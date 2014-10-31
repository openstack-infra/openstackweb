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
 * Defines the logo permission form page type
 */
class LogoRightsPage extends Page {

	private static $db = array(
    	'LogoURL' => 'Text',
    	'AllowedMembers' => 'Text',
    	'EchoSignCode' => 'Text'
		);

	private static $has_many = array(
		// Relates the submission data objects to this page
		'LogoRightsSubmissions' => 'LogoRightsSubmission'
	);
		
	public function getCMSFields()
	{
		$fields = parent::getCMSFields();
		
			// Adds a sortable table of the submissions into the CMS
		    $manager = new GridField(
			'LogoRightsSubmissions',
			'Logos',
			 $this->LogoRightsSubmissions()
		);
				
		$fields->addFieldToTab("Root.Submisions", $manager);
    	$fields->addFieldToTab('Root.Main', new TextField('LogoURL','Path to logo downloads page (URL)'), 'Content');
    	$fields->addFieldToTab('Root.Main', new TextField('EchoSignCode','Numerical Code For EchoSign Document'), 'Content');
		return $fields;
	}
	
}
 
class LogoRightsPage_Controller extends Page_Controller {

	// enables the form to be submitted
	static $allowed_actions = array(
		'LogoForm'
	);

	function AllowedMember() {
		// check to see if a member is logged in and if their email address is in the allowed members field
		$currentMember = Member::currentUser();
		if($currentMember && (stristr($this->AllowedMembers,$currentMember->Email))) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function LogoForm() {
		// Create fields 
		$fields = new FieldList(
		new TextField('Name', 'Name*'),
		new EmailField('Email', 'Email*'),
		new TextField('PhoneNumber', 'Phone Number*'),
		new TextField('ProductName', 'Product Name*'),
		new TextField('CompanyName', 'Company Name*'),
		new TextField('Website', 'Company Website (URL)*'),
		new TextAreaField('StreetAddress', 'Street Address*', 2, 20),
		new TextField('City', 'City*'),
		new TextField('State', 'State'),
		new TextField('Zip', 'Zip / Postal Code'),
		new TextField('Country', 'Country*'),
		new CheckboxField('BehalfOfCompany','This submission is on behalf of my company')
		);
		
		// Create action
		$actions = new FieldList(
		new FormAction('doLogoPermissions', 'I AGREE')
		);
		
		// Create Validators
		$validator = new RequiredFields('Name', 'Email', 'PhoneNumber', 'CompanyName', 'Website', 'StreetAddress','City', 'Country');
		
		// Form(controller, form name, fields, actions, validator)
		return new Form($this, 'LogoForm', $fields, $actions, $validator);
	}
	
	// called when the form is submitted (see 'form action' above)
	function doLogoPermissions($data, $form) {
	    $submission = new LogoRightsSubmission();
	    $form->saveInto($submission);
	    //Giving the submission a page ID establishes the relationship required for it to work in the DOM view in the CMS.
		$submission->LogoRightsPageID = $this->ID;
	    $submission->write();
	    
	    //Send email alert about submission
		$From = $data['Email'];
		$To = "logo@openstack.org";
		$Subject = "New OpenStack Trademark Agreement";     
		$email = EmailFactory::getInstance()->buildEmail($From, $To, $Subject);
		$email->setTemplate('Trademark');
		$email->populateTemplate($data);
		$email->send();	    
	    
	    Session::set('LogoSignoffCompleted', true);
	
		$this->redirect($this->LogoURL);
	}
	
	
	function init() {
		parent::init();

		// Shorten Text Area
		Requirements::customCSS("textarea {height:3em; width:300px;}");

		// adding JS for jquery based validation
		Requirements::javascript("http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js");
		Requirements::javascript("http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js");
		Requirements::customScript('
				jQuery(document).ready(function() {
					jQuery("#Form_LogoForm").validate({
						rules: {
								Name: "required",
						Email: {
								required: true,
								email: true
						},
								PhoneNumber: "required",
								CompanyName: "required",
								ProductName: "required",
								Website: "required",
								StreetAddress: "required",
								City: "required",
								Country: "required"
					},
					messages: {
								Name: "Oops... Please provide your name.",
								Email: "Oops... Please provide your email address.",
								PhoneNumber: "Oops... Please provide your phone number.",
								CompanyName: "Oops... Please provide your company name.",
								ProductName: "Oops... Please provide your product name.",
								Website: "Oops... Please provide your company website.",
								StreetAddress: "Oops... Please provide your street address.",
								City: "Oops... Please provide your city.",
								Country: "Oops... Please provide your country."
								}
						});
					});
				');

			Requirements::javascript("http://assets.zendesk.com/external/zenbox/v2.5/zenbox.js");
			Requirements::css("http://assets.zendesk.com/external/zenbox/v2.5/zenbox.css");
			Requirements::customScript('
				 if (typeof(Zenbox) !== "undefined") {
				    Zenbox.init({
				      dropboxID:   "20115046",
				      url:         "https://openstack.zendesk.com",
				      tabID:       "Ask Us",
				      tabColor:    "black",
				      tabPosition: "Right"
				    });
				  }

			');


	}
	
	function BrandingMenu() {
		return TRUE;
	}

}