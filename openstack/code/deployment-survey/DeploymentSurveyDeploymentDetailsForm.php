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
class DeploymentSurveyDeploymentDetailsForm extends Form
{

	function __construct($controller, $name)
	{

		// Define fields //////////////////////////////////////

		$CurrentDeploymentID = Session::get('CurrentDeploymentID');

		$fields = new FieldList (
			new HiddenField('DeploymentID', 'DeploymentID', $CurrentDeploymentID),
			new LiteralField('Break', '<p>For each deployment profile, you can decide if you
				wish to allow us to share the basic information on this page. If you
				select private we will treat all of the profile information you enter as confidential
				information.</p>'),

			new OptionSetField(
				'IsPublic',
				'Would you like to keep this information confidential or allow the Foundation to share information about this deployment publicly?',
				array('1' => '<strong>Willing to share:</strong> The information on this page may be shared for this deployment',
					'0' => '<strong>Confidential:</strong> All details provided should be kept confidential to the OpenStack Foundation'),
				1
			),
			new LiteralField('Break', '<hr/>'),
			new LiteralField('Break', ColumnFormatter::$left_column_start),
			new TextField('Label', 'Deployment Name'),
			new LiteralField('Break', '<p>A friendly label like "Production OpenStack Deployment"</p>'),

			new LiteralField('Break', ColumnFormatter::$right_column_start),
			new DropdownField('DeploymentType', 'Deployment Type', Deployment::$deployment_type_options),
			new LiteralField('Break', ColumnFormatter::$end_columns),
			new LiteralField('Break', ColumnFormatter::$left_column_start),
			new CheckboxSetField('ProjectsUsed', 'Projects Used', Deployment::$projects_used_options),
			new LiteralField('Break', ColumnFormatter::$right_column_start),
			new CheckboxSetField('CurrentReleases', 'What releases are you currently using?', Deployment::$current_release_options),
			new DropdownField(
				'DeploymentStage',
				'In what stage is your OpenStack deployment? (make a new deployment profile for each type of deployment)',
				Deployment::$stage_options
			),
			new LiteralField('Break', ColumnFormatter::$end_columns),

			new LiteralField('Break', '<hr/>'),
			new DropdownField('NumCloudUsers',
				'What\'s the size of your cloud by number of users?',
				Deployment::$num_cloud_users_options),
			new CheckboxSetField(
				'WorkloadsDescription',
				'Describe the workloads or applications running in your Openstack environment. (choose any that apply)',
				ArrayUtils::AlphaSort(Deployment::$workloads_description_options, null, array('Other' => 'Other (please specify)'))),
			new TextAreaField(
				'OtherWorkloadsDescription',
				'Other workloads or applications running in your Openstack environment. (optional)')
		);

		$saveButton = new FormAction('SaveDeployment', 'Next Step');
		$nextButton = new CancelFormAction($controller->Link() . 'Deployments', 'Cancel');

		$actions = new FieldList(
			$saveButton, $nextButton
		);

		// Create Validators
		$validator = new RequiredFields('Label',

			'IsPublic',
			'ProjectsUsed',
			'NumCloudUsers',
			'CurrentReleases',
			'DeploymentStage',
			'DeploymentType');

		parent::__construct($controller, $name, $fields, $actions, $validator);


		if ($CurrentDeploymentID) {
			//Populate the form with the current members data
			if ($Deployment = $this->controller->LoadDeployment($CurrentDeploymentID)) {
				$this->loadDataFrom($Deployment->data());
			} else {
				// HTTP ERROR
				return $this->httpError(403, 'Access Denied.');
			}
		}

	}

	function SaveDeployment($data, $form)
	{

		$id = convert::raw2sql($data['DeploymentID']);

		// Only loaded if it belongs to current user
		$Deployment = $form->controller->LoadDeployment($id);

		// If a deployment wasn't returned, we'll create a new one
		if (!$Deployment) {
			$Deployment = new Deployment();
			$Deployment->OrgID = Member::currentUser()->getCurrentOrganization()->ID;
			$newDeploy = true;
		}

		$form->saveInto($Deployment);


		$survey = $form->controller->GetCurrentSurvey();
		$Deployment->DeploymentSurveyID = $survey->ID;
		$Deployment->UpdateDate = SS_Datetime::now()->Rfc2822();
		$Deployment->OrgID = $survey->OrgID;
		$Deployment->write();
		/**/

		$survey->CurrentStep = 'MoreDeploymentDetails';
		$survey->HighestStepAllowed = 'MoreDeploymentDetails';
		$survey->UpdateDate = SS_Datetime::now()->Rfc2822();
		$survey->write();


		// If it is a new deployment and it is public, we send an email...
		if (isset($newDeploy) && $Deployment->IsPublic === 1) {

			global $email_new_deployment;
			global $email_from;

			$email = EmailFactory::getInstance()->buildEmail($email_from,
				$email_new_deployment,
				'New Deployment');

			$email->setTemplate('NewDeploymentEmail');

			$email->populateTemplate(array(
				'Deployment' => $Deployment,
			));

			$email->send();
		}

		Session::set('CurrentDeploymentID', $Deployment->ID);
		Controller::curr()->redirect($form->controller->Link() . 'MoreDeploymentDetails');
	}

	function forTemplate()
	{
		return $this->renderWith(array(
			$this->class,
			'Form'
		));
	}

}
