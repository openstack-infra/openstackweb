<?php
/**
 * Copyright 2014 Openstack.org
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/
/**
 * Defines the CallForSpeakers page type
 */
class CallForSpeakersPage extends Page {
   static $db = array(
 	 );
   static $has_one = array(
   );
   
}
 
class CallForSpeakersPage_Controller extends Page_Controller {
	function init() {

      $getVars = $this->request->getVars();
      if (isset($getVars['hidden'])) Session::set('HiddenTalk', $getVars['hidden']);

	    parent::init();
	}

  static $allowed_actions = array(
      'AddTalkForm',
      'TalkDetails',
      'DeleteTalk',
      'DeleteSpeaker',
      'RegisterForm',
      'ConfirmSpeaker',
      'CallForSpeakersForm',
      'SpeakerList',
      'AddSpeakerForm',
      'SpeakerDetails',
      'SpeakerDetailsForm',
      'ListPresentations' => 'admin',
      'EmailSubmitters' => 'admin',
      'AdjustSpeakerEmail' => 'admin',
      'AdjustedSpeakerList' => 'admin',
      'SpeakersWithoutEmails' => 'admin',
      'AdminsToContact' => 'admin',
      'EmailSpeakers' => 'admin',
      'EmailSubmitters' => 'admin',
      'CancelDelete',
      'OnsitePhoneForm',
      'doSavePhoneNumber',
      'PhoneNumberSaved',
      'SpeakerBureau',
      'SpeakerBureauForm'
  );

  public function CurrentSummit() {
    return $this->parent()->Summit();
  }

  public function PastSubmissionDeadline() {

    $CurrentSummit = $this->CurrentSummit();

    if ($CurrentSummit->AcceptSubmissionsEndDate == NULL) {
      // No deadline provided, so return false
      return FALSE;
    } else {
      // Look at the deadline (including full 24hrs of that day) and see if it's less than the time now.
      return (strtotime($CurrentSummit->AcceptSubmissionsEndDate) + (60 * 60 * 24)) < strtotime('now');
    }
  }

  public function SubmissionDeadline() {
    $CurrentSummit = $this->CurrentSummit();
    $d = new Date(null);
    $d->setValue($CurrentSummit->AcceptSubmissionsEndDate);
    return $d;
  }

  function EmailOnNewTalk() {
    // Email a user if a new talk was added but they also haven't already been emailed this session
    if ((Session::get('NewTalkAdded') == TRUE) && (Session::get('UserBeenEmailed') == FALSE)) {

      $CurrentSummit = $this->CurrentSummit();
      $Member = Member::currentUser();

      // Send the email
      $To = $Member->Email;
      $Subject = "Thank you for your speaking submission!";

      $data['Member'] = $Member;
      $data['Summit'] = $CurrentSummit;
      $data['Link'] = $this->Link();

      $email = EmailFactory::getInstance()->buildEmail(CALL_4_SPEAKERS_FROM_EMAIL, $To, $Subject);
      $email->setTemplate('PresentationSubmitted');
      $email->populateTemplate($data);
      if(EmailUtils::validEmail($To)) $email->send();    

      // adjust session vars
      Session::set('UserBeenEmailed',TRUE);
      Session::clear('NewTalkAdded');
    }
  }

  public function MemberTalks() {
    if ($MemberID = Member::currentUserID()) {
      $this->EmailOnNewTalk();
      return $this->Parent()->Summit()->TalksByMemberID($MemberID);
    }
  }

  public function AllPresentations() {
    return Talk::get();
  }

  public function AddTalkForm() {
        $CallForSpeakersForm = new CallForSpeakersForm($this, 'AddTalkForm');
        // Keeps users from seeing the 'CSFR attack' error
        $CallForSpeakersForm->disableSecurityToken();

        $talkID = NULL;
        $Params = $this->getURLParams();
        if(isset($Params['ID'])) $talkID = convert::raw2sql($Params['ID']);

        if($talkID) {
          $Talk = Talk::get()->byID($talkID);
          if($Talk && $Talk->CanEdit()) {
              $CallForSpeakersForm->loadDataFrom($Talk);
          } else {
              return $this->httpError(400, 'This talk could not be found.');
          }
        }

        $CallForSpeakersForm->loadDataFrom($this->request->postVars());

        return $CallForSpeakersForm;
    }  

  public function RegisterForm() {
      return new CallForSpeakersRegistrationForm($this, 'RegisterForm');
  }

  public function DisplayCompletedMessage() {
    $getVars = $this->request->getVars();
    return (isset($getVars['completed']));
  }

  public function AddSpeakerForm() {
      $Params = $this->getURLParams();
      $talkID = convert::raw2sql($Params['ID']);

      $AddSpeakerForm = new AddSpeakerForm($this, 'AddSpeakerForm', $talkID);

      return $AddSpeakerForm;

  }  

  public function OnsitePhoneForm() {
      
      $speakerHash = Session::get('ConfirmSpeakerHash');
      $OnsitePhoneForm = new OnsitePhoneForm($this, 'OnsitePhoneForm', $speakerHash);

      return $OnsitePhoneForm;

  } 


  public function ConfirmSpeaker() {

      $getVars = $this->request->getVars();
      if(isset($getVars['key'])) $hashKey = Convert::raw2sql($getVars['key']);
      if(isset($hashKey)) $speakerID = substr(base64_decode($hashKey),3);
    
      if(isset($speakerID) && is_numeric($speakerID) && $Speaker = Speaker::get()->byID($speakerID))
      {       
        
          Session::set('ConfirmSpeakerHash', $hashKey);

          $Speaker->Confirmed = TRUE;
          $Speaker->write();  

          $data['ConfirmedSpeaker'] = $Speaker;
          return $this->Customise($data);
      } 
      else 
      {
          return $this->httpError(404, 'Sorry, this speaker confirmation code does not seem to be correct.');
      }

  }

