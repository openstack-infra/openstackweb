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
 * Class AddSpeakerForm
 */
class AddSpeakerForm extends HoneyPotForm
{

	static $allowed_actions = array(
		'done',
	);

	function __construct($controller, $name, $talkID)
	{

		// Email Address Field
		$EmailAddressField = new TextField('Email', "Speaker's Email Address");

		$formData = Session::get("FormInfo.Form_CallForSpeakersRegistrationForm.data");

		if ($formData['Email']) {
			$email = $formData['Email'];
		} else {
			$email = Member::currentUser()->Email;
		}

		$EmailAddressField->setValue($email);

		// Talk ID
		$TalkField = new HiddenField('TalkID', "TalkID", $talkID);

		$fields = new FieldList(
			$EmailAddressField,
			$TalkField
		);

		$talk = NULL;

		if ($talkID != NULL) {
			// Look to see if the presentation has at least one speaker attached
			$talkID = Convert::raw2sql($talkID);
			$talk = Talk::get()->byID($talkID);
		}

		if ($talk && $talk->HasSpeaker()) {

			$actions = new FieldList(
				new FormAction('addAction', 'Add New Speaker'),
				new FormAction('done', 'Done Editing Speakers')
			);

		} else {

			$actions = new FieldList(
				new FormAction('addAction', 'Add First Speaker')
			);

		}

		parent::__construct($controller, $name, $fields, $actions);
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

		if (!EmailUtils::validEmail($data['Email'])) {
			//Set error message
			$form->AddErrorMessage('Email', "That doesn't appear to be a valid email address.", 'bad');
			//Set form data from submitted values
			Session::set("FormInfo.Form_CallForSpeakersRegistrationForm.data", $data);
			//Return back to form
			return Controller::curr()->redirectBack();
		}


		// Set up session variables and forward to SpeakerDetails action

		// Find and load the talkID from the hidden field
		$TalkID = Convert::raw2sql($data['TalkID']);
		if (is_numeric($TalkID)) {

			Session::set('AddSpeakerProcess.TalkID', $TalkID);

		}

		Session::set('AddSpeakerProcess.Email', $data['Email']);

		Controller::curr()->redirect($form->controller()->Link() . 'SpeakerDetails/');

	}

	function done($data, $form)
	{

		Controller::curr()->redirect($form->controller()->Link() . '?completed=1');

	}

}
