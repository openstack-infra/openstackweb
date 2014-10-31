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
$include_path = Director::baseFolder() . '/html2pdf_v4.03/html2pdf.class.php';
require_once($include_path);

class UserStory extends Page
{

	static $db = array(
		'CaseStudyTitle' => 'Text',
		'CaseStudyBody' => 'HTMLText',
		'Video' => 'Text',
		'ShowCaseStudy' => 'Boolean',
		'ShowVideo' => 'Boolean',
		'CompanyName' => 'Text',
		'CompanyURL' => 'Text',
		'ShowInAdmin' => 'Boolean',
		'ObjectivesTitle' => 'Text',
		'ObjectivesBody' => 'HTMLText',
		// Custom Fields if the User Story doesn't belong to any Deployment Survey
		'CompanyHeadquarters' => 'Text',
		'CompanySize' => 'Text',
		'CompanyIndustry' => 'Text',
		'ProjectsUsed' => 'Text',
		'DeploymentType' => 'Text',
		'PrimaryCountry' => 'Text',
		'ThirdPartyURL' => 'Text',
	);

	static $has_one = array(
		'Deployment' => 'Deployment',
		'UserStoriesIndustry' => 'UserStoriesIndustry',
		'SummaryImg' => 'Image',
		'CaseStudyImg' => 'Image'
	);

	static $has_many = array(
		'UserStoriesLink' => 'UserStoriesLink'
	);

	static $singular_name = 'User Story';
	static $plural_name = 'User Stories';

	function getCMSFields()
	{

		// Get arrays to related dropdowns

		$deployments = Deployment::get()->filter('IsPublic',1);
		if ($deployments) {
			$deployments = $deployments->map('ID', 'OrgAndLabel', '(Select one)', true);
		}

		$industries = UserStoriesIndustry::get()->filter('Active',1);
		if ($industries) {
			$industries = $industries->map('ID', 'IndustryName', '(Select one)', true);
		}

		$fields = parent::getCMSFields();
		$CountryCodes = CountryCodes::$iso_3166_countryCodes;
		$fields->addFieldsToTab('Root.Main',
			array(
				new CustomUploadField('SummaryImg', 'Summary Image'),
				new DropdownField('DeploymentID', 'Deployment', $deployments),
				new DropdownField('UserStoriesIndustryID', 'Industry', $industries),
				new DropdownField(
					'DeploymentType',
					'Deployment Type',
					Deployment::$deployment_type_options
				),
				new DropdownField(
					'PrimaryCountry',
					'Country',
					$CountryCodes
				),
			)
		);

		$fields->addFieldsToTab('Root.CaseStudy',

			array(
				new CheckboxField('ShowCaseStudy', 'Show Case Study?'),
				new TextField('CaseStudyTitle', 'Case Study Title'),
				new TextField('CompanyName', 'Company Name'),
				new TextField('CompanyURL', 'Company URL'),
				new TextField('ThirdPartyURL', '3rd Party Site URL'),
				new HtmlEditorField('CaseStudyBody', 'Case Study Body'),
				new CustomUploadField('CaseStudyImg', 'Case Study Image'),
				new TextField('ObjectivesTitle', 'Objectives Title'),
				new HtmlEditorField('ObjectivesBody', 'Objectives Body'),
				new TextField('CompanyHeadquarters', 'Company Headquarters'),
				new TextField('CompanySize', 'Company Size'),
				new TextField('CompanyIndustry', 'Company Industry'),
				new CheckboxSetField(
					'ProjectsUsed',
					'ProjectsUsed',
					array(
						'Openstack Compute (Nova)' => 'Openstack Compute (Nova)',
						'Openstack Block Storage (Cinder)' => 'Openstack Block Storage (Cinder)',
						'Openstack Object Storage (Swift)' => 'Openstack Object Storage (Swift)',
						'Openstack Network' => 'Openstack Network (Neutron)',
						'Openstack Dashboard (Horizon)' => 'Openstack Dashboard (Horizon)',
						'Openstack Identity Service (Keystone)' => 'Openstack Identity Service (Keystone)',
						'Openstack Image Service (Glance)' => 'Openstack Image Service (Glance)',
						'Heat' => 'OpenStack Orchestration (Heat)',
						'Ceilometer' => 'OpenStack Metering (Ceilometer)',
						'OpenStack Bare Metal (Ironic)' => 'OpenStack Bare Metal (Ironic)',
						'OpenStack Database as a Service (Trove)' => 'OpenStack Database as a Service (Trove)'
					)
				)
			)
		);

		$fields->addFieldsToTab('Root.Video',
			array(
				new CheckboxField('ShowVideo', 'Show Video?'),
				new TextField('Video', 'YouTube URL')
			)
		);

		// Reuse fields from Page Model, and change their label to match Specs
		$fields->renameField('Title', 'Deployment Display Name');
		$fields->renameField('Content', 'Summary');

		return $fields;
	}

	public function SummaryImg220()
	{
		return $this->SummaryImg()->SetWidth(220);
	}


	public function Projects(){
		$projects_used = explode(',' , $this->getProjects() );
		$list = new ArrayList();
		foreach($projects_used as $project){
			$do = new DataObject(); 
			$do->Project = $project;
			$list->push($do);
		}
		return $list;
	}

	public function getProjects()
	{
		if ($this->Deployment()->ProjectsUsed)
			return $this->Deployment()->ProjectsUsed;
		else
			return $this->ProjectsUsed;
	}

	public function DeploymentType()
	{
		if ($this->Deployment() && !empty($this->Deployment()->DeploymentType))
			return $this->Deployment()->DeploymentType;
		else
			return $this->DeploymentType;
	}

	public function Country()
	{
		if ($this->Deployment() && $this->Deployment()->DeploymentSurvey() && $this->Deployment()->DeploymentSurvey()->PrimaryCountry)
			return $this->Deployment()->DeploymentSurvey()->PrimaryCountry;
		else
			return $this->PrimaryCountry;
	}

	public function YouTubeID()
	{
		if (preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $this->Video, $matches)) {
			return $matches[0];
		} else {
			return $this->Video;
		}
	}

	public function getHeadquarters()
	{
		if ($this->Deployment()->DeploymentSurvey()->PrimaryCity)
			return $this->Deployment()->DeploymentSurvey()->PrimaryCity;
		else
			return $this->CompanyHeadquarters;
	}

	public function getIndustry()
	{
		if ($this->Deployment()->DeploymentSurvey()->Industry)
			return $this->Deployment()->DeploymentSurvey()->Industry;
		else
			return $this->CompanyIndustry;
	}

	public function getSize()
	{
		if ($this->Deployment()->DeploymentSurvey()->OrgSize)
			return $this->Deployment()->DeploymentSurvey()->OrgSize;
		else
			return $this->CompanySize;
	}


	public function Headquarters()
	{
		if ($this->Deployment() && $this->Deployment()->DeploymentSurvey())
			return $this->Deployment()->DeploymentSurvey()->PrimaryCity;
		else
			return $this->CompanyHeadquarters;
	}

	public function OrgSize()
	{
		if ($this->Deployment() && $this->Deployment()->DeploymentSurvey())
			return $this->Deployment()->DeploymentSurvey()->OrgSize;
		else
			return $this->CompanySize;
	}

	public function Industry()
	{
		if ($this->Deployment() && $this->Deployment()->DeploymentSurvey())
			return $this->Deployment()->DeploymentSurvey()->Industry;
		else
			return $this->CompanyIndustry;
	}

	/**
	 * @param null $action
	 * @return string
	 */
	public function Link($action = null){
		if(!empty($this->ThirdPartyURL))
			return $this->ThirdPartyURL;
		return parent::Link($action);
	}
}

class UserStory_Controller extends Page_Controller
{

	static $allowed_actions = array(
		'pdf'
	);

	public function pdf($request)
	{
		$file = FileUtils::convertToFileName($this->Title) . '.pdf';
		$html_inner = $this->customise(array('BASEURL' => Director::protocolAndHost()))->renderWith("UserStoryPDF");
		$base = Director::baseFolder();
		$css = $base . "/themes/openstack/css/main.pdf.css";
		$html_outer = sprintf("<html><head><style>%s</style></head><body><div class='container'>%s</div></body></html>",
			str_replace("@host", $base, @file_get_contents($css)),
			str_replace('"/assets/', '"' . Director::protocolAndHost() . '/assets/', $html_inner));

		//for debug purposes
		if (isset($_GET['view'])) {
			echo $html_outer;
			die();
		}

		try {
			$html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(15, 5, 15, 5));
			$html2pdf->WriteHTML($html_outer);
			//clean output buffer
			ob_end_clean();
			$html2pdf->Output($file, "D");
		} catch (HTML2PDF_exception $e) {
			$message = array(
				'errno' => '',
				'errstr' => $e->__toString(),
				'errfile' => 'UserStory.php',
				'errline' => '',
				'errcontext' => ''
			);
			SS_Log::log($message, SS_Log::ERR);
			$this->httpError(404,'There was an error on PDF generation!');
		}
	}

}