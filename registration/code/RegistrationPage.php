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
class RegistrationPage extends Page
{

}

class RegistrationPage_Controller extends Page_Controller
{

	//Allow our form as an action
	static $allowed_actions = array(
		'RegistrationForm',
		'results',
		'AffiliationEditForm'
	);

	function init()
	{
		parent::init();

		Requirements::css("themes/openstack/css/chosen.css", "screen,projection");
		Requirements::css("themes/openstack/javascript/jquery-ui-1.10.3.custom/css/smoothness/jquery-ui-1.10.3.custom.min.css");
		Requirements::css("registration/css/affiliations.css");


		Requirements::javascript("themes/openstack/javascript/chosen.jquery.min.js");
		Requirements::javascript("themes/openstack/javascript/pure.min.js");
		Requirements::javascript("themes/openstack/javascript/jquery.serialize.js");
		Requirements::javascript("themes/openstack/javascript/jquery.cleanform.js");
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::javascript("themes/openstack/javascript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js");
		Requirements::javascript("themes/openstack/javascript/jquery.ui.datepicker.validation.package-1.0.1/jquery.ui.datepicker.validation.js");
		Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");

		Requirements::css('registration/css/registration.page.css');
		Requirements::javascript("registration/javascript/affiliations.js");
		Requirements::javascript("registration/javascript/registration.page.js");
	}

	public function AffiliationEditForm()
	{
		$AffiliationEditForm = new AffiliationEditForm($this, 'AffiliationEditForm');
		return $AffiliationEditForm;
	}

	//Generate the registration form
	function RegistrationForm()
	{
		// Name Set
		$FirstNameField = new TextField('FirstName', "First Name");
		$LastNameField = new TextField('Surname', "Last Name");

		// Email Addresses
		$PrimaryEmailField = new TextField('Email', "Primary Email Address");
		// New Gender Field
		$GenderField = new OptionSetField('Gender', 'I identify my gender as:', array(
			'Male' => 'Male',
			'Female' => 'Female',
			'Specify' => 'Let me specify',
			'Prefer not to say' => 'Prefer not to say'
		));
		$GenderSpecifyField = new TextField('GenderSpecify', 'Specify your gender');
		$GenderSpecifyField->addExtraClass('hide');

		$StatementOfInterestField = new TextField('StatementOfInterest', 'Statement of Interest');
		$StatementOfInterestField->addExtraClass('autocompleteoff');


		$affiliations = new FieldGroup(
			new HeaderField('Affiliations'),
			new LiteralField("add-affiliation", "<a class='roundedButton' id='add-affiliation' title='Add New Affiliation' href='#'>Add New Affiliation</a>"),
			new LiteralField("affiliations-container", "<div id='affiliations-container'></div>")
		);

		$fields = new FieldList(
			$FirstNameField,
			$LastNameField,
			new LiteralField('break', '<hr/>'),
			$PrimaryEmailField,
			new LiteralField('instructions', '<p>This will also be your login name.</p>'),
			new LiteralField('break', '<hr/>'),
			$GenderField,
			$GenderSpecifyField,
			new LiteralField('instructions', '<p>It\'s perfectly acceptable if you choose not to tell us: we appreciate you becoming a member of OpenStack Foundation. The information will remain private and only used to monitor our effort to improve gender diversity in our community.</p>'),
			new LiteralField('break', '<hr/>'),
			$affiliations,
			new HiddenField("Affiliations", "Affiliations", ""),
			new LiteralField('instructions', '<p>For our purposes, an affiliation is defined as any company where you are an officer, director or employee, or any person or company that has paid you more than $60,000 USD as an independent contractor in the last 12 months.  Please list all affiliations which meet this criteria.</p>'),
			$StatementOfInterestField,
			new LiteralField('instructions', '<p>Your statement of interest should be a few words describing your objectives or plans for OpenStack.</p>'),
			new LiteralField('break', '<hr/>'),
			new TextField('Address', _t('Addressable.ADDRESS', 'Street Address (Line1)')),
			new TextField('Suburb', _t('Addressable.SUBURB', 'Street Address (Line2)')),
			new TextField('City', _t('Addressable.CITY', 'City'))
		);

		$label = _t('Addressable.STATE', 'State');
		if (is_array($this->allowedStates)) {
			$fields->push(new DropdownField('State', $label, $this->allowedStates));
		} elseif (!is_string($this->allowedStates)) {
			$fields->push(new TextField('State', $label));

		}

		$AdressField = new TextField(
			'Postcode', _t('Addressable.POSTCODE', 'Postcode')
		);

		$fields->push($AdressField);

		$label = _t('Addressable.COUNTRY', 'Country');
		if (is_array($this->allowedCountries)) {
			$countryField = new DropdownField('Country', $label, $this->allowedCountries);
			$countryField->addExtraClass('chzn-select');
			$fields->push($countryField);
		} elseif (!is_string($this->allowedCountries)) {
			$countryField = new CountryDropdownField('Country', $label);
			$countryField->addExtraClass('chzn-select');
			$fields->push($countryField);
		}

		$fields->push(new LiteralField('break', '<hr/>'));

		$fields->push(new ConfirmedPasswordField('Password',
			'Password'
		));


		$actions = new FieldList(
			new FormAction('doRegister', 'Submit My Application')
		);


		$validator = new Member_Validator(
			'FirstName',
			'Surname',
			'Email',
			'StatementOfInterest',
			'Address',
			'City',
			'Country',
			'Password'
		);

		return new HoneyPotForm($this, 'RegistrationForm', $fields, $actions, $validator);
	}

	//Submit the registration form
	function doRegister($data, $form)
	{
		if (!isset($data["Affiliations"]) || empty($data["Affiliations"])) {
			//Set error message
			$form->AddErrorMessage('Affiliations', "Sorry, You must at least enter one valid Affiliation.", 'bad');
			//Set form data from submitted values
			Session::set("FormInfo.Form_RegistrationForm.data", $data);
			//Return back to form
			return $this->redirectBack();;
		}

		$new_affiliations = json_decode($data["Affiliations"]);

		//Check for existing member email address
		if ($member = Member::get()->filter('Email', Convert::raw2sql($data['Email']))->first()) {
			//Set error message
			$form->AddErrorMessage('Email', "Sorry, that email address already exists. Please choose another.", 'bad');
			//Set form data from submitted values
			Session::set("FormInfo.Form_RegistrationForm.data", $data);
			//Return back to form
			return $this->redirectBack();
		}

		//Otherwise create new member and log them in
		$Member = new Member();
		$form->saveInto($Member);

		if (isset($data['Gender'])) {
			$Gender = $data['Gender'];

			if ($Gender != 'Male' && $Gender != 'Female' && $Gender != 'Prefer not to say') {
				$Member->Gender = Convert::raw2sql($data['GenderSpecify']);
			}
		}

		$Member->write();

		// Assign the member to be part of the foundation group
		$Member->addToGroupByCode('foundation-members');

		// Set up member with legal agreement for becoming an OpenStack Foundation Member
		$legalAgreement = new LegalAgreement();
		$legalAgreement->MemberID = $Member->ID;
		$legalAgreement->LegalDocumentPageID = 422;

		$legalAgreement->write();

		$Member->login();

		//Find or create the 'user' group
		if (!$userGroup = Group::get()->filter('Code', 'users')->first()) {
			$userGroup = new Group();
			$userGroup->Code = "users";
			$userGroup->Title = "Users";
			$userGroup->Write();
			$Member->Groups()->add($userGroup);
		}
		//Add member to user group
		$Member->Groups()->add($userGroup);


		foreach ($new_affiliations as $key => $newAffiliation) {
			$dbAffiliation = new Affiliation();
			$org_name = Convert::raw2sql($newAffiliation->OrgName);
			$org_name = trim($org_name);
			AffiliationController::Save($dbAffiliation, $newAffiliation, $org_name, $Member);
		}

		PublisherSubscriberManager::getInstance()->publish('new_user_registered', array($Member->ID));

		//Get profile page
		if ($ProfilePage = EditProfilePage::get()->first()) {

			//send Thank you email

			$config = SiteConfig::current_site_config();
			if ($config->RegistrationSendMail &&
				!empty($config->RegistrationFromMessage) &&
				!empty($config->RegistrationSubjectMessage) &&
				!empty($config->RegistrationHTMLMessage) &&
				!empty($config->RegistrationPlainTextMessage)
			) {


				$registration_email = new CustomEmail($config->RegistrationFromMessage,
					$Member->Email,
					$config->RegistrationSubjectMessage,
					$config->RegistrationHTMLMessage,
					$config->RegistrationPlainTextMessage);
				$registration_email->send();
			}
			//Redirect to profile page with success message
			return $this->redirect($ProfilePage->Link('?success=1'));

		}

	}

	function LegalTerms()
	{
		return LegalDocumentPage::get()->byID(422);
	}


	// This method is used to autocomplete match org names as they are entered
	// It's called via Ajax on the OrgName field

	public function results()
	{
		if ($query = $this->getSearchQuery()) {
			$query = Convert::raw2xml($query);

			// Search Orgs against the query.

			$Results = Org::get()->filter('Name:PartialMatch', $query);

			// For AutoComplete
			if (Director::is_ajax() && $Results) {

				$Orgs = $Results->map('ID', 'Name');
				$Suggestions = "";

				foreach ($Orgs as $Org) {
					$Suggestions = $Suggestions . $Org . '|' . '1' . "\n";
				}

				return $Suggestions;
			}

		}

	}

	function getSearchQuery()
	{
		if ($this->request)
			return $this->request->getVar("q");
	}
}