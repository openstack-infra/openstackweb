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
class EditSpeakerProfileForm extends SafeXSSForm {

    function __construct($controller, $name, $speaker = null, $member = null, $email = null)
    {
        // Get the city for the current member
        if($member) {
            $country = $member->Country;
        } else {
            $country = '';
        }

        // Fields
        $FirstNameField = new TextField('FirstName', "Speaker's First Name");
        $LastNameField = new TextField('Surname', "Speaker's Last Name");
        $TitleField = new TextField('Title',"Speaker's Title");
        $BioField = new TextAreaField('Bio',"Speaker's Bio");

        // ID Fields
        $SpeakerIDField = new HiddenField('SpeakerID', 'SpeakerID', "");
        $MemberIDField = new HiddenField('MemberID','MemberID');

        // Replace Fields
        $ReplaceBioField = new HiddenField('ReplaceBio', 'ReplaceBio',0);
        $ReplaceNameField = new HiddenField('ReplaceName','ReplaceName',0);
        $ReplaceSurnameField = new HiddenField('ReplaceSurname','ReplaceSurname',0);

        // IRC and Twitter
        $IRCHandleField = new TextField('IRCHandle', 'IRC Handle <em>(Optional)</em>');
        $TwiiterNameField = new TextField('TwitterName', 'Twitter Name <em>(Optional)</em>');

        // Upload Speaker Photo
        $PhotoField = new CustomUploadField('Photo', 'Upload a speaker photo');
	    $PhotoField->setCanAttachExisting(false);
	    $PhotoField->setAllowedMaxFileNumber(1);
	    $PhotoField->setAllowedFileCategories('image');
	    $PhotoField->setFolderName('profile-images');
	    $sizeMB = 2; // 1 MB
	    $size = $sizeMB * 1024 * 1024; // 1 MB in bytes
	    $PhotoField->getValidator()->setAllowedMaxFileSize($size);
	    $PhotoField->setCanPreviewFolder(false); // Don't show target filesystem folder on upload field

        // Opt In Field
        $OptInField = new CheckboxField ('AviableForBureau',"I'd like to be in the speaker bureau.");

        $Divider = new LiteralField ('hr','<hr/>');

        // Funded Travel
        $FundedTravelField = new CheckboxField ('FundedTravel',"My Company would be willing to fund my travel to events.");

        // Country Field
        $CountryCodes = CountryCodes::$iso_3166_countryCodes;
        $CountryField = new DropdownField('Country', 'Country', $CountryCodes);
        $CountryField->setValue($country);

        $ExpertiseField = new TextareaField('Expertise', 'Topics of interest (one per line)');

        // Load Existing Data if present
        if($speaker) {
	        $this->record = $speaker;
            $FirstNameField->setValue($speaker->FirstName);
            $LastNameField->setValue($speaker->Surname);
            $BioField->setValue($speaker->Bio);
            $SpeakerIDField->setValue($speaker->ID);
            $MemberIDField->setValue($speaker->MemberID);
            $TitleField->setValue($speaker->Title);
            $IRCHandleField->setValue($speaker->IRCHandle);
            $TwiiterNameField->setValue($speaker->TwitterName);
            $OptInField->setValue($speaker->AviableForBureau);
            $FundedTravelField->setValue($speaker->FundedTravel);
            $ExpertiseField->setValue($speaker->Expertise);
	        $PhotoField->setValue(null, $speaker);
        } elseif($member) {
            $FirstNameField->setValue($member->FirstName);
            $LastNameField->setValue($member->Surname);
            $BioField->setValue($member->Bio);
            $MemberIDField->setValue($member->ID);
            $IRCHandleField->setValue($member->IRCHandle);
            $TwiiterNameField->setValue($member->TwitterName);
        }


        $fields = new FieldList(
            $FirstNameField,
            $LastNameField,
            $TitleField,
            $BioField,
            $SpeakerIDField,
            $MemberIDField,
            $ReplaceBioField,
            $ReplaceNameField,
            $ReplaceSurnameField,
            $IRCHandleField,
            $TwiiterNameField,
            $PhotoField,
            $Divider,
            $OptInField,
            $FundedTravelField,
            $CountryField,
            $ExpertiseField
        );

        $actions = new FieldList(
            new FormAction('addAction', 'Save Speaker Details')
        );

        $validator = new RequiredFields(
            'FirstName',
            'Surname',
            'Title'
        );

        parent::__construct($controller, $name, $fields, $actions, $validator);
    }

    function addAction($data, $form) {

        //Check for a logged in member
        if ($CurrentMember = Member::currentUser()) {
            // Find a site member (in any group) based on the MemberID field
            $id = Convert::raw2sql($data['MemberID']);
            $member = DataObject::get_by_id("Member", $id);

            if ($data['SpeakerID'] && is_numeric($data['SpeakerID'])) {
                $speaker = DataObject::get_by_id("Speaker", $data['SpeakerID']);
            } elseif ($member) {
                $speaker = DataObject::get_one("Speaker", "`MemberID` = ".$member->ID);
            }

            if (!$speaker) {
                $speaker = new Speaker();
            }

            //Find or create the 'speaker' group
            if(!$userGroup = DataObject::get_one('Group', "Code = 'speakers'"))
            {
                $userGroup = new Group();
                $userGroup->Code = "speakers";
                $userGroup->Title = "Speakers";
                $userGroup->Write();
                $member->Groups()->add($userGroup);
            }
            //Add member to the group
            $member->Groups()->add($userGroup);

            if(($data['Country'] != '') && ($data['Country'] != $member->Country)) {
                $member->Country = convert::raw2sql($data['Country']);
            }

            if ($data['ReplaceName'] == 1) {
                $member->FirstName = $data['FirstName'];
            }
            if ($data['ReplaceSurname'] == 1) {
                $member->LastName = $data['LastName'];
            }
            if ($data['ReplaceBio'] == 1) {
                $member->Bio = $data['Bio'];
            }

            $member->write();

			$form->saveInto($speaker);
            $speaker->MemberID = $member->ID;
            $speaker->AdminID = Member::currentUser()->ID;
            // Attach Photo
            if($member->PhotoID && $speaker->PhotoID == 0) {
                $speaker->PhotoID = $member->PhotoID;
            }

            $speaker->AskedAboutBureau = TRUE;

            $speaker->write();


	        $this->controller->redirect($this->controller()->Link().'speaker?saved=1');

        }
        else {
            return Security::PermissionFailure($this->controller, 'You must be <a href="/join">registered</a> and logged in to edit your profile:');
        }
    }
}