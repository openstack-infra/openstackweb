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
class SpeakerDetailsForm extends HoneyPotForm
{

	function __construct($controller, $name, $talkID = null, $speaker = null, $member = null, $email = null)
	{

		// Fields
		$FirstNameField = new TextField('FirstName', "Speaker's First Name");
		$LastNameField = new TextField('Surname', "Speaker's Last Name");
		$TitleField = new TextField('Title', "Speaker's Title");
		$BioField = new HTMLEditorField('Bio', "Speaker's Bio");

		// ID Fields
		$TalkIDField = new HiddenField('TalkID', "TalkID", $talkID);
		$SpeakerIDField = new HiddenField('SpeakerID', 'SpeakerID', "");
		$MemberIDField = new HiddenField('MemberID', 'MemberID');
		$EmailField = new HiddenField('Email', 'Email');

		// IRC and Twitter
		$IRCHandleField = new TextField('IRCHandle', 'IRC Handle <em>(Optional)</em>');
		$TwiiterNameField = new TextField('TwitterName', 'Twitter Name <em>(Optional)</em>');

		// Upload Speaker Photo
		$PhotoField = new UploadField('Photo', 'Upload a speaker photo');
		$PhotoField->setCanAttachExisting(false);
		$PhotoField->setAllowedMaxFileNumber(1);
		$PhotoField->setAllowedFileCategories('image');
		$PhotoField->setFolderName('profile-images');

		$sizeMB = 2; // 1 MB
		$size = $sizeMB * 1024 * 1024; // 2 MB in bytes
		$PhotoField->getValidator()->setAllowedMaxFileSize($size);

		$PhotoField->setCanPreviewFolder(false); // Don't show target filesystem folder on upload field


		// Load Existing Data if present
		if ($speaker) {
			$FirstNameField->setValue($speaker->FirstName);
			$LastNameField->setValue($speaker->Surname);
			$BioField->setValue($speaker->Bio);
			$SpeakerIDField->setValue($speaker->ID);
			$MemberIDField->setValue($speaker->MemberID);
			$TitleField->setValue($speaker->Title);


			$IRCHandleField->setValue($speaker->IRCHandle);
			$TwiiterNameField->setValue($speaker->TwitterName);
		} elseif ($member) {
			$FirstNameField->setValue($member->FirstName);
			$LastNameField->setValue($member->Surname);
			$BioField->setValue($member->Bio);
			$BioField->setValue($member->Bio);
			$MemberIDField->setValue($member->ID);

			$IRCHandleField->setValue($member->IRCHandle);
			$TwiiterNameField->setValue($member->TwitterName);
		}

		if ($email) {
			$EmailField->setValue($email);
		}

		$fields = new FieldList(
			$FirstNameField,
			$LastNameField,
			$TitleField,
			$BioField,
			$TalkIDField,
			$SpeakerIDField,
			$MemberIDField,
			$IRCHandleField,
			$TwiiterNameField,
			$PhotoField,
			$EmailField
		);

		$actions = new FieldList(
			new FormAction('addAction', 'Save Speaker Details')
		);

		$validator = new RequiredFields(
			'FirstName',
			'Surname',
			'Title'
		);

		Requirements::customScript('
	      tinymce.init({
            mode: "textareas",
            resize: false,
            menubar: false,
            statusbar: false,
            setup : function(ed) {
               ed.onChange.add(function(ed, l) {
                    tinymce.triggerSave();
                });
            }
        });
      ');

		parent::__construct($controller, $name, $fields, $actions, $validator);
	}

	function forTemplate()
	{
		return $this->renderWith(array(
			$this->class,
			'Form'
		));
	}

	function addAction($data, $form)
	{

		// Clear session details
		Session::clear('AddSpeakerProcess.TalkID');
		Session::clear('AddSpeakerProcess.Email');
		Session::clear('AddSpeakerProcess.SpeakerID');

		// Find and load the talk from the hidden field
		$TalkID = Convert::raw2sql($data['TalkID']);
		if ($TalkID && is_numeric($TalkID)) {
			$Talk = Talk::get()->byID($TalkID);
		}

		// Find a site member (in any group) based on the MemberID field
		if ($data['MemberID'] && is_numeric($data['MemberID'])) {
			$id = Convert::raw2sql($data['MemberID']);
			$member = Member::get()->byID($id);
		} else {
			$member = NULL;
		}

		if ($data['SpeakerID'] && is_numeric($data['SpeakerID'])) {

			$speaker = Speaker::get()->byID((int)$data['SpeakerID']);
		} elseif ($member) {
			$speaker = Speaker::get()->filter('MemberID', $member->ID)->first();
		} else {
			$speaker = NULL;
		}

		if (!$member) {
			$member = new Member();
			$form->saveInto($member);
		}


		//Find or create the 'speaker' group

		if (!$userGroup = Group::get()->filter('Code', 'speakers')->first()) {
			$userGroup = new Group();
			$userGroup->Code = "speakers";
			$userGroup->Title = "Speakers";
			$userGroup->Write();
			$member->Groups()->add($userGroup);
		}
		//Add member to the group
		$member->Groups()->add($userGroup);
		$member->write();


		if (!$speaker) {
			// No speaker, so we'll create one
			$speaker = new Speaker();
		}


		$form->saveInto($speaker);
		$speaker->MemberID = $member->ID;
		$speaker->AdminID = Member::currentUser()->ID;

		// Attach Photo
		if ($member->PhotoID && $speaker->PhotoID == 0) {
			$speaker->PhotoID = $member->PhotoID;
		}

		$speaker->write();

		if (isset($Talk)) {
			$Talk->Speakers()->add($speaker);
			$Talk->write();
		}

		// See if speaker should be prompted to join speaker bureau
		if ($this->ShouldPromptForBureau($speaker)) {
			Session::set('SpeakerBureau.TalkID', $TalkID);
			Controller::curr()->redirect(Controller::curr()->Link() . 'SpeakerBureau/');
		} else {
			Controller::curr()->redirect($form->controller()->Link() . 'SpeakerList/' . $data['TalkID']);
		}

	}

	function ShouldPromptForBureau($speaker)
	{
		if (!$speaker->AskedAboutBureau) return TRUE;
	}


}