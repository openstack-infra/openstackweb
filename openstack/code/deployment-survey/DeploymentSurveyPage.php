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
 * Defines the DeploymentSurveyPage
 */
class DeploymentSurveyPage extends Page
{
	static $db = array(
		'LoginPageTitle'         => 'HTMLText',
		'LoginPageContent'       => 'HTMLText',
		'LoginPageSlide1Content' => 'HTMLText',
		'LoginPageSlide2Content' => 'HTMLText',
		'LoginPageSlide3Content' => 'HTMLText',
		'ThankYouContent'        => 'HTMLText',
	);

	static $has_one = array();

	function getCMSFields()
	{
		$fields = parent::getCMSFields();
		//login page content
		$fields->addFieldToTab('Root.Login', new HtmlEditorField('LoginPageTitle','Page Main Title',10));
		$fields->addFieldToTab('Root.Login', new HtmlEditorField('LoginPageContent','Content'));
		$fields->addFieldToTab('Root.Login', new HtmlEditorField('LoginPageSlide1Content','Slide #1 Content',20));
		$fields->addFieldToTab('Root.Login', new HtmlEditorField('LoginPageSlide2Content','Slide #2 Content',20));
		$fields->addFieldToTab('Root.Login', new HtmlEditorField('LoginPageSlide3Content','Slide #3 Content',20));
		//thank u content
		$fields->addFieldToTab('Root.Thank You', new HtmlEditorField('ThankYouContent','Content'));
		return $fields;
	}

	public function getLoginPageTitle(){
		$res = (string)$this->getField('LoginPageTitle');
		if(empty($res)){
			$res = 'OpenStack User Survey: Welcome!';
		}
		return $res;
	}

	public function getLoginPageContent(){
		$link = Controller::curr()->Link();
		$res = (string)$this->getField('tLoginPageContent');
		if(empty($res)) {
			$res = <<< HTML

			<p>This survey provides users an opportunity to influence the community and software
		direction. By sharing information about your configuration and requirements, the OpenStack
		Foundation User Committee will be able to advocate on your behalf.</p>
		<p><a href="{$link}faq" class="roundedButton">More Information About The Survey</a></p>
		<hr/>

		<h1>Get Started</h1>
HTML;
		}
		return $res;
	}

	public function getLoginPageSlide1Content(){
		$res = (string)$this->getField('LoginPageSlide1Content');
		if(empty($res)){
			$res = 'This is the <strong>OpenStack User Survey</strong> for OpenStack cloud users and operators.';
		}
		return $res;
	}


	public function getLoginPageSlide2Content(){
		$res = (string)$this->getField('LoginPageSlide2Content');
		if(empty($res)){
			$res = 'It should only take <strong>10 minutes</strong> to complete.';
		}
		return $res;
	}

	public function getLoginPageSlide3Content(){
		$res = (string)$this->getField('LoginPageSlide3Content');
		if(empty($res)){
			$res = 'All of the information you provide is <strong>confidential</strong> to the Foundation (unless you specify otherwise).';
		}
		return $res;
	}
	public function getThankYouContent(){

		$res = (string)$this->getField('ThankYouContent');
		if(empty($res)) {
			$res = <<< HTML
		<h2>Thank You!</h2>

<p>We aggregate data from the survey every six months before the Summit. A video
presentation by the User Committee and slides from May 2014 are available to
<a href="https://www.openstack.org/summit/openstack-summit-atlanta-2014/session-videos/presentation/2014-spring-user-survey-results-and-feedback">view now</a>.</p>

<p>If you'd like to get involved in working with other OpenStack users,
find out more about the <a href="/foundation/user-committee">User Committee</a>.</p>

HTML;
		}
		return $res;

	}

}

class DeploymentSurveyPage_Controller extends Page_Controller
{

	static $allowed_actions = array(
		'Login',
		'OrgInfo',
		'AppDevSurvey',
		'Deployments',
		'DeploymentDetails',
		'MoreDeploymentDetails',
		'ThankYou',
		'Form',
		'RemoveDeployment',
		'AddDeployment',
		'SkipDeployments',
		'logout',
		'StartSurvey',
		'RegisterForm',
		'SavePasswordForm',
		'SavePassword',
		'CheckEmail',
		'MemberStart'
	);


	public function CheckEmail()
	{
		$email = $this->request->getVar('Email');
		//Check for existing member email address
		$res = true;
		if ($member = Member::get()->filter('Email', Convert::raw2sql($email))->first()) {
			$res = false;
		}
		echo json_encode($res);
	}

	function init()
	{
		parent::init();

		// require custom CSS
		Requirements::css("themes/openstack/css/user-survey.css");

		Requirements::css("themes/openstack/css/chosen.css", "screen,projection");
		Requirements::css("themes/openstack/css/jquery.autocomplete.css");


		Requirements::block(SAPPHIRE_DIR . '/thirdparty/behaviour/behaviour.js');
		Requirements::block(SAPPHIRE_DIR . '/thirdparty/prototype/prototype.js');
		Requirements::block(SAPPHIRE_DIR . '/javascript/prototype_improvements.js');


		Requirements::javascript("themes/openstack/javascript/chosen.jquery.min.js");
		Requirements::javascript("themes/openstack/javascript/jquery.autocomplete.min.js");


		Requirements::customScript("
                jQuery(document).ready(function($) {

                $('#DeploymentSurveyOrgInfoForm_Form_PrimaryCountry').chosen();

                $('#DeploymentSurveyOrgInfoForm_Form_Organization').autocomplete('/join/register/results', {
                      minChars: 3,
                      selectFirst: true,
                      autoFill: true,
                  });

            });

      ");


		// No one is logged in
		if (!Member::currentUser()) {

			// These are the actions available to non-logged in members
			$nonLoggedInAvailablActions = array('Login', 'MemberStart', 'StartSurvey', 'DeploymentSurveyRegistrationForm', 'RegisterForm', 'faq', 'CheckEmail');

			if (!in_array($this->request->param('Action'), $nonLoggedInAvailablActions)) {
				$this->redirect($this->Link() . 'Login');
			}

			// A Member is logged in
		} else {

			if ($this->request->param('Action') == NULL) {
				// If there's no action (step) specified, look for the current step using the function below
				$CurrentStep = $this->CurrentStep();
				// Go to the new step
				$this->redirect($this->Link() . $CurrentStep);
			} else {
				// Use the controller action as the current step and set in session
				$DesiredStep = $this->request->param('Action'); // the page requested
				if ($DesiredStep == 'Login') return;

				$survey = $this->GetCurrentSurvey(); // the current survey

				$DesiredStepIndex = array_search($DesiredStep, DeploymentSurvey::$steps); // The index of this step in the list
				$HighestStepAllowedIndex = array_search($survey->HighestStepAllowed, DeploymentSurvey::$steps); // The index of the highest allowed step in the list

				// Set the current step to the new desired step as long as the previous step was completed.
				if ($DesiredStepIndex !== FALSE && $DesiredStepIndex <= ($HighestStepAllowedIndex)) {
					$survey->CurrentStep = $DesiredStep;
					$survey->write();
				} elseif ($DesiredStepIndex !== FALSE) {
					$this->redirect($this->Link() . $survey->CurrentStep);
				}
			}

		}
	}

	function GetCurrentSurvey()
	{
		// Look for an existing survey
		if ($CurrentUserID = Member::currentUserID()) {
			// look for a deployment survey for this user
			$DeploymentSurvey = dataObject::get_one('DeploymentSurvey', 'MemberID = ' . $CurrentUserID);
			if (!$DeploymentSurvey) {
				// Create a new deployment survey
				$DeploymentSurvey = new DeploymentSurvey();
				$DeploymentSurvey->MemberID = $CurrentUserID;
				$DeploymentSurvey->CurrentStep = 'OrgInfo';
				$DeploymentSurvey->UpdateDate = SS_Datetime::now()->Rfc2822();
				$DeploymentSurvey->Write();
			}

			return $DeploymentSurvey;

		}
	}

	// Looks up the current step of the process
	function CurrentStep()
	{
		// Check the database for a DeploymentSurvey with a current step
		if ($CurrentUserID = Member::currentUserID()) {
			// look for a deployment survey for this user
			$DeploymentSurvey = dataObject::get_one('DeploymentSurvey', 'MemberID = ' . $CurrentUserID);
			if ($DeploymentSurvey && $DeploymentSurvey->CurrentStep != NULL) {
				$CurrentStep = $DeploymentSurvey->CurrentStep;
			} else {
				// member is logged in, but has no current step in a deployment survey
				$CurrentStep = 'OrgInfo'; // 1st Step of survey
			}

		} else {
			// No one logged in
			$CurrentStep = 'Login';
		}

		return $CurrentStep;
	}

	public function NextStep($data, $form)
	{
		// Save our work
		$survey = $this->GetCurrentSurvey();
		$form->saveInto($survey);
		//Update Member if need be
		if (isset($data['Organization'])) {
			$org_data = Convert::raw2sql(trim($data['Organization']));
			if (!empty($org_data)) {
				$org = DataObject::get_one('Org', " Name = '{$org_data}' ");
				if (!$org) {
					$org = new Org;
					$org->Name = $org_data;
					$org->IsStandardizedOrg = false;
					$org->write();
					//register new request
					$new_request = new OrganizationRegistrationRequest();
					$new_request->MemberID = Member::currentUserID();
					$new_request->OrganizationID = $org->ID;
					$new_request->write();
				}
				$this->updateMember($org_data);
				$survey->OrgID = $org->ID;
				$survey->UpdateDate = SS_Datetime::now()->Rfc2822();
				$survey->write();
			}
		}

		if ($form instanceof DeploymentSurveyOrgInfoForm) {
			//check if user info has changed
			$current_user = Member::currentUser();
			$current_user->FirstName = $data['FirstName'];
			$current_user->Surname = $data['Surname'];
			$current_user->write();
			if (isset($data['OpenStackInvolvement']) && is_array($data['OpenStackInvolvement']) && array_key_exists('Cloud Consumer', $data['OpenStackInvolvement'])) {
				//normal flow - Send them to user-survey/AppDevSurvey as normal
				$newIndex = array_search($this->CurrentStep(), DeploymentSurvey::$steps) + 1;
			} else //  Send them to user-survey/Deployments
				$newIndex = array_search('Deployments', DeploymentSurvey::$steps);
		}

		if ($newIndex > count(DeploymentSurvey::$steps)) $newIndex = count(DeploymentSurvey::$steps);
		$CurrentStep = DeploymentSurvey::$steps[$newIndex];
		Session::set('CurrentStep', $CurrentStep);
		$survey->CurrentStep = $CurrentStep;
		$survey->HighestStepAllowed = $CurrentStep;
		$survey->UpdateDate = SS_Datetime::now()->Rfc2822();
		$survey->write();
		$this->redirect($this->Link() . $CurrentStep);
	}

	public function Login()
	{
		return array();
	}

	public function MemberStart()
	{
		$member = null;
		if (isset($_REQUEST['m'])) {
			$member = Member::get()->byID((int)$_REQUEST['m']);
		}

		// Check whether we are merely changin password, or resetting.
		if (isset($_REQUEST['t']) && $member && $member->validateAutoLoginToken($_REQUEST['t'])) {
			$member->logIn();
			return $this->redirect($this->Link() . "OrgInfo");
		} elseif (Member::currentUser()) {
			return $this->redirect($this->Link() . "OrgInfo");
		} else {
			return $this->redirect($this->Link() . "OrgInfo");
		}

	}

	public function RegisterForm()
	{
		return new DeploymentSurveyRegistrationForm($this, 'RegisterForm');
	}

	public function SavePasswordForm()
	{
		return new DeploymentSurveySavePasswordForm($this, 'SavePasswordForm');
	}

	// Populate the template's $Form area with the proper form depending on the current step for the user
	public function Form()
	{

		$currentForm = 'DeploymentSurvey' . $this->currentStep() . 'Form';
		$form = new $currentForm($this, 'Form');

		// Load the member's survey to use populate the form
		$survey = $this->GetCurrentSurvey();

		// But check to see if there are any form errors (otherwise you'll overwrite the session data)
		$errors = Session::get('FormInfo.' . $form->FormName() . '.errors');

		if ($survey && !$errors) $form->loadDataFrom($survey->data());
		return $form;
	}

	// Used in the DeploymentSurveyPage_Deployments.ss template
	public function DeploymentList()
	{
		$survey = $this->GetCurrentSurvey();
		if ($survey && $survey->Deployments()) {
			$survey->HighestStepAllowed = 'DeploymentDetails';
			$survey->UpdateDate = SS_Datetime::now()->Rfc2822();
			$survey->write();
			return $survey->Deployments();
		}

	}

	public function DeploymentDetails()
	{
		if (isset($_GET['DeploymentID'])) {
			Session::set('CurrentDeploymentID', $_GET['DeploymentID']);
		} else {
			Session::clear('CurrentDeploymentID');
		}

		return array();
	}

	public function LoadDeployment($id)
	{
		if ($id && is_numeric($id) && $CurrentDeployment = Deployment::get()->byID($id)) {
			$DeploymentSurvey = $CurrentDeployment->DeploymentSurvey();
			if ($DeploymentSurvey && $DeploymentSurvey->MemberID == Member::currentUserID()) return $CurrentDeployment;
		}

		return NULL;

	}

	public function LoadAppDevSurvey()
	{
		$survey = $this->GetCurrentSurvey();
		if ($survey && $survey->AppDevSurveys() && $survey->AppDevSurveys()->Count() > 0) {
			return $survey->AppDevSurveys()->First();
		}

		return NULL;
	}

	public function RemoveDeployment()
	{

		if (isset($_GET['DeploymentID'])) $id = convert::raw2sql($_GET['DeploymentID']);

		if ($id && $Deployment = $this->LoadDeployment($id)) {
			$Deployment->Delete();
			$this->redirectBack();
		}
	}

	public function UpdateMember($Organization)
	{
		$Member = Member::currentUser();
		$NewOrg = convert::raw2sql($Organization);

		// If a new org name was provided for the member, find / create the new org and update the member record
		if (!is_null($NewOrg) && !$Member->hasCurrentAffiliation($NewOrg)) {
			$newAffiliation = new StdClass;
			$newAffiliation->StartDate = date('Y-m-d');
			$newAffiliation->EndDate = null;
			$newAffiliation->Current = 1;
			$newAffiliation->JobTitle = "";
			$newAffiliation->Role = "";
			AffiliationController::Save(new Affiliation(), $newAffiliation, $NewOrg, $Member);
		}
	}

	function AddDeployment()
	{
		$survey = $this->GetCurrentSurvey();
		$survey->CurrentStep = 'DeploymentDetails';
		$survey->HighestStepAllowed = 'DeploymentDetails';
		$survey->UpdateDate = SS_Datetime::now()->Rfc2822();
		$survey->write();
		$this->redirect($this->Link() . 'DeploymentDetails');
	}

	function SkipDeployments()
	{
		$survey = $this->GetCurrentSurvey();
		$survey->CurrentStep = 'ThankYou';
		$survey->HighestStepAllowed = 'ThankYou';
		$survey->UpdateDate = SS_Datetime::now()->Rfc2822();
		$survey->write();
		$this->redirect($this->Link() . 'ThankYou');
	}

	function logout()
	{
		Security::logout(true);
	}

	function ThankYou()
	{

		$survey = $this->GetCurrentSurvey();

		if ($survey->BeenEmailed != TRUE && EmailValidator::validEmail($survey->Member()->Email)) {

			//Send email to submitter
			$To = $survey->Member()->Email;
			$Subject = "The OpenStack User Survey: Thank You!";
			$email = EmailFactory::getInstance()->buildEmail(DEPLOYMENT_SURVEY_THANK_U_FROM_EMAIL, $To, $Subject);
			$email->setTemplate('DeploymentSurveyEmail');
			$email->populateTemplate($survey);
			$email->send();

			// Set flag in DB that this user has been emailed
			$survey->BeenEmailed = TRUE;
			$survey->UpdateDate = SS_Datetime::now()->Rfc2822();
			$survey->write();
		}

		return array();

	}

}