  public function SpeakerList() {

    Session::clear('AddSpeakerProcess.TalkID');
    Session::clear('AddSpeakerProcess.Email');
    Session::clear('AddSpeakerProcess.SpeakerID');

    $Params = $this->getURLParams();
    $TalkID = convert::raw2sql($Params['ID']);

    if(is_numeric($TalkID) && $Talk = Talk::get()->byID($TalkID))
    { 
      Session::set('AddSpeakerProcess.TalkID', $TalkID);

      if($Talk->CanEdit()) {
        return $this;
      } else {
        return $this->httpError(302, 'Sorry you do not have permission to edit this presentation.');
      }
    } else {
      return $this->httpError(400, 'This talk could not be found.');
    }

  }

  public function TalkID() {
    $Params = $this->getURLParams();
    if (isset($Params['ID']) && is_numeric($Params['ID'])) return $Params['ID'];
  }

  public function SpeakerDetails() {

    $Params = $this->getURLParams();
    if (isset($Params['ID']) && is_numeric($Params['ID'])) Session::set('AddSpeakerProcess.SpeakerID',$Params['ID']);
    return $this;
        
  }

  public function SpeakerDetailsForm() {

        // New speaker being set up from session date (add mode)
        $TalkID = Session::get('AddSpeakerProcess.TalkID');
        $Email = Session::get('AddSpeakerProcess.Email');
        $SpeakerID = Session::get('AddSpeakerProcess.SpeakerID');

        // Find a site member (in any group) based on this email address
        $member = Member::get()->filter('Email',Convert::raw2sql($Email))->first();

        // See if the member has been assigned a speaker record
        if ($SpeakerID) {
           $speaker = Speaker::get()->byID($SpeakerID);
        } elseif ($member) {
           $speaker = Speaker::get()->filter('MemberID',$member->ID)->first();
        } else {
           $speaker = NULL;
        }

        if(!$TalkID) {
          return $this->httpError(500,'No talk ID was specified.');
        } elseif (!$Talk = Talk::get()->byID($TalkID) ) {
          return $this->httpError(500,'There is no talk by this ID.');
        } elseif (!$Talk->CanEdit()) {
          return $this->httpError(500,'You cannot edit this presentation.');
        }

        $SpeakerDetailsForm = New SpeakerDetailsForm($this, 'SpeakerDetailsForm', $TalkID, $speaker, $member, $Email);

        if ($speaker) {
          $SpeakerDetailsForm->loadDataFrom($speaker, FALSE, array('Photo'));
        } elseif ($member) {
          $SpeakerDetailsForm->loadDataFrom($member, FALSE, array('Photo'));
        }

        return $SpeakerDetailsForm;

        Session::clear('AddSpeakerProcess.TalkID');
        Session::clear('AddSpeakerProcess.Email');
        Session::clear('AddSpeakerProcess.SpeakerID');

  }

  public function SpeakerBureauForm() {
      $SpeakerBureauForm = New SpeakerBureauForm($this, 'SpeakerBureauForm');
      return $SpeakerBureauForm;
  }

  public function SpeakersForThisPresentation() {
    $Params = $this->getURLParams();
    $TalkID = convert::raw2sql($Params['ID']);
    
    if(is_numeric($TalkID) && $Talk = Talk::get()->byID($TalkID))
    { 
      $MemberID = Member::currentUser()->ID;
      if($MemberID && $Talk->CanEdit($MemberID)) {
        return $Talk->Speakers();
      }
    } 
  }

  public function DeleteSpeaker() {

    $Params = $this->getURLParams();
    $SpeakerID = NULL;
    $Speaker = NULL;
    $TalkID = NULL;

    if(isset($Params['ID']) && is_numeric($Params['ID'])) $SpeakerID = $Params['ID'];
    if(isset($Params['OtherID']) && is_numeric($Params['OtherID'])) $TalkID = $Params['OtherID'];


    if($SpeakerID && ($Speaker = Speaker::get()->byID($SpeakerID)) && $TalkID && ($Talk = Talk::get()->byID($TalkID))) {

      if ($Talk->Speakers()->count() == 1) {
        $this->setMessage('Error', 'You cannot delete the only speaker. Please add another speaker first.');
        $this->redirectBack();
        return;
      }

      if ($Speaker->CanRemoveFromTalk($TalkID)) {
        $Talk->Speakers()->remove($Speaker);
        $Talk->write();
      }        
    } else {
      return httpError(500,'Could not delete this speaker.');
    }

	  Controller::curr()->redirectBack();

  }

  public function DeleteTalk() {
    $Params = $this->getURLParams();
    $TalkID = NULL;
    $Talk = NULL;

    if(isset($Params['ID']) && is_numeric($Params['ID'])) $TalkID = $Params['ID'];

    if($TalkID && $Talk = Talk::get()->byID($TalkID)) {
      if ($Talk->CanEdit() && $this->TalkToDeleteConfirmed()) {
        $Talk->delete();
        Session::clear('TalkToDelete');
      } else {
        Session::set('TalkToDelete',$TalkID);
        Controller::curr()->redirect($this->Link());
        return;
      }
    } else {
      return $this->httpError(500,'Could not delete this presentation.');
    }

	  Controller::curr()->redirectBack();

  } 

  public function TalkToDeleteConfirmed() {
    return Session::get('TalkToDelete');
  }

  public function CancelDelete() {
    Session::clear('TalkToDelete');
	Controller::curr()->redirect($this->Link());
  }


}