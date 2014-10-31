<?php
/**
 * Copyright 2014 Openstack Foundation
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
 * Used to vote on summit presentations
 */

class PresentationVotingPage extends Page {
  static $db = array(
  );
  static $has_one = array(
  );
  static $defaults = array(
        'ShowInMenus' => false
  );

  // Used to filter searches to only the presentations we want to see
  static $talk_limit_clause = ' AND MarkedToDelete IS NULL';

}
 
class PresentationVotingPage_Controller extends Page_Controller {

    static $allowed_actions = array(
          'SpeakerVotingLoginForm',
          'Presentation',
          'Category',
          'SaveRating',
          'SaveComment',
          'Done',
          'FullPresentationList',
          'ShowFullPresentationList',
          'SearchForm'
    );

    function init() {
      if (!$this->request->param('Action')) $this->redirect($this->Link().'Presentation/');
      parent::init();
    }

    function CategoryList() {
      return array(
          array('ID' => 25, 'Name' => 'Enterprise IT Strategies', 'URLSegment' => 'enterprise-it-strategies'),
          array('ID' => 26, 'Name' => 'Telco Strategies', 'URLSegment' => 'telco-strategies'),
          array('ID' => 27, 'Name' => 'How to Contribute', 'URLSegment' => 'how-to-contribute'),
          array('ID' => 28, 'Name' => 'Planning Your OpenStack Project', 'URLSegment' => 'planning-your-openStack-project'),
          array('ID' => 29, 'Name' => 'Products Tools Services', 'URLSegment' => 'products-tools-services'),
          array('ID' => 30, 'Name' => 'User Stories', 'URLSegment' => 'user-stories'),
          array('ID' => 31, 'Name' => 'Community Building', 'URLSegment' => 'community-building'),
          array('ID' => 32, 'Name' => 'Related OSS Projects', 'URLSegment' => 'related-oss-projects'),
          array('ID' => 33, 'Name' => 'Operations', 'URLSegment' => 'operations'),
          array('ID' => 34, 'Name' => 'Cloud Security', 'URLSegment' => 'cloud-security'),
          array('ID' => 35, 'Name' => 'Compute', 'URLSegment' => 'compute'),
          array('ID' => 36, 'Name' => 'Storage', 'URLSegment' => 'storage'),
          array('ID' => 37, 'Name' => 'Networking', 'URLSegment' => 'networking'),
          array('ID' => 38, 'Name' => 'Public & Hybrid Clouds', 'URLSegment' => 'public-and-hybrid-clouds'),
          array('ID' => 39, 'Name' => 'Hands-on Labs', 'URLSegment' => 'hands-on-labs'),
          array('ID' => 40, 'Name' => 'Targeting OpenStack Clouds', 'URLSegment' => 'targeting-openstack-clouds'),
          array('ID' => 41, 'Name' => 'Cloudfunding', 'URLSegment' => 'cloudfunding'),
      );
    }

    // Build a page for GCSE
    function FullPresentationList() {
      return Talk::get()->filter('MarkedToDelete',0)->sort('PresentationTitle','ASC');
    }

    // Render category buttons
    function CategoryLinks() {

      $items = new ArrayList();
      $Categories = $this->CategoryList();

      foreach($Categories as $Category) {
        $items->push( new ArrayData( $Category ) ); 
      }

      return $items;

    }

    function CategoryIDFromURL($CategoryURL) {

      $Categories = $this->CategoryList();

      foreach ($Categories as $key => $val) {
        if ($val['URLSegment'] === $CategoryURL) {
            return $Categories[$key]['ID'];
        }
      }
      return null;
    }

    function Category() {
      $URLSegment = $this->request->param("ID");

      if($URLSegment == 'All') {
        Session::clear('CategoryID');
        $Category = NULL;
      } elseif($URLSegment) {
        $Category = $this->CategoryIDFromURL($URLSegment);
        Session::set('CategoryID',$Category);
      }

      $this->redirect($this->Link().'Presentation/'.$this->RandomPresentationURLSegment($Category));

    }

    function PresentationByID($ID) {
      // Clean ID to be safe
      $ID = Convert::raw2sql($ID);
      if(is_numeric($ID)) {
        $Presentation = Talk::get()->byID($ID);
        return $Presentation;
      }
    }

    function SearchForm() {
      $SearchForm = new PresentationVotingSearchForm($this, 'SearchForm');
      $SearchForm->disableSecurityToken();
      return $SearchForm;
    }

    function doSearch($data, $form) {

      $Talks = NULL;

      if($data['Search'] && strlen($data['Search']) > 1) {
         $query = Convert::raw2sql($data['Search']);

          $sqlQuery = new SQLQuery();
          $sqlQuery->setSelect( array(
            'DISTINCT Talk.URLSegment',
            'Talk.PresentationTitle',
            // IMPORTANT: Needs to be set after other selects to avoid overlays
            'Talk.ClassName',
            'Talk.ClassName',
            'Talk.ID'
          ));
          $sqlQuery->setFrom( array(
            "Talk",
            "left join Talk_Speakers on Talk.ID = Talk_Speakers.TalkID left join Speaker on Talk_Speakers.SpeakerID = Speaker.ID"
          ));
          $sqlQuery->setWhere( array(
            "(Talk.MarkedToDelete IS FALSE) AND (Talk.SummitID = 3) AND ((concat_ws(' ', Speaker.FirstName, Speaker.Surname) like '%$query%') OR (Talk.PresentationTitle like '%$query%') or (Talk.Abstract like '%$query%'))"
          ));
           
          $result = $sqlQuery->execute();
           
          // let Silverstripe work the magic

	      $arrayList = new ArrayList();

	      foreach($result as $rowArray) {
		      // concept: new Product($rowArray)
		      $arrayList->push(new $rowArray['ClassName']($rowArray));
	      }

	      $Talks = $arrayList;

      }
      
      // Clear the category if one was set
      Session::set('CategoryID',NULL);
      $data['SearchMode'] = TRUE;
      if($Talks) $data["SearchResults"] = $Talks;

      return $this->Customise($data);

   }

   function ShowIntro() {
      $MemberID = Member::currentUserID();
      If ($MemberID) {
        $Votes = SpeakerVote::get()->filter('VoterID', $MemberID);
        if(!$Votes && !(Session::get('IntroShown'))) {
          Session::set('IntroShown',TRUE);
          return 'yes';
        }
      } else {
        return 'no';
      }
      return 'no';

   }

    function CurrentVote($TalkID) {
      if(Member::currentUserID()) {
        $SpeakerVote = SpeakerVote::get()->filter(array('VoterID'=>Member::currentUserID(),'TalkID'=>$TalkID))->first();
        if ($SpeakerVote) return $SpeakerVote->VoteValue;
      }
    }

    function RandomPresentationURLSegment($Category = NULL) {

      $Talk = NULL;
      $CategoryID = Session::get('CategoryID');
      $currentMemberID = Member::currentUserID();

      // Set up a filter to not display any presentations that have already recieved votes for a logged in member
      $CurrentUserJoin = NULL;
      if($currentMemberID) $CurrentUserJoin = " AND SpeakerVote.VoterID = ".Member::currentUserID();

      $CurrentUserWhere = NULL;
      if($currentMemberID) $CurrentUserWhere = " `SpeakerVote`.VoteValue IS NULL";

      if(!$CategoryID) {

        if($currentMemberID) {
	      $Talks = Talk::get()->filter(array('MarkedToDelete'=>0, 'SummitID' => 3))->sort('RAND()')->leftJoin('SpeakerVote',"(Talk.ID = SpeakerVote.TalkID" . $CurrentUserJoin . ")");
	        if(!empty($CurrentUserWhere))
		        $Talks->where($CurrentUserWhere);
        } else {
	        $Talks = Talk::get()->filter(array('MarkedToDelete'=>0, 'SummitID' => 3))->sort('RAND()');
        }

        if($Talks) $Talk = $Talks->first();

      } else {

        if($currentMemberID) {
	        $Talks = Talk::get()->filter(array('MarkedToDelete'=>0, 'SummitID' => 3, 'SummitCategoryID'=>$CategoryID))->leftJoin('SpeakerVote',"(Talk.ID = SpeakerVote.TalkID" . $CurrentUserJoin . ")")->sort('RAND()');
	        if(!empty($CurrentUserWhere))
		        $Talks->where($CurrentUserWhere);
        } else {
	        $Talks = Talk::get()->filter(array('MarkedToDelete'=>0, 'SummitID' => 3, 'SummitCategoryID'=>$CategoryID))->sort('RAND()');
        }

        if($Talks) $Talk = $Talks->first();
      }

      if($Talk) {
        return $Talk->URLSegment;
      } else {
        return 'none';
      }

    }

    function Done() {

      $Member = Member::currentUser();

      if($Member) {

          $CategoryID = Session::get('CategoryID');
          if(is_numeric($CategoryID)) $Category = SummitCategory::get()->byID($CategoryID);
          if(isset($Category)) $data["CategoryName"] = $Category->Name;

          $Subject = 'Voting Event';


          if($Category) {
            $Body = $Member->FirstName . ' ' . $Member->Surname . ' just completed voting for all presentations in the category ' . $Category->Name;
          } else {
            $Body = $Member->FirstName . ' ' . $Member->Surname . ' just completed voting for every single presentation listed!';
          }

          $email = EmailFactory::getInstance()->buildEmail(PRESENTATION_VOTING_EVENT_FROM_EMAIL, PRESENTATION_VOTING_EVENT_TO_EMAIL, $Subject, $Body);
          $email->send();

          //return our $Data to use on the page
          return $this->Customise($data);
      }

    }    

    function PresentationByURLSegment($URLSegment) {
      // Clean ID to be safe
      $URLSegment = Convert::raw2sql($URLSegment);
      // Look up a specific presentation
      $Presentation = Talk::get()->filter(array('URLSegment'=>$URLSegment,'SummitID'=>3))->first();
      return $Presentation;
    }        

    // Used as a URL action to display a presentation
    function Presentation() {
      $URLSegment = $this->request->param("ID");

      if($URLSegment == 'none') {
        $this->redirect($this->Link().'Done');
        return;
      }

      if($URLSegment) {
        $Talk = $this->PresentationByURLSegment($URLSegment);

        if($Talk && $Talk->MainTopic != Session::get('Category')) Session::clear('Category');

      } else {
        $CategoryID = Session::get('CategoryID');        
        $this->redirect($this->Link().'Presentation/'.$this->RandomPresentationURLSegment($CategoryID));
        return;
      }

      if($Talk) {
        $data["Presentation"] = $Talk;
        $data["VoteValue"] = $this->CurrentVote($Talk->ID);
        
        $CategoryID = Session::get('CategoryID');
        if(is_numeric($CategoryID)) $Category = SummitCategory::get()->byID($CategoryID);
        if(isset($Category)) $data["CategoryName"] = $Category->Name;

        //return our $Data to use on the page
        return $this->Customise($data);
      } else {
        //Talk not found
        return $this->httpError(404, 'Sorry that talk could not be found');
      }
    }

    function ClientIP() {
      $inSSL = ( isset($_SERVER['SSL']) || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ) ? true : false;
      if($inSSL) {
        $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
        $clientIP = $_SERVER['REMOTE_ADDR'];
      }
      return $clientIP;
    }

    function SpeakerVotingLoginForm() {
      $SpeakerVotingLoginForm = new SpeakerVotingLoginForm($this, 'SpeakerVotingLoginForm');
      return $SpeakerVotingLoginForm;
    }


    function SaveRating() {

      if(!Member::currentUserID()) {
        return $this->httpError(403, 'You need to be logged in to perform this action.');
      }

      $rating = '';
      $TalkID = '';

      if(isset($_GET['rating']) && is_numeric($_GET['rating'])) {
        $rating = $_GET['rating'];
      }

      if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $TalkID = $_GET['id'];
      }

      $Member = member::currentUser();

      $validRatings = array(-1,0,1,2,3);

      if($Member && isset($rating) && (in_array((int)$rating, $validRatings, true)) && $TalkID) {

        $previousVote = SpeakerVote::get()->filter(array('TalkID'=>$TalkID,'VoterID'=>$Member->ID))->first();

        if(!$previousVote) {
          $speakerVote = new SpeakerVote;
          $speakerVote->TalkID = $TalkID;
          $speakerVote->VoteValue = $rating;
          $speakerVote->IP = $this->ClientIP();
          $speakerVote->VoterID = $Member->ID;
          $speakerVote->write();
          
          $this->redirect($this->Link().'Presentation/'.$this->RandomPresentationURLSegment());
    
        } else {
          $previousVote->VoteValue = $rating;
          $previousVote->IP = $this->ClientIP();
          $previousVote->write();

          $this->redirect($this->Link().'Presentation/'.$this->RandomPresentationURLSegment());

        }
        
      } else {
        return 'no rating saved.';
      }
    }

    function SaveComment($data) {

      if(!Member::currentUserID()) {
        return $this->httpError(403, 'You need to be logged in to perform this action.');
      }

      $VarsPassed = $data->requestVars();
      $comment = Convert::raw2sql($VarsPassed['comment']);
      $TalkID = Convert::raw2sql($VarsPassed['submission']);
      $Member = member::currentUser();

      if($Member) {
        $previousVote = SpeakerVote::get()->filter(array('TalkID'=>$TalkID,'VoterID'=>$Member->ID))->first();
        if(!$previousVote) {
          $speakerVote = new SpeakerVote;
          $speakerVote->TalkID = $TalkID;
          $speakerVote->Note = $comment;
          $speakerVote->IP = $this->ClientIP();
          $speakerVote->VoterID = $Member->ID;
          $speakerVote->write();
          return $VarsPassed["comment"];
        } else {
          $previousVote->Note = $comment;
          $previousVote->IP = $this->ClientIP();
          $previousVote->write();
          return $VarsPassed["comment"];
        }
        
      } else {
        return false;
      }
    }

}
