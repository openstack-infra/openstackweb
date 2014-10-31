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

class CallForSpeakersRegistrationForm extends HoneyPotForm {

	function __construct($controller, $name)
	{

		// Define fields //////////////////////////////////////

		$fields = new FieldList (
			new TextField('FirstName', 'First Name'),
			new TextField('Surname', 'Last Name'),
			new TextField('Email', 'Email')
		);

		$startSurveyButton = new FormAction('CreateAccount', 'Create Account');
		$actions = new FieldList(
			$startSurveyButton
		);

		$validator = new RequiredFields("FirstName", "Surname", "Email");


		parent::__construct($controller, $name, $fields, $actions, $validator);

	}

	function forTemplate()
	{
		return $this->renderWith(array(
			$this->class,
			'Form'
		));
	}


	function CreateAccount($data, $form)
	{


		//Check for existing member email address
		if ($member = Member::get()->filter('Email',Convert::raw2sql($data['Email']))->first() ) {
			//Set error message
			$form->AddErrorMessage('Email', "Sorry, that email address already exists. Please choose another or login with that email.", 'bad');
			//Set form data from submitted values
			Session::set("FormInfo.Form_CallForSpeakersRegistrationForm.data", $data);
			//Return back to form
			return Controller::curr()->redirectBack();
		}

		//Otherwise create new member and log them in
		$Member = new Member();
		$form->saveInto($Member);
		$Member->write();

		$Member->login();

		//Find or create the 'user' group
		if (!$userGroup = Group::get()->filter('Code', 'site-accounts')->first()) {
			$userGroup = new Group();
			$userGroup->Code = "site-accounts";
			$userGroup->Title = "Site Accounts";
			$userGroup->Write();
			$Member->Groups()->add($userGroup);
		}
		//Add member to user group
		$Member->Groups()->add($userGroup);

		return Controller::curr()->redirect($form->controller->Link());
	}
}
