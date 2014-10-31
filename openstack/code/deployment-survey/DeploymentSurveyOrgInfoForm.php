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
class DeploymentSurveyOrgInfoForm extends Form
{
	private $first_name_field;
	private $last_name_field;
	private $email_field;

	function __construct($controller, $name)
	{

		// Define fields //////////////////////////////////////
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::javascript('themes/openstack/javascript/deployment.survey.org.info.form.js');


		if (is_array($this->allowedCountries)) {
			$allowedCountries = $this->allowedCountries;
		}
		$CountryCodes = CountryCodes::$iso_3166_countryCodes;
		$org_field = null;
		$current_user = Member::currentUser();
		$current_affiliations = $current_user->getCurrentAffiliations();
		$org_field_name = 'Organization';
		if (!$current_affiliations)
			$org_field = new TextField('Organization', 'Your Organization Name');
		else {
			if (count($current_affiliations) > 1) {
				$source = array();
				foreach ($current_affiliations as $a) {
					$org = $a->Organization();
					$source[$org->ID] = $org->Name;
				}
				$source['0'] = "-- New One --";
				$org_field_name = 'OrgID';
				$ddl = new DropdownField('OrgID', 'Your Organization', $source);
				$ddl->setEmptyString('-- Select Your Organization --');
				$org_field = new FieldGroup();
				$org_field->push($ddl);
				$org_field->push($txt = new TextField('Organization', ''));
				$txt->addExtraClass('new-org-name');
			} else {
				$org_field = new TextField('Organization', 'Your Organization Name', $current_user->getOrgName());
			}
		}

		$fields = new FieldList (
			$org_field,
			$this->first_name_field = new TextField('FirstName', 'First Name', $current_user->FirstName),
			$this->last_name_field = new TextField('Surname', 'Last Name', $current_user->Surname),
			$this->email_field = new HiddenField('Email', 'Email', $current_user->Email),
			new LiteralField('Break', '<p>(Changing information here will also update your OpenStack Foundation profile.)</p>'),
			new LiteralField('Break', ColumnFormatter::$left_column_start),
			new DropdownField(
				'Industry',
				'Industry',
				ArrayUtils::AlphaSort(DeploymentSurvey::$industry_options, array('unspecified' => '-- Please Select One --'), array('Other' => 'Other (please specify)') )
			),
			new TextField('OtherIndustry', 'Other Industry'),
			new LiteralField('Break', ColumnFormatter::$right_column_start),
			new DropdownField(
				'OrgSize',
				'Organization Size',
				DeploymentSurvey::$organization_size_options
			),
			new LiteralField('Break', ColumnFormatter::$end_columns),
			new LiteralField('Break', '<hr/>'),
			new LiteralField('Break', '<p>Where is the primary location or headquarters of your organization?</p>'),
			new LiteralField('Break', ColumnFormatter::$left_column_start),
			new TextField('PrimaryCity', 'City'),
			new LiteralField('Break', ColumnFormatter::$right_column_start),
			new TextField('PrimaryState', 'State/Province'),
			new LiteralField('Break', ColumnFormatter::$end_columns),
			new DropdownField(
				'PrimaryCountry',
				'Country',
				$CountryCodes
			),
			new LiteralField('Break', '<hr/>'),
			new TextField('Title', 'Your Job Title'),
			new CheckboxSetField('OpenStackInvolvement', 'What best describes your involvement with OpenStack?', ArrayUtils::AlphaSort(DeploymentSurvey::$openstack_involvement_options)),
			new CheckboxSetField('InformationSources', 'Where do end up finding information about using OpenStack, after using search engines and talking to your colleagues?', ArrayUtils::AlphaSort(DeploymentSurvey::$information_options, null, array('Other' => 'Other (please specify)'))),
			new TextField('OtherInformationSources', 'Other information sources'),
			new CheckboxField('OkToContact', 'The OpenStack Foundation and User Committee can communicate with me in the future about my usage'),
			new LiteralField('Break', '<hr/>'),
			new LiteralField('Break', '<p>We would love to hear how OpenStack and the OpenStack Foundation can better meet your needs. These free-form questions are optional, but will provide valuable insights.</p>'),
			new TextAreaField('FurtherEnhancement', 'What areas of OpenStack software require further enhancement? (optional)'),
			new TextAreaField('FoundationUserCommitteePriorities', 'What should be the priorities for the Foundation and User Committee during the coming year? (optional)'),
			new TextAreaField('WhatDoYouLikeMost', 'What do you like most about OpenStack? (optional)'),
			new CheckboxSetField(
				'BusinessDrivers',
				'What are your business drivers for using OpenStack? (optional)',
				ArrayUtils::AlphaSort(DeploymentSurvey::$business_drivers_options,null, array('Other' => 'Other (please specify)'))),
			new TextField('OtherBusinessDrivers', 'Other business drivers'),
			new DropdownField(
				'OpenStackRecommendRate',
				'How likely is it that you would recommend OpenStack to a friend or colleague? (10 being the best)',
				DeploymentSurvey::$openstack_recommendation_rate_options
			),
			new TextareaField('OpenStackRecommendation', 'Are there any additional comments you would pass as part of this recommendation?')
		);


		// $prevButton = new CancelFormAction($controller->Link().'Login', 'Previous Step');
		$nextButton = new FormAction('NextStep', '  Next Step  ');

		$actions = new FieldList(
			$nextButton
		);


		// Create Validators
		$validator = new RequiredFields($org_field_name, 'Title', 'PrimaryCity', 'PrimaryCountry', 'FirstName', 'Surname', 'Email');
		parent::__construct($controller, $name, $fields, $actions, $validator);
	}

	function loadDataFrom($data, $clearMissingFields = false, $fieldList = null)
	{
		$res = parent::loadDataFrom($data, $clearMissingFields, $fieldList);
		$current_user = Member::currentUser();
		if ($data instanceof DeploymentSurvey) {
			if (empty($data->FirstName)) {
				$this->first_name_field->setValue($current_user->FirstName);
			}
			if (empty($data->Surname)) {
				$this->last_name_field->setValue($current_user->Surname);
			}
			if (empty($data->Email)) {
				$this->email_field->setValue($current_user->Email);
			}
		}
		return $res;
	}

	function forTemplate()
	{
		return $this->renderWith(array(
			$this->class,
			'Form'
		));
	}
}