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
 * Defines Sangria Admin area
 */
class SangriaPage extends Page
{
	static $db = array();

	static $has_one = array();
}

class SangriaPage_Controller extends Page_Controller
{


	var $submissionsCount = 0;
	var $orgs_cached = array();
	var $default_start_date;
	var $default_end_date;
	var $date_filter_query;

	static $allowed_actions = array(
		'AddInvolvementType',
		'AddInvolvementTypeForm',
		'ViewSpeakingSubmissions',
		'StandardizeOrgNames',
		'MarkOrgStandardized',
		'RemoveDuplicateOrg',
		'ViewDeploymentStatistics',
		'GenerateAutoLoginHashes',
		// Manage User Stories and Deployments
		'ViewDeploymentDetails',
		'ViewDeploymentSurveyStatistics',
		'ViewCurrentStories',
		'SetCaseStudy',
		'SetAdminSS',
		'AddUserStory',
		'UpdateStories',
		'AddNewDeployment',
		'ExportSurveyResults',
		'ExportAppDevSurveyResults',
		'DeploymentDetails',
		'SurveyDetails',
		'exportFoundationMembers',
		'exportCorporateSponsors',
		'ExportData',
		'exportCLAUsers',
		'exportGerritUsers',
		'DeploymentSurveyDeploymentsFilters',
		'filterResults',
	);

	function init()
	{
		if (!Permission::check("SANGRIA_ACCESS")) Security::permissionFailure();
		parent::init();
		Requirements::css('themes/openstack/javascript/jquery-ui-1.10.3.custom/css/smoothness/jquery-ui-1.10.3.custom.min.css');
		Requirements::javascript('themes/openstack/javascript/jquery.tablednd.js');
		Requirements::javascript('themes/openstack/javascript/querystring.jquery.js');
		Requirements::javascript('themes/openstack/javascript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js');
		Requirements::css("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.css");
		Requirements::javascript("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.js");
		Requirements::css("themes/openstack/css/deployment.survey.page.css");
		Requirements::javascript("themes/openstack/javascript/deployment.survey.filters.js");
		$this->default_start_date = date('Y/m/d', strtotime('-12 months')) . ' 00:00';
		$this->default_end_date = date('Y/m/d') . ' 23:59';
	}

	function providePermissions()
	{
		return array(
			"SANGRIA_ACCESS" => "Access the Sangria Admin"
		);
	}

	// Deployment Survey Filters


	function DeploymentSurveyDeploymentsFilters($action)
	{
		Requirements::css("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.css");
		Requirements::javascript("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.js");
		Requirements::css("themes/openstack/css/deployment.survey.page.css");
		Requirements::javascript("themes/openstack/javascript/deployment.survey.filters.js");
		$data = Session::get("FormInfo.Form_DeploymentSurveyDeploymentsFilters.data");
		$params = $this->requestParams;
		$start_date = (isset($params['date-from'])) ? $params['date-from'] : $this->default_start_date;
		$end_date = (isset($params['date-to'])) ? $params['date-to'] : $this->default_end_date;
		$form = new DeploymentSurveyDeploymentsFilters($this, 'DeploymentSurveyDeploymentsFilters', $action, $start_date, $end_date);
		// we should also load the data stored in the session. if failed
		if (is_array($data)) {
			$form->loadDataFrom($data);
		}
	}

	// DASHBOARD METRICS


	function IndividualMemberCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('FoundationMember', function () {
			$query = new IndividualFoundationMemberCountQuery();
			$res = $query->handle(null)->getResult();
			return $res[0];
		});
	}

	function CommunityMemberCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('CommunityMember', function () {
			$query = new IndividualCommunityMemberCountQuery();
			$res = $query->handle(null)->getResult();
			return $res[0];
		});
	}

	function NewsletterMemberCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('NewsletterMember', function () {
			$query = new FoundationMembersSubscribedToNewsLetterCountQuery();
			$res = $query->handle(new FoundationMembersSubscribedToNewsLetterCountQuerySpecification)->getResult();
			return $res[0];
		});
	}

	function NewsletterPercentage()
	{
		return number_format(($this->NewsletterMemberCount() / $this->IndividualMemberCount()) * 100, 2);
	}

	function UserStoryCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('UserStory', function () {
			$query = new UserStoriesCountQuery();
			$res = $query->handle(new UserStoriesCountQuerySpecification(true))->getResult();
			return $res[0];
		});
	}

	function UserLogoCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('UserLogo', function () {
			$query = new UserStoriesCountQuery();
			$res = $query->handle(new UserStoriesCountQuerySpecification(false))->getResult();
			return $res[0];
		});
	}

	function PlatinumMemberCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('PlatinumOrg', function () {
			$query = new CompanyCountQuery();
			$res = $query->handle(new CompanyCountQuerySpecification('Platinum'))->getResult();
			return $res[0];
		});
	}

	function GoldMemberCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('GoldOrg', function () {
			$query = new CompanyCountQuery();
			$res = $query->handle(new CompanyCountQuerySpecification('Gold'))->getResult();
			return $res[0];
		});
	}

	function CorporateSponsorCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('CorporateOrg', function () {
			$query = new CompanyCountQuery();
			$res = $query->handle(new CompanyCountQuerySpecification('Corporate'))->getResult();
			return $res[0];
		});
	}

	function StartupSponsorCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('StartupOrg', function () {
			$query = new CompanyCountQuery();
			$res = $query->handle(new CompanyCountQuerySpecification('Startup'))->getResult();
			return $res[0];
		});
	}

	function SupportingOrganizationCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('MentionOrg', function () {
			$query = new CompanyCountQuery();
			$res = $query->handle(new CompanyCountQuerySpecification('Mention'))->getResult();
			return $res[0];
		});
	}

	function TotalOrganizationCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('TotalOrgs', function () {
			$query = new CompanyCountQuery();
			$res = $query->handle(new CompanyCountQuerySpecification())->getResult();
			return $res[0];
		});
	}

	function NewsletterInternationalCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('NewsletterInternationalCount', function () {
			$query = new FoundationMembersSubscribedToNewsLetterCountQuery();
			$res = $query->handle(new FoundationMembersSubscribedToNewsLetterCountQuerySpecification('US'))->getResult();
			return $res[0];
		});
	}

	function NewsletterInternationalPercentage()
	{
		return number_format(($this->NewsletterInternationalCount() / $this->NewsletterMemberCount()) * 100, 2);
	}

	function IndividualMemberCountryCount()
	{
		$Count = DB::query('select count(distinct(Member.Country)) from Member left join Group_Members on Member.ID = Group_Members.MemberID where Group_Members.GroupID = 5;')->value();
		return $Count;
	}

	function InternationalOrganizationCount()
	{
		return EntityCounterHelper::getInstance()->EntityCount('InternationalOrganization', function () {
			$query = new CompanyCountQuery();
			$res = $query->handle(new CompanyCountQuerySpecification(null, 'US'))->getResult();
			return $res[0];
		});
	}

	function OrgsInternationalPercentage()
	{
		return number_format(($this->InternationalOrganizationCount() / $this->TotalOrganizationCount()) * 100, 2);
	}


	// Involvement Types
	function InvolvementTypes()
	{
		return InvolvementType::get();
	}

	function AddInvolvementTypeForm()
	{
		return new AddInvolvementTypeForm($this, 'AddInvolvementTypeForm');
	}

	function GenerateAutoLoginHashes()
	{
		$startVal = 0;

		if (isset($_GET["startID"]) && intval($_GET["startID"]) > 0) {
			$startVal = intval($_GET["startID"]);
		}

		$members = Member::get()->filter(array('SubscribedToNewsletter' => 1, 'ID:GreaterThan' => $startVal))->order('ID')->leftJoin('Group_Members', "`Member`.`ID` = `Group_Members`.`MemberID` AND Group_Members.GroupID = 5 ");
		foreach ($members as $member) {
			$token = $member->generateAutologinTokenAndStoreHash(14);
			echo "\"" . $member->ID . "\",\"" . $member->Email . "\",\"" . $member->FirstName . "\",\"" . $member->Surname . "\",\"" . urldecode($token) . "\"<br/>";
			flush();
		}
	}

	// Speaking Submissions

	function SpeakingSubmissionCount()
	{
		$this->SpeakingSubmissions();
		return $this->submissionsCount;
	}

	function SpeakingSubmissions()
	{
		$submissions = SpeakerSubmission::get()->filter('Created:GreaterThan', '2012-11-01')->sort('Created');
		$this->submissionsCount = $submissions->Count();
		return $submissions;
	}

	// Org Standardization

	function Orgs()
	{
		$orgs = Org::get()->sort("Name");
		return $orgs;
	}

	function NonStandardizedOrgs()
	{
		$orgs = Org::get()->filter(array('IsStandardizedOrg' => 0))->sort('Name')->limit(150);
		return $orgs;
	}


	function StandardizedOrgs()
	{
		global $orgs_cached;

		if (count($orgs_cached) > 0) {
			return $orgs_cached;
		} else {
			$orgs = Org::get()->filter('IsStandardizedOrg', 1)->sort('Name');
			$orgs_cached = $orgs;
			return $orgs;
		}
	}

	function MarkOrgStandardized()
	{
		if (isset($_GET['orgId']) && is_numeric($_GET['orgId'])) {
			$orgId = $_GET['orgId'];
		}

		$org = Org::get()->byID($orgId);

		$org->IsStandardizedOrg = 1;
		$org->write();
		$this->redirectBack();
	}

	function RemoveDuplicateOrg()
	{
		if (isset($_POST['oldOrgIds']) && is_array($_POST['oldOrgIds'])) {
			$oldOrgIds = $_POST['oldOrgIds'];
		}

		foreach ($oldOrgIds as $oldId => $newId) {
			if ($newId == "STANDARDIZE") {
				$org = Org::get()->byID($oldId);

				$org->IsStandardizedOrg = 1;
				$org->write();
			} else if ($newId != 0) {
				// Update all members with new Org
				DB::query("UPDATE `Affiliation` SET `OrganizationID` = " . $newId . " WHERE `OrganizationID` = " . $oldId);

				// Remove old Org
				DB::query("DELETE FROM `Org` WHERE `ID` = " . $oldId);
			}
		}

		$this->redirectBack();
	}

	// Deployment Survey data
	function DeploymentSurveysCount()
	{
		$DeploymentSurveys = DeploymentSurvey::get()->where("Title IS NOT NULL")->where($this->date_filter_query);
		$Count = $DeploymentSurveys->Count();
		return $Count;
	}

	function IndustrySummary()
	{
		$list = new ArrayList();
		$deploymentSurvey = new DeploymentSurvey();
		$options = DeploymentSurvey::$industry_options;

		foreach ($options as $option => $label) {
			$count = DB::query("select count(*) from DeploymentSurvey where Industry like '%" . $option . "%' AND " . $this->date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function OtherIndustry()
	{
		$list = DeploymentSurvey::get()->where("OtherIndustry IS NOT NULL AND " . $this->date_filter_query)->sort('OtherIndustry');
		return $list;
	}

	function OrganizationSizeSummary()
	{
		$list = new ArrayList();
		$deploymentSurvey = new DeploymentSurvey();
		$options = DeploymentSurvey::$organization_size_options;

		foreach ($options as $option => $label) {
			$count = DB::query("select count(*) from DeploymentSurvey where OrgSize like '%" . $option . "%' AND " . $this->date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function InvolvementSummary()
	{
		$list = new ArrayList();
		$deploymentSurvey = new DeploymentSurvey();
		$options = DeploymentSurvey::$openstack_involvement_options;

		foreach ($options as $option => $label) {
			$count = DB::query("select count(*) from DeploymentSurvey where OpenStackInvolvement like '%" . $option . "%' AND " . $this->date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function InformationSourcesSummary()
	{
		$list = new ArrayList();
		$deploymentSurvey = new DeploymentSurvey();
		$options = DeploymentSurvey::$information_options;

		foreach ($options as $option => $label) {
			$count = DB::query("select count(*) from DeploymentSurvey where InformationSources like '%" . $option . "%' AND " . $this->date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function OtherInformationSources()
	{
		$list = DeploymentSurvey::get()->where("OtherInformationSources IS NOT NULL AND " . $this->date_filter_query)->sort('OtherInformationSources');
		return $list;
	}

	function FurtherEnhancement()
	{
		$list = DeploymentSurvey::get()->where("FurtherEnhancement IS NOT NULL AND " . $this->date_filter_query)->sort('FurtherEnhancement');
		return $list;
	}

	function FoundationUserCommitteePriorities()
	{
		$list = DeploymentSurvey::get()->where("FoundationUserCommitteePriorities IS NOT NULL AND " . $this->date_filter_query)->sort('FurtherEnhancement');
		return $list;
	}

	function BusinessDriversSummary()
	{
		$list = new ArrayList();
		$deploymentSurvey = new DeploymentSurvey();
		$options = DeploymentSurvey::$business_drivers_options;

		foreach ($options as $option => $label) {
			if ($option == 'Ability to innovate, compete') {
				$option = 'Ability to innovate{comma} compete';
			}
			$count = DB::query("select count(*) from DeploymentSurvey where BusinessDrivers like '%" . $option . "%' AND " . $this->date_filter_query)->value();
			$do = new DataObject();
			$do->Value = $label;
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	function OtherBusinessDrivers()
	{
		$list = DeploymentSurvey::get()->where("OtherBusinessDrivers IS NOT NULL AND " . $this->date_filter_query)->sort("OtherBusinessDrivers");
		return $list;
	}

	function WhatDoYouLikeMost()
	{
		$list = DeploymentSurvey::get()->where("WhatDoYouLikeMost IS NOT NULL AND " . $this->date_filter_query)->sort("WhatDoYouLikeMost");
		return $list;
	}


	// Deployment data
	function DeploymentsCount()
	{
		$filterWhereClause = $this->generateFilterWhereClause();
		$Deployments = Deployment::get()->where(" 1=1 " . $filterWhereClause.' AND '.$this->date_filter_query);
		$Count = $Deployments->count();
		return $Count;
	}



	function IsPublicSummary()
	{
		$options = array(0 => "No", 1 => "Yes");
		return $this->generateSelectListSummary("IsPublic",	$options, true);
	}


	function DeploymentTypeSummary()
	{
		return $this->generateSelectListSummary("DeploymentType", Deployment::$deployment_type_options, true);
	}

	function ProjectsUsedSummary()
	{
		return $this->generateSelectListSummary("ProjectsUsed",	Deployment::$projects_used_options, true);
	}

	function CurrentReleasesSummary()
	{
		return $this->generateSelectListSummary("CurrentReleases",	Deployment::$current_release_options, true);
	}

	function DeploymentStageSummary()
	{
		return $this->generateSelectListSummary("DeploymentStage", Deployment::$stage_options, true);
	}

	function APIFormatsSummary()
	{
		return $this->generateSelectListSummary("APIFormats",	Deployment::$api_options, true);
	}

	function HypervisorsSummary()
	{
		return $this->generateSelectListSummary("Hypervisors",Deployment::$hypervisors_options, true);
	}

	function BlockStorageDriversSummary()
	{
		return $this->generateSelectListSummary("BlockStorageDrivers",
			Deployment::$block_storage_divers_options,true);
	}

	function NetworkDriversSummary()
	{
		return $this->generateSelectListSummary("NetworkDrivers",
			Deployment::$network_driver_options, true);
	}

	function IdentityDriversSummary()
	{
		return $this->generateSelectListSummary("IdentityDrivers",
			Deployment::$identity_driver_options,true);
	}

	function SupportedFeaturesSummary()
	{
		return $this->generateSelectListSummary("SupportedFeatures",
			Deployment::$deployment_features_options,true);
	}

	function ComputeNodesSummary()
	{
		return $this->generateSelectListSummary("ComputeNodes",
			Deployment::$compute_nodes_options,true);
	}

	function ComputeCoresSummary()
	{
		return $this->generateSelectListSummary("ComputeCores",
			Deployment::$compute_cores_options,true);
	}

	function ComputeInstancesSummary()
	{
		return $this->generateSelectListSummary("ComputeInstances",
			Deployment::$compute_instances_options,true);
	}

	function BlockStorageTotalSizeSummary()
	{
		return $this->generateSelectListSummary("BlockStorageTotalSize",
			Deployment::$storage_size_options,true);
	}

	function ObjectStorageSizeSummary()
	{
		return $this->generateSelectListSummary("ObjectStorageSize",
			Deployment::$storage_size_options,true);
	}

	function ObjectStorageNumObjectsSummary()
	{
		return $this->generateSelectListSummary("ObjectStorageNumObjects",
			Deployment::$stoage_objects_options,true);
	}

	function NetworkNumIPsSummary()
	{
		return $this->generateSelectListSummary("NetworkNumIPs",
			Deployment::$network_ip_options,true);
	}

	function NumCloudUsersSummary()
	{
		return $this->generateSelectListSummary("NumCloudUsers",
			Deployment::$num_cloud_users_options);
	}

	function WorkloadsSummary()
	{
		return $this->generateSelectListSummary("WorkloadsDescription",
			Deployment::$workloads_description_options);
	}

	function DeploymentToolsSummary()
	{
		return $this->generateSelectListSummary("DeploymentTools",
			Deployment::$deployment_tools_options);
	}

	function OperatingSystemSummary()
	{
		return $this->generateSelectListSummary("OperatingSystems",
			Deployment::$operating_systems_options);
	}

	function WhyNovaNetwork()
	{
		$filterWhereClause = $this->generateFilterWhereClause();

		$list = Deployment::get()->where("WhyNovaNetwork IS NOT NULL" . $filterWhereClause)->sort('WhyNovaNetwork');

		return $list;
	}

	function DeploymentMatchingOrgs()
	{
		$filterWhereClause = $this->generateFilterWhereClause();

		$results = DB::query("select o.Name from Deployment d join DeploymentSurvey s on (d.DeploymentSurveyID = s.ID) join Org o on (s.OrgID = o.ID) where 1=1" . $filterWhereClause);
		$list = new ArrayList();

		for ($i = 0; $i < $results->numRecords(); $i++) {
			$record = $results->nextRecord();
			$do = new DataObject();
			$do->OrgName = $record["Name"];
			$list->push($do);
		}
		return $list;
	}


	// Export CSV of all Deployment Surveys and Associated Deployments
	function ExportSurveyResults() {
		$fileDate = date( 'Ymdhis' );

		$this->generateDateFilters('s');

		$surveyQuery = "select s.ID as SurveyID, s.Created as SurveyCreated,
                s.UpdateDate as SurveyEdited, o.Name as OrgName, o.ID as OrgID , d.ID as DeploymentID,
                d.Created as DeploymentCreated, d.UpdateDate as DeploymentEdited, m.FirstName,
                m.Surname, m.Email, s.Title, s.Industry, s.OtherIndustry, s.PrimaryCity,
                s.PrimaryState, s.PrimaryCountry, s.OrgSize, s.OpenStackInvolvement,
                s.InformationSources, s.OtherInformationSources, s.FurtherEnhancement,
                s.FoundationUserCommitteePriorities, s.UserGroupMember, s.UserGroupName,
                s.OkToContact, s.BusinessDrivers, s.OtherBusinessDrivers, s.WhatDoYouLikeMost,
                s.OpenStackRecommendRate as NetPromoter, s.OpenStackRecommendation,
                d.Label, d.IsPublic, d.DeploymentType, d.ProjectsUsed, d.CurrentReleases,
                d.DeploymentStage, d.NumCloudUsers, d.APIFormats, d.Hypervisors, d.OtherHypervisor,
                d.BlockStorageDrivers, d.OtherBlockStorageDriver, d.NetworkDrivers,
                d.OtherNetworkDriver, d.IdentityDrivers, d.OtherIndentityDriver,
                d.SupportedFeatures, d.ComputeNodes, d.ComputeCores, d.ComputeInstances,
                d.BlockStorageTotalSize, d.ObjectStorageSize, d.ObjectStorageNumObjects,
                d.NetworkNumIPs, d.WorkloadsDescription, d.OtherWorkloadsDescription,
                d.WhyNovaNetwork, d.OtherWhyNovaNetwork, d.DeploymentTools, d.OtherDeploymentTools, d.OperatingSystems,
                d.OtherOperatingSystems, d.SwiftGlobalDistributionFeatures, d.SwiftGlobalDistributionFeaturesUsesCases,
                d.OtherSwiftGlobalDistributionFeaturesUsesCases, d.Plans2UseSwiftStoragePolicies,
                d.OtherPlans2UseSwiftStoragePolicies, d.UsedDBForOpenStackComponents,
                d.OtherUsedDBForOpenStackComponents, d.ToolsUsedForYourUsers, d.OtherToolsUsedForYourUsers,
                d.Reason2Move2Ceilometer
            from DeploymentSurvey s
                left outer join Member m on (s.MemberID = m.ID)
                left outer join Deployment d on (d.DeploymentSurveyID = s.ID)
                left outer join Org o on (s.OrgID = o.ID)
            where s.Title is not null AND ".$this->date_filter_query."
            order by s.ID;";

		$res = DB::query($surveyQuery);

		$fields = array('SurveyID','SurveyCreated','SurveyEdited','OrgName','OrgID','DeploymentID','DeploymentCreated', 'DeploymentEdited', 'FirstName',
			'Surname','Email','Title','Industry','OtherIndustry','PrimaryCity','PrimaryState','PrimaryCountry','OrgSize','OpenStackInvolvement','InformationSources',
			'OtherInformationSources','FurtherEnhancement','FoundationUserCommitteePriorities','UserGroupMember','UserGroupName','OkToContact','BusinessDrivers',
			'OtherBusinessDrivers','WhatDoYouLikeMost','NetPromoter','OpenStackRecommendation','Label','IsPublic','DeploymentType','ProjectsUsed',
			'CurrentReleases','DeploymentStage','NumCloudUsers','APIFormats','Hypervisors','OtherHypervisor','BlockStorageDrivers','OtherBlockStorageDriver',
			'NetworkDrivers','OtherNetworkDriver','IdentityDrivers','OtherIndentityDriver','SupportedFeatures','ComputeNodes','ComputeCores','ComputeInstances',
			'BlockStorageTotalSize','ObjectStorageSize','ObjectStorageNumObjects','NetworkNumIPs','WorkloadsDescription','OtherWorkloadsDescription',
			'WhyNovaNetwork','OtherWhyNovaNetwork','DeploymentTools','OtherDeploymentTools','OperatingSystems','OtherOperatingSystems','SwiftGlobalDistributionFeatures',
			'SwiftGlobalDistributionFeaturesUsesCases','OtherSwiftGlobalDistributionFeaturesUsesCases','Plans2UseSwiftStoragePolicies','OtherPlans2UseSwiftStoragePolicies',
			'UsedDBForOpenStackComponents','OtherUsedDBForOpenStackComponents','ToolsUsedForYourUsers','OtherToolsUsedForYourUsers','Reason2Move2Ceilometer');
		$data   = array();

		foreach($res as $row){
			$member = array();
			foreach($fields as $field){
				$member[$field] = $row[$field];
			}
			array_push($data,$member);
		}

		$filename = "survey_results" . $fileDate . ".csv";

		return CSVExporter::getInstance()->export($filename, $data, ',');

	}

	// Export CSV of all App Dev Surveys
	function ExportAppDevSurveyResults() {
		$fileDate = date( 'Ymdhis' );

		$this->generateDateFilters('s');

		$surveyQuery = "select s.ID as SurveyID, s.Created as SurveyCreated,
                s.LastEdited as SurveyEdited, o.Name as OrgName, o.ID as OrgID,  a.ID as AppSurveyID,
                a.Created as AppSurveyCreated, a.LastEdited as AppSurveyEdited, m.FirstName,
                m.Surname, m.Email, s.Title, s.Industry, s.OtherIndustry, s.PrimaryCity,
                s.PrimaryState, s.PrimaryCountry, s.OrgSize, s.OpenStackInvolvement,
                s.InformationSources, s.OtherInformationSources, s.FurtherEnhancement,
                s.FoundationUserCommitteePriorities, s.UserGroupMember, s.UserGroupName,
                s.OkToContact, s.BusinessDrivers, s.OtherBusinessDrivers, s.WhatDoYouLikeMost,
                a.Toolkits, a.OtherToolkits, a.ProgrammingLanguages, a.OtherProgrammingLanguages,
                a.APIFormats, a.DevelopmentEnvironments, a.OtherDevelopmentEnvironments,
                a.OperatingSystems, a.OtherOperatingSystems, a.ConfigTools, a.OtherConfigTools,
                a.StateOfOpenStack, a.DocsPriority, a.InteractionWithOtherClouds
            from DeploymentSurvey s
                right join AppDevSurvey a on (a.DeploymentSurveyID = s.ID)
                left outer join Member m on (a.MemberID = m.ID)
                left outer join Org o on (s.OrgID = o.ID)
            where s.Title is not null AND ".$this->date_filter_query."
            order by s.ID;";

		$res = DB::query($surveyQuery);


		$fields = array('SurveyID','SurveyCreated','SurveyEdited','OrgName','OrgID','AppSurveyID','AppSurveyCreated', 'AppSurveyEdited', 'FirstName',
			'Surname','Email','Title','Industry','OtherIndustry','PrimaryCity','PrimaryState','PrimaryCountry','OrgSize','OpenStackInvolvement','InformationSources',
			'OtherInformationSources','FurtherEnhancement','FoundationUserCommitteePriorities','UserGroupMember','UserGroupName','OkToContact','BusinessDrivers',
			'OtherBusinessDrivers','WhatDoYouLikeMost','Toolkits','OtherToolkits','ProgrammingLanguages','OtherProgrammingLanguages','APIFormats','DevelopmentEnvironments','OtherDevelopmentEnvironments',
			'OperatingSystems','OtherOperatingSystems','ConfigTools','OtherConfigTools','StateOfOpenStack','DocsPriority','InteractionWithOtherClouds');
		$data   = array();

		foreach($res as $row){
			$member = array();
			foreach($fields as $field){
				$member[$field] = $row[$field];
			}
			array_push($data,$member);
		}

		$filename = "app_dev_surveys" . $fileDate . ".csv";

		return CSVExporter::getInstance()->export($filename, $data, ',');
	}

	function getSortIcon($type)
	{
		return $this->getSortDir($type, true) == 'desc' ? '&blacktriangledown;' : '&blacktriangle;';
	}

	public function getSortDir($type, $read_only = false)
	{
		$default = 'asc';
		$dir = Session::get($type . '.sort.dir');
		if (empty($dir)) {
			$dir = $default;
		} else {
			$dir = $dir == 'asc' ? 'desc' : 'asc';
		}
		if (!$read_only)
			Session::set($type . '.sort.dir', $dir);
		return $dir;
	}

	function Deployments()
	{
		$sort = $this->request->getVar('sort');
		$sort_dir = $this->getSortDir('deployments');
		$date_from = Convert::raw2sql(trim($this->request->getVar('date-from')));
		$date_to = Convert::raw2sql(trim($this->request->getVar('date-to')));

		$sort_query = '';
		$sort_dir = '';
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

	function DeploymentsSurvey()
	{

		$sqlQuery = new SQLQuery();
		$sqlQuery->addSelect(array('DeploymentSurvey.*'));
		$sqlQuery->addFrom(array("DeploymentSurvey, Deployment, Org"));
		$sqlQuery->addWhere(array("Deployment.DeploymentSurveyID = DeploymentSurvey.ID
                                AND Deployment.IsPublic = 1
                                AND Org.ID = DeploymentSurvey.OrgID
                                AND DeploymentSurvey.Title IS NOT NULL
                                "));
		$sqlQuery->addOrderBy('Org.Name');

		$result = $sqlQuery->execute();

		$arrayList = new ArrayList();

		foreach ($result as $rowArray) {
			// concept: new Product($rowArray)
			$arrayList->push(new $rowArray['ClassName']($rowArray));
		}

		return $arrayList;
	}

	function UserStoriesIndustries()
	{
		return UserStoriesIndustry::get()->filter('Active', 1);
	}

	// Current User Stories
	function UserStoriesPerIndustry($Industry)
	{
		return UserStory::get()->filter('UserStoriesIndustryID', $Industry);
	}

	function SetCaseStudy()
	{
		if (isset($_GET['ID']) && is_numeric($_GET['ID'])) {
			$UserStory = $_GET['ID'];
		} else {
			die();
		}

		$setCaseStudy = ($_GET['Set'] == 1) ? 1 : 0;
		$story = SiteTree::get_by_id("UserStory", $UserStory);

		$story->ShowCaseStudy = $setCaseStudy;
		$story->write();
		$story->publish("Live", "Stage");

		$this->setMessage('Success', 'Case Study updated for <b>' . $story->Title . '</b>');

		$this->redirectBack();
	}

	function SetAdminSS()
	{
		if (isset($_GET['ID']) && is_numeric($_GET['ID'])) {
			$UserStory = $_GET['ID'];
		} else {
			die();
		}
		$showinAdmin = ($_GET['Set'] == 1) ? 1 : 0;
		$story = SiteTree::get_by_id("UserStory", $UserStory);

		$parent = UserStoryHolder::get()->first();
		if (!$parent) {
			$this->setMessage('Error', 'could not publish user story bc there is not any available parent page(UserStoryHolder).');
			$this->redirectBack();
		}

		$story->ShowInAdmin = $showinAdmin;
		$story->setParent($parent); // Should set the ID once the Holder is created...
		$story->write();
		//$story->publish("Live", "Stage");

		$this->setMessage('Success', '<b>' . $story->Title . '</b> updated.');

		$this->redirectBack();
	}

	// Add User Story from Deployment
	function AddUserStory()
	{
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
		$userStory->setParent($parent); // Should set the ID once the Holder is created...
		$userStory->write();
		$userStory->publish("Live", "Stage");

		$this->setMessage('Success', '<b>' . $userStory->Title . '</b> added as User Story.');

		$this->redirectBack();
	}


	// Update Stories Industry and Order
	function UpdateStories()
	{
		foreach ($_POST['industry'] as $story_id => $industry) {
			$story = SiteTree::get_by_id("UserStory", $story_id);
			$story->UserStoriesIndustryID = $industry;
			$story->Sort = $_POST['order'][$story_id];
			$story->Video = $_POST['video'][$story_id];
			$story->Title = $_POST['title'][$story_id];
			$story->ShowVideo = ($_POST['video'][$story_id]) ? true : false;
			$story->write();
			$story->publish("Live", "Stage");
		}

		$this->setMessage('Success', 'User Stories saved.');

		$this->redirectBack();
	}

	function CountriesDDL()
	{
		return new CountryDropdownField('country', 'Country');
	}

	function ViewDeploymentDetails()
	{
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::javascript("themes/openstack/javascript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js");
		Requirements::javascript("themes/openstack/javascript/jquery.ui.datepicker.validation.package-1.0.1/jquery.ui.datepicker.validation.js");
		Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");
		Requirements::javascript("themes/openstack/javascript/sangria/view.deployment.details.js");
		return $this->getViewer('ViewDeploymentDetails')->process($this);
	}

	function getQuickActionsExtensions()
	{
		$html = '';
		$this->extend('getQuickActionsExtensions', $html);
		return $html;
	}

	function DeploymentDetails()
	{
		$params = $this->request->allParams();
		$deployment_id = intval(Convert::raw2sql($params["ID"]));;
		$deployment = Deployment::get()->byID($deployment_id);
		if ($deployment)
			return $this->Customise($deployment)->renderWith(array('SangriaPage_DeploymentDetails', 'SangriaPage', 'SangriaPage'));
		return $this->httpError(404, 'Sorry that Deployment could not be found!.');
	}

	function SurveyDetails()
	{
		$params = $this->request->allParams();
		$survey_id = intval(Convert::raw2sql($params["ID"]));;
		$survey = DeploymentSurvey::get()->byID($survey_id);
		if ($survey)
			return $this->Customise($survey)->renderWith(array('SangriaPage_SurveyDetails', 'SangriaPage', 'SangriaPage'));
		return $this->httpError(404, 'Sorry that Deployment Survey could not be found!.');
	}

	function exportFoundationMembers()
	{
		$params = $this->getRequest()->getVars();
		if (!isset($params['fields']) || empty($params['fields']))
			return $this->httpError('412', 'missing required param fields');

		if (!isset($params['ext']) || empty($params['ext']))
			return $this->httpError('412', 'missing required param ext');

		$fields = $params['fields'];
		$ext = $params['ext'];

		$sanitized_fields = array();

		if (!count($fields)) {
			return $this->httpError('412', 'missing required param fields');
		}

		$allowed_fields = array('ID' => 'ID', 'FirstName' => 'FirstName', 'SurName' => 'SurName', 'Email' => 'Email');

		for ($i = 0; $i < count($fields); $i++) {
			if (!array_key_exists($fields[$i], $allowed_fields))
				return $this->httpError('412', 'invalid field');
			array_push($sanitized_fields, 'Member.' . $fields[$i]);
		}

		$query = new SQLQuery();
		$query->addFrom('Member');
		$query->addSelect($sanitized_fields);
		$query->addInnerJoin('Group_Members', 'Group_Members.MemberID = Member.ID');
		$query->addInnerJoin('Group', "Group.ID = Group_Members.GroupID AND Group.Code='foundation-members'");
		$query->setOrderBy('SurName,FirstName');
		$result = $query->execute();

		$data = array();

		foreach ($result as $row) {
			$member = array();
			foreach ($fields as $field) {
				$member[$field] = $row[$field];
			}
			array_push($data, $member);
		}

		$filename = "FoundationMembers" . date('Ymd') . "." . $ext;

		return CSVExporter::getInstance()->export($filename, $data);
	}

	function exportCorporateSponsors()
	{

		$params = $this->getRequest()->getVars();

		if (!isset($params['levels']) || empty($params['levels']))
			return $this->httpError('412', 'missing required param level');

		if (!isset($params['fields']) || empty($params['fields']))
			return $this->httpError('412', 'missing required param fields');

		if (!isset($params['ext']) || empty($params['ext']))
			return $this->httpError('412', 'missing required param ext');

		$level = $params['levels'];

		$fields = $params['fields'];

		$ext = $params['ext'];

		$sanitized_fields = array();

		if (!count($fields)) {
			return $this->httpError('412', 'missing required param fields');
		}

		if (!count($level)) {
			return $this->httpError('412', 'missing required param $level');
		}

		$allowed_fields = array('MemberLevel' => 'MemberLevel', 'Name' => 'Name', 'City' => 'City', 'State' => 'State', 'Country' => 'Country', 'Industry' => 'Industry', 'ContactEmail' => 'ContactEmail', 'AdminEmail' => 'AdminEmail');
		$allowed_levels = array('Platinum' => 'Platinum', 'Gold' => 'Gold', 'Startup' => 'Startup', 'Mention' => 'Mention');
		for ($i = 0; $i < count($fields); $i++) {
			if (!array_key_exists($fields[$i], $allowed_fields))
				return $this->httpError('412', 'invalid field');
			array_push($sanitized_fields, 'Company.' . $fields[$i]);
		}
		for ($i = 0; $i < count($level); $i++) {
			if (!array_key_exists($level[$i], $allowed_levels))
				return $this->httpError('412', 'invalid level');
		}

		$query = new SQLQuery();

		$query->addFrom('Company');
		$query->addSelect($sanitized_fields);
		$query->addWhere(" MemberLevel IN ('" . implode("','", $level) . "')");
		$query->setOrderBy('MemberLevel');

		$result = $query->execute();

		$data = array();

		foreach ($result as $row) {
			$company = array();
			foreach ($fields as $field) {
				$company[$field] = $row[$field];
			}
			array_push($data, $company);
		}

		$filename = "Companies" . date('Ymd') . "." . $ext;

		return CSVExporter::getInstance()->export($filename, $data);
	}

	function ExportData()
	{
		$this->Title = 'Export Data';
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::javascript("themes/openstack/javascript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js");
		Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");
		Requirements::javascript('themes/openstack/javascript/sangria/sangria.page.export.data.js');
		return $this->getViewer('ExportData')->process($this);
	}

	function AddNewDeployment()
	{

		$survey = DataObject::get_one('DeploymentSurvey', 'ID = ' . $_POST['survey']);

		$deployment = new Deployment;
		$deployment->Label = $_POST['label'];
		$deployment->DeploymentType = $_POST['type'];
		$deployment->CountryCode = $_POST['country'];
		$deployment->DeploymentSurveyID = $_POST['survey'];
		if ($survey) {
			$deployment->OrgID = $survey->OrgID;
		} else {
			$deployment->OrgID = 0;
		}
		$deployment->IsPublic = 1;
		$deployment->write();

		$this->setMessage('Success', '<b>' . $_POST['label'] . '</b> added as a new Deployment.');

		$this->redirectBack();
	}

	function exportCLAUsers()
	{

		$params = $this->getRequest()->getVars();
		if (!isset($params['fields']) || empty($params['fields']))
			return $this->httpError('412', 'missing required param fields');

		if (!isset($params['ext']) || empty($params['ext']))
			return $this->httpError('412', 'missing required param ext');


		if (!isset($params['status']) || empty($params['status']))
			return $this->httpError('412', 'missing required param status');

		$sanitized_filters = $params['status'];
		$fields = $params['fields'];
		$ext = $params['ext'];

		$sanitized_filters = implode("','", $sanitized_filters);

		$sanitized_fields = array();

		if (!count($fields)) {
			return $this->httpError('412', 'missing required param fields');
		}

		$allowed_fields = array('ID' => 'ID', 'FirstName' => 'FirstName', 'SurName' => 'SurName', 'Email' => 'Email');

		for ($i = 0; $i < count($fields); $i++) {
			if (!array_key_exists($fields[$i], $allowed_fields))
				return $this->httpError('412', 'invalid field');
			array_push($sanitized_fields, 'M.' . $fields[$i]);
		}


		$sanitized_fields = implode(',', $sanitized_fields);

		$sql = <<< SQL
		SELECT {$sanitized_fields}
		, GROUP_CONCAT(G.Code, ' | ') AS Groups
		FROM Member M
		LEFT JOIN Group_Members GM on GM.MemberID = M.ID
		LEFT JOIN `Group` G  on G.ID = GM.GroupID
		WHERE GerritID IS NOT NULL AND G.Code IN ('{$sanitized_filters}')
		GROUP BY M.ID
		ORDER BY M.SurName, M.FirstName;
SQL;


		$result = DB::query($sql);
		$data = array();
		array_push($fields, 'Groups');
		foreach ($result as $row) {
			$member = array();
			foreach ($fields as $field) {
				$member[$field] = $row[$field];
			}
			array_push($data, $member);
		}

		$filename = "CLAMembers" . date('Ymd') . "." . $ext;

		return CSVExporter::getInstance()->export($filename, $data);
	}


	function exportGerritUsers()
	{
		$params = $this->getRequest()->getVars();
		if (!isset($params['status']) || empty($params['status']))
			return $this->httpError('412', 'missing required param status');

		if (!isset($params['ext']) || empty($params['ext']))
			return $this->httpError('412', 'missing required param ext');

		$status = $params['status'];
		$ext = $params['ext'];

		$sanitized_filters = array();
		$allowed_filter_values = array('foundation-members' => 'foundation-members', 'community-members' => 'community-members');
		for ($i = 0; $i < count($status); $i++) {
			if (!array_key_exists($status[$i], $allowed_filter_values))
				return $this->httpError('412', 'invalid filter value');
			array_push($sanitized_filters, $status[$i]);
		}

		$sanitized_filters = implode("','", $sanitized_filters);
		$sql = <<<SQL

		SELECT M.FirstName,
		   M.Surname,
	       M.Email,
		   COALESCE(NULLIF(M.SecondEmail , ''), 'N/A') AS Secondary_Email ,
	       M.GerritID,
	       COALESCE(NULLIF(M.LastCodeCommit, ''), 'N/A') AS LastCodeCommitDate,
		   g.Code as Member_Status,
		   CASE g.Code WHEN 'foundation-members' THEN (SELECT LA.Created FROM LegalAgreement LA WHERE LA.MemberID =  M.ID and LA.LegalDocumentPageID = 422) ELSE 'N/A'END AS FoundationMemberJoinDate,
		   CASE g.Code WHEN 'foundation-members' THEN 'N/A' ELSE ( SELECT ActionDate FROM FoundationMemberRevocationNotification WHERE RecipientID = M.ID AND Action = 'Revoked') END AS DateMemberStatusChanged ,
		   GROUP_CONCAT(O.Name, ' | ') AS Company_Affiliations
		FROM Member M
		LEFT JOIN Affiliation A on A.MemberID = M.ID
		LEFT JOIN Org O on O.ID = A.OrganizationID
		INNER JOIN Group_Members gm on gm.MemberID = M.ID
		INNER JOIN `Group` g on g.ID = gm.GroupID and ( g.Code = 'foundation-members' or g.Code = 'community-members')
		WHERE GerritID IS NOT NULL AND g.Code IN ('{$sanitized_filters}')
		GROUP BY M.ID;
SQL;

		$res = DB::query($sql);
		$fields = array('FirstName', 'Surname', 'Email', 'Secondary_Email', 'GerritID', 'LastCodeCommitDate', 'Member_Status', 'FoundationMemberJoinDate', 'DateMemberStatusChanged', 'Company_Affiliations');
		$data = array();

		foreach ($res as $row) {
			$member = array();
			foreach ($fields as $field) {
				$member[$field] = $row[$field];
			}
			array_push($data, $member);
		}

		$filename = "GerritUsers" . date('Ymd') . "." . $ext;

		return CSVExporter::getInstance()->export($filename, $data);
	}

	public function Groups()
	{
		$sql = <<<SQL
		SELECT G.Code,G.Title,G.ClassName FROM `Group` G ORDER BY G.Title;
SQL;
		$result = DB::query($sql);

		// let Silverstripe work the magic

		$groups = new ArrayList();

		foreach ($result as $rowArray) {
			// $res: new Product($rowArray)
			$groups->push(new $rowArray['ClassName']($rowArray));
		}
		return $groups;
	}

	function ViewDeploymentStatistics(){
		$this->generateDateFilters();
		Requirements::css("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.css");
		Requirements::javascript("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.js");
		Requirements::css("themes/openstack/css/deployment.survey.page.css");
		Requirements::javascript("themes/openstack/javascript/deployment.survey.filters.js");
		return $this->Customise(array())->renderWith(array('SangriaPage_ViewDeploymentStatistics','SangriaPage','SangriaPage'));
	}

	function ViewDeploymentSurveyStatistics(){
		$this->generateDateFilters();
		Requirements::css("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.css");
		Requirements::javascript("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.js");
		Requirements::css("themes/openstack/css/deployment.survey.page.css");
		Requirements::javascript("themes/openstack/javascript/deployment.survey.filters.js");
		return $this->Customise(array())->renderWith(array('SangriaPage_ViewDeploymentSurveyStatistics','SangriaPage','SangriaPage'));
	}


	function generateSelectListSummary($fieldName, $optionSet,  $applyDateFilters=false)
	{
		$list = new ArrayList();

		$urlString = $_SERVER["REDIRECT_URL"] . "?";
		$keyUrlString = "";
		$keyValue = "";

		foreach ($_GET as $key => $value) {
			if (preg_match("/Filter$/", $key)) {
				if ($key != $fieldName . "Filter") {
					$urlString .= $key . "=" . $value . "&";
				} else {
					$keyUrlString = $key . "=" . $value;
					$keyValue = $value;
				}
			}
		}

		foreach ($optionSet as $option => $label) {

			$query = "select count(*) from Deployment where ".$fieldName." like '%".$option."%'".$this->generateFilterWhereClause();
			$query .= ($applyDateFilters) ? ' AND '.$this->date_filter_query : '';

			$count = DB::query($query)->value();
			$do = new DataObject();

			$href = $urlString.$fieldName."Filter=".$option;

			if ($applyDateFilters) {
				$start_date = $this->request->getVar('From');
				$end_date = $this->request->getVar('To');
				if ($start_date && $end_date)
					$href .= "&From=".$start_date."&To=".$end_date;
			}

			$do->Value = "<a href='".$href."'>".$label."</a>";
			if( !empty($keyUrlString) && $keyValue != $option) {
				$do->Value .= " (<a href='".$urlString.$keyUrlString.",,".$option."'>+</a>) (<a href='".$urlString.$keyUrlString."||".$option."'>|</a>)";
			}
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}


	function generateFilterWhereClause()
	{
		$filterWhereClause = "";

		foreach ($_GET as $key => $value) {
			if (preg_match("/Filter$/", $key)) {
				$orValues = preg_split("/\|\|/", $value);
				$andValues = preg_split("/\,\,/", $value);

				if (count($orValues) > 1) {
					$filterWhereClause .= " and (";
					for ($i = 0; $i < count($orValues); $i++) {
						if ($i > 0) {
							$filterWhereClause .= " OR ";
						}
						$filterWhereClause .= preg_replace("/Filter$/", "", $key) . " like '%" . $orValues[$i] . "%'";
					}
					$filterWhereClause .= ")";
				} else if (count($andValues) > 1) {
					$filterWhereClause .= " and (";
					for ($i = 0; $i < count($andValues); $i++) {
						if ($i > 0) {
							$filterWhereClause .= " AND ";
						}
						$filterWhereClause .= preg_replace("/Filter$/", "", $key) . " like '%" . $andValues[$i] . "%'";
					}
					$filterWhereClause .= ")";
				} else {
					$filterWhereClause .= " and " . preg_replace("/Filter$/", "", $key) . " like '%" . $value . "%'";
				}
			}
		}


		return $filterWhereClause;
	}

	function generateDateFilters($table_prefix = '' ) {
		$where_query = '';
		$start_date = $this->request->getVar('From');
		$end_date = $this->request->getVar('To');
		if(!empty($table_prefix))
			$table_prefix .= '.';
		if(isset($start_date) && isset($end_date)){
			$date_from = Convert::raw2sql(trim($start_date));
			$date_to   = Convert::raw2sql(trim($end_date));
			$start = new \DateTime($date_from);
			$start->setTime(00, 00, 00);
			$end   = new \DateTime($date_to);
			$end->setTime(23, 59, 59);
			$where_query .= " ( {$table_prefix}UpdateDate >= '{$start->format('Y-m-d H:i:s')}' AND {$table_prefix}UpdateDate <= '{$end->format('Y-m-d H:i:s')}' ) ";
		} else {
			$where_query .= " ( {$table_prefix}UpdateDate >= '{$this->default_start_date}' AND {$table_prefix}UpdateDate <= '{$this->default_end_date}' ) ";
		}

		$this->date_filter_query = $where_query;
	}

	//Survey date filters constructor
	function DateFilters($action='') {
		$start_date = ($this->request->getVar('From')) ? $this->request->getVar('From') : $this->default_start_date;
		$end_date = ($this->request->getVar('To')) ? $this->request->getVar('To') : $this->default_end_date;

		$data = array("start_date"=>$start_date, "end_date"=>$end_date, "action"=>$action);
		return $this->renderWith("SurveyDateFilters",$data);
	}
}
