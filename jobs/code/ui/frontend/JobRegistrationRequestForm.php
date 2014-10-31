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
 * Class JobRegistrationRequestForm
 */
final class JobRegistrationRequestForm extends HoneyPotForm {

	function __construct($controller, $name, $use_actions = true) {
		$fields = new FieldList;
		//point of contact
		$fields->push($point_of_contact_name = new TextField('point_of_contact_name','Name'));
		$fields->push($point_of_contact_email = new EmailField('point_of_contact_email','Email'));
		//main info

		$fields->push($title =new TextField('title','Title'));
		$fields->push($url = new TextField('url','Url'));
		$fields->push($description = new HtmlEditorField('description','Description'));
		$fields->push($instructions =new HtmlEditorField('instructions','Instructions To Apply'));
		$fields->push($expiration_date = new TextField('expiration_date','Expiration Date'));
		$fields->push($company = new TextField('company_name','Company'));

		$point_of_contact_name->addExtraClass('job_control');
		$point_of_contact_email->addExtraClass('job_control');
		$title->addExtraClass('job_control');
		$url->addExtraClass('job_control');
		$description->addExtraClass('job_control');
		$instructions->addExtraClass('job_control');
		$expiration_date->addExtraClass('job_control');
		$company->addExtraClass('job_control');


		//location

		$ddl_locations = new DropdownField('location_type','Location Type', array('N/A'=>'N/A','Remote'=>'Remote','Various'=>'Add a Location'));
		$ddl_locations->addExtraClass('location_type');
		$ddl_locations->addExtraClass('job_control');
		$fields->push($ddl_locations);

		$fields->push($city    = new TextField('city','City'));
		$fields->push($state   = new TextField('state','State'));
		$fields->push($country = new CountryDropdownField('country','Country'));

		$city->addExtraClass('physical_location');
		$state->addExtraClass('physical_location');
		$country->addExtraClass('physical_location');

		// Guard against automated spam registrations by optionally adding a field
		// that is supposed to stay blank (and is hidden from most humans).
		// The label and field name are intentionally common ("username"),
		// as most spam bots won't resist filling it out. The actual username field
		// on the forum is called "Nickname".
		$fields->push(new TextField('user_name','UserName'));

		// Create action
		$actions = new FieldList();
		if($use_actions)
			$actions->push(new FormAction('saveJobRegistrationRequest', 'Save'));
		// Create validators
		$validator = new ConditionalAndValidationRule(array(new HtmlPurifierRequiredValidator('title','company_name','instructions','description'), new RequiredFields('point_of_contact_name','point_of_contact_email')));

		$this->addExtraClass('job-registration-form');
		parent::__construct($controller, $name, $fields, $actions, $validator);
	}

	function forTemplate() {
		return $this->renderWith(array(
			$this->class,
			'Form'
		));
	}

	function submit($data, $form) {
		// do stuff here
	}
}
