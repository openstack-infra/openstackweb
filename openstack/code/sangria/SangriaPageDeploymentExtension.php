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
 * Class SangriaPageDeploymentExtension
 */
final class SangriaPageDeploymentExtension extends Extension {

	public function onBeforeInit(){
		Config::inst()->update(get_class($this), 'allowed_actions', array(
			'ViewDeploymentStatistics',
			'ViewDeploymentSurveyStatistics',
			'ViewDeploymentDetails',
			'DeploymentDetails',
			'AddNewDeployment',
			'AddUserStory'));

		Config::inst()->update(get_class($this->owner), 'allowed_actions', array(
			'ViewDeploymentStatistics',
			'ViewDeploymentSurveyStatistics',
			'ViewDeploymentDetails',
			'DeploymentDetails',
			'AddNewDeployment',
			'AddUserStory'));
	}

	function DeploymentDetails(){
		$params        = $this->owner->request->allParams();
		$deployment_id = intval(Convert::raw2sql($params["ID"]));;
		$deployment    = Deployment::get()->byID($deployment_id);
		if($deployment)
			return $this->owner->Customise($deployment)->renderWith(array('SangriaPage_DeploymentDetails','SangriaPage','SangriaPage'));
		return $this->owner->httpError(404, 'Sorry that Deployment could not be found!.');
	}

	// Deployment Survey data

	public function ViewDeploymentSurveyStatistics(){
		SangriaPage_Controller::generateDateFilters();
		Requirements::css("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.css");
		Requirements::javascript("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.js");
		Requirements::css("themes/openstack/css/deployment.survey.page.css");
		Requirements::javascript("themes/openstack/javascript/deployment.survey.filters.js");
		return $this->owner->Customise(array())->renderWith(array('SangriaPage_ViewDeploymentSurveyStatistics','SangriaPage','SangriaPage'));
	}

	function DeploymentSurveysCount() {
		$DeploymentSurveys = DeploymentSurvey::get()->where("Title IS NOT NULL")->where(SangriaPage_Controller::$date_filter_query);
		$Count = ($DeploymentSurveys) ? $DeploymentSurveys->Count() : 0;
		return $Count;
	}

	function IndustrySummary() {
		$list    = new ArrayList();
		$options = DeploymentSurvey::$industry_options;

		foreach( $options as $option => $label ) {
			$count = DB::query("select count(*) from DeploymentSurvey where Industry like '%".$option."%' AND ".SangriaPage_Controller::$date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function OtherIndustry() {
		$list = DeploymentSurvey::get()->where("OtherIndustry IS NOT NULL AND " . SangriaPage_Controller::$date_filter_query)->sort('OtherIndustry');
		return $list;
	}

	function OrganizationSizeSummary() {
		$list = new ArrayList();
		$options = DeploymentSurvey::$organization_size_options;

		foreach( $options as $option => $label ) {
			$count = DB::query("select count(*) from DeploymentSurvey where OrgSize like '%".$option."%' AND ".SangriaPage_Controller::$date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function InvolvementSummary() {
		$list = new ArrayList();
		$options = DeploymentSurvey::$openstack_involvement_options;

		foreach( $options as $option => $label ) {
			$count = DB::query("select count(*) from DeploymentSurvey where OpenStackInvolvement like '%".$option."%' AND ".SangriaPage_Controller::$date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function InformationSourcesSummary() {
		$list = new ArrayList();
		$options = DeploymentSurvey::$information_options;

		foreach( $options as $option => $label ) {
			$count = DB::query("select count(*) from DeploymentSurvey where InformationSources like '%".$option."%' AND ".SangriaPage_Controller::$date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function OtherInformationSources() {
		$list = DeploymentSurvey::get()->where("OtherInformationSources IS NOT NULL AND " . SangriaPage_Controller::$date_filter_query)->sort('OtherInformationSources');
		return $list;
	}

	function FurtherEnhancement() {
		$list = DeploymentSurvey::get()->where("FurtherEnhancement IS NOT NULL AND " . SangriaPage_Controller::$date_filter_query)->sort('FurtherEnhancement');
		return $list;
	}

	function FoundationUserCommitteePriorities() {
		$list = DeploymentSurvey::get()->where("FoundationUserCommitteePriorities IS NOT NULL AND " . SangriaPage_Controller::$date_filter_query)->sort('FurtherEnhancement');
		return $list;
	}

	function BusinessDriversSummary() {
		$list = new ArrayList();
		$options = DeploymentSurvey::$business_drivers_options;

		foreach( $options as $option => $label ) {
			if( $option == 'Ability to innovate, compete') {
				$option = 'Ability to innovate{comma} compete';
			}
			$count = DB::query("select count(*) from DeploymentSurvey where BusinessDrivers like '%".$option."%' AND ".SangriaPage_Controller::$date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function OtherBusinessDrivers() {
		$list = DeploymentSurvey::get()->where("OtherBusinessDrivers IS NOT NULL AND " . SangriaPage_Controller::$date_filter_query)->sort("OtherBusinessDrivers");
		return $list;
	}

	function WhatDoYouLikeMost() {
		$list = DeploymentSurvey::get()->where("WhatDoYouLikeMost IS NOT NULL AND " . SangriaPage_Controller::$date_filter_query)->sort("WhatDoYouLikeMost");
		return $list;
	}

	function NumCloudUsersSummary() {
		return SangriaPage_Controller::generateSelectListSummary("NumCloudUsers",
			Deployment::$num_cloud_users_options);
	}


	// Deployment Survey data

	function ViewDeploymentStatistics(){
		SangriaPage_Controller::generateDateFilters();
		Requirements::css("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.css");
		Requirements::javascript("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.js");
		Requirements::css("themes/openstack/css/deployment.survey.page.css");
		Requirements::javascript("themes/openstack/javascript/deployment.survey.filters.js");
		return $this->owner->Customise(array())->renderWith(array('SangriaPage_ViewDeploymentStatistics','SangriaPage','SangriaPage'));
	}

	function DeploymentsCount() {
		$filterWhereClause = SangriaPage_Controller::generateFilterWhereClause();
		$Deployments = Deployment::get()->where(" 1=1 " . $filterWhereClause.' AND '.SangriaPage_Controller::$date_filter_query);
		return $Deployments->count();
	}

	function IsPublicSummary() {
		$options = array( 0 => "No", 1 => "Yes" );
		return SangriaPage_Controller::generateSelectListSummary("IsPublic", $options, true);
	}

	function DeploymentTypeSummary() {
		return SangriaPage_Controller::generateSelectListSummary("DeploymentType", Deployment::$deployment_type_options, true);
	}

	function ProjectsUsedSummary() {
		return SangriaPage_Controller::generateSelectListSummary("ProjectsUsed", Deployment::$projects_used_options, true);
	}

	function CurrentReleasesSummary() {
		return SangriaPage_Controller::generateSelectListSummary("CurrentReleases", Deployment::$current_release_options, true);
	}

	function APIFormatsSummary() {
		return SangriaPage_Controller::generateSelectListSummary("APIFormats", Deployment::$api_options, true);
	}

	function DeploymentStageSummary() {
		return SangriaPage_Controller::generateSelectListSummary("DeploymentStage", Deployment::$stage_options, true);
	}

	function HypervisorsSummary() {
		return SangriaPage_Controller::generateSelectListSummary("Hypervisors", Deployment::$hypervisors_options, true);
	}

	function IdentityDriversSummary() {
		return SangriaPage_Controller::generateSelectListSummary("IdentityDrivers", Deployment::$identity_driver_options, true);
	}

	function SupportedFeaturesSummary() {
		return SangriaPage_Controller::generateSelectListSummary("SupportedFeatures", Deployment::$deployment_features_options, true);
	}

	function NetworkDriversSummary() {
		return SangriaPage_Controller::generateSelectListSummary("NetworkDrivers", Deployment::$network_driver_options, true);
	}

	function NetworkNumIPsSummary() {
		return SangriaPage_Controller::generateSelectListSummary("NetworkNumIPs", Deployment::$network_ip_options, true);
	}

	function BlockStorageDriversSummary() {
		return SangriaPage_Controller::generateSelectListSummary("BlockStorageDrivers", Deployment::$block_storage_divers_options, true);
	}

	function ComputeNodesSummary() {
		return SangriaPage_Controller::generateSelectListSummary("ComputeNodes", Deployment::$compute_nodes_options, true);
	}

	function ComputeCoresSummary() {
		return SangriaPage_Controller::generateSelectListSummary("ComputeCores", Deployment::$compute_cores_options, true);
	}

	function ComputeInstancesSummary() {
		return SangriaPage_Controller::generateSelectListSummary("ComputeInstances", Deployment::$compute_instances_options, true);
	}

	function BlockStorageTotalSizeSummary() {
		return SangriaPage_Controller::generateSelectListSummary("BlockStorageTotalSize", Deployment::$storage_size_options, true);
	}

	function ObjectStorageSizeSummary() {
		return SangriaPage_Controller::generateSelectListSummary("ObjectStorageSize", Deployment::$storage_size_options, true);
	}

	function ObjectStorageNumObjectsSummary() {
		return SangriaPage_Controller::generateSelectListSummary("ObjectStorageNumObjects", Deployment::$stoage_objects_options, true);
	}

	// Deployment Details

	function ViewDeploymentDetails(){
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::css(THIRDPARTY_DIR . '/jquery-ui-themes/smoothness/jquery-ui.css');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-ui/jquery-ui.js');
		Requirements::javascript("themes/openstack/javascript/jquery.ui.datepicker.validation.package-1.0.1/jquery.ui.datepicker.validation.js");
		Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");
		Requirements::javascript("themes/openstack/javascript/sangria/view.deployment.details.js");
		return $this->owner->getViewer('ViewDeploymentDetails')->process($this->owner);
	}

	function Deployments(){
		$sort = $this->request->getVar('sort');
		$sort_dir = $this->getSortDir('deployments');
		$date_from = Convert::raw2sql(trim($this->request->getVar('date-from')));
		$date_to = Convert::raw2sql(trim($this->request->getVar('date-to')));
		$sort_query = '';
		if (!empty($sort)) {
			switch (strtolower(trim($sort))) {
				case 'date': {
					$sort_query = "UpdateDate";
					$sort_dir = strtoupper($sort_dir);
				}
					break;
				default: {
				$sort_query = "ID";
				$sort_dir = 'DESC';
				}
				break;
			}
		}

		$where_query = "IsPublic = 1";

		if (!empty($date_from) && !empty($date_to)) {
			$start = new \DateTime($date_from);
			$start->setTime(00, 00, 00);
			$end = new \DateTime($date_to);
			$end->setTime(23, 59, 59);
			$where_query .= " AND ( UpdateDate >= '{$start->format('Y-m-d H:i:s')}' AND UpdateDate <= '{$end->format('Y-m-d H:i:s')}')";
		}

		$res = Deployment::get()->where($where_query);
		if (!empty($sort_query) && !empty($sort_dir)) {
			$res->sort($sort_query, $sort_dir);
		}
		return $res;
	}


	function DeploymentsSurvey(){

		$sqlQuery = new SQLQuery();
		$sqlQuery->select = array('DeploymentSurvey.*');
		$sqlQuery->from = array("DeploymentSurvey, Deployment, Org");
		$sqlQuery->where = array("Deployment.DeploymentSurveyID = DeploymentSurvey.ID
                                AND Deployment.IsPublic = 1
                                AND Org.ID = DeploymentSurvey.OrgID
                                AND DeploymentSurvey.Title IS NOT NULL
                                ");
		$sqlQuery->orderby = 'Org.Name';

		$result = $sqlQuery->execute();

		$arrayList = new ArrayList();

		foreach ($result as $rowArray) {
			// concept: new Product($rowArray)
			$arrayList->push(new $rowArray['ClassName']($rowArray));
		}

		return $arrayList;
	}

	// Add User Story from Deployment
	function AddUserStory() {

		if (isset($_GET['ID']) && is_numeric($_GET['ID'])) {
			$ID = $_GET['ID'];
		} else {
			die();
		}

		$parent = UserStoryHolder::get()->first();
		if (!$parent) {
			$this->setMessage('Error', 'could not add an user story bc there is not any available parent page(UserStoryHolder).');
			$this->redirectBack();
		}
		$userStory = new UserStory;
		$userStory->Title = $_GET['label'];
		$userStory->DeploymentID = $ID;
		$userStory->UserStoriesIndustryID = $_GET['industry'];
		$userStory->CompanyName = $_GET['org'];
		$userStory->CaseStudyTitle = $_GET['org'];
		$userStory->ShowInAdmin = 1;
		$userStory->setParent($parent); // Should set the ID once the Holder is created...
		$userStory->write();
		$userStory->publish("Live", "Stage");

		$this->setMessage('Success', '<b>' . $userStory->Title . '</b> added as User Story.');

		$this->redirectBack();
	}

	function AddNewDeployment(){

		$survey = DataObject::get_one('DeploymentSurvey','ID = ' . $_POST['survey'] );

		$deployment = new Deployment;
		$deployment->Label = $_POST['label'];
		$deployment->DeploymentType = $_POST['type'];
		$deployment->CountryCode = $_POST['country'];
		$deployment->DeploymentSurveyID = $_POST['survey'];
		if($survey){
			$deployment->OrgID = $survey->OrgID;
		}else{
			$deployment->OrgID = 0;
		}
		$deployment->IsPublic = 1;
		$deployment->write();

		$this->owner->setMessage('Success', '<b>' . $_POST['label'] . '</b> added as a new Deployment.');

		Controller::curr()->redirectBack();
	}

	function WorkloadsSummary() {
		return SangriaPage_Controller::generateSelectListSummary("WorkloadsDescription",
			Deployment::$workloads_description_options);
	}

	function DeploymentToolsSummary() {
		return SangriaPage_Controller::generateSelectListSummary("DeploymentTools",
			Deployment::$deployment_tools_options);
	}

	function OperatingSystemSummary() {
		return SangriaPage_Controller::generateSelectListSummary("OperatingSystems",
			Deployment::$operating_systems_options);
	}

	function WhyNovaNetwork() {
		$filterWhereClause = SangriaPage_Controller::generateFilterWhereClause();

		$list = DataObject::get("Deployment","WhyNovaNetwork IS NOT NULL".$filterWhereClause,"WhyNovaNetwork");

		return $list;
	}
}