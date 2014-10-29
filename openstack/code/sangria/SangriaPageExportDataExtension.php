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
 * Class SangriaPageExportDataExtension
 */
final class SangriaPageExportDataExtension extends Extension
{

	public function onBeforeInit()
	{
		Config::inst()->update(get_class($this), 'allowed_actions', array(
			'ExportData',
			'exportCLAUsers',
			'exportGerritUsers',
			'ExportSurveyResults',
			'ExportAppDevSurveyResults',
			'exportFoundationMembers',
			'exportCorporateSponsors',
		));


		Config::inst()->update(get_class($this->owner), 'allowed_actions', array(
			'ExportData',
			'exportCLAUsers',
			'exportGerritUsers',
			'ExportSurveyResults',
			'ExportAppDevSurveyResults',
			'exportFoundationMembers',
			'exportCorporateSponsors',
		));
	}

	function ExportData()
	{
		$this->Title = 'Export Data';
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol() . "ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::css(THIRDPARTY_DIR . '/jquery-ui-themes/smoothness/jquery-ui.css');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-ui/jquery-ui.js');
		Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");
		Requirements::javascript('themes/openstack/javascript/sangria/sangria.page.export.data.js');
		return $this->owner->getViewer('ExportData')->process($this->owner);
	}

	function exportCLAUsers()
	{

		$params = $this->owner->getRequest()->getVars();
		if (!isset($params['fields']) || empty($params['fields']))
			return $this->owner->httpError('412', 'missing required param fields');

		if (!isset($params['ext']) || empty($params['ext']))
			return $this->owner->httpError('412', 'missing required param ext');

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
			array_push($sanitized_fields, 'M.' . $fields[$i]);
		}

		$sanitized_fields = implode(',', $sanitized_fields);

		$sql = <<< SQL
		SELECT {$sanitized_fields}
		, GROUP_CONCAT(G.Code, ' | ') AS Groups
		FROM Member M
		LEFT JOIN Group_Members GM on GM.MemberID = M.ID
		LEFT JOIN `Group` G  on G.ID = GM.GroupID
		WHERE GerritID IS NOT NULL
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
		$params = $this->owner->getRequest()->getVars();
		if (!isset($params['status']) || empty($params['status']))
			return $this->owner->httpError('412', 'missing required param status');

		if (!isset($params['ext']) || empty($params['ext']))
			return $this->owner->httpError('412', 'missing required param ext');

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
	}

	// Export CSV of all Deployment Surveys and Associated Deployments
	function ExportSurveyResults()
	{
		$fileDate = date('Ymdhis');

		SangriaPage_Controller::generateDateFilters('s');

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
            where s.Title is not null AND " . SangriaPage_Controller::$date_filter_query . "
            order by s.ID;";

		$res = DB::query($surveyQuery);

		$fields = array('SurveyID', 'SurveyCreated', 'SurveyEdited', 'OrgName', 'OrgID', 'DeploymentID', 'DeploymentCreated', 'DeploymentEdited', 'FirstName',
			'Surname', 'Email', 'Title', 'Industry', 'OtherIndustry', 'PrimaryCity', 'PrimaryState', 'PrimaryCountry', 'OrgSize', 'OpenStackInvolvement', 'InformationSources',
			'OtherInformationSources', 'FurtherEnhancement', 'FoundationUserCommitteePriorities', 'UserGroupMember', 'UserGroupName', 'OkToContact', 'BusinessDrivers',
			'OtherBusinessDrivers', 'WhatDoYouLikeMost', 'NetPromoter', 'OpenStackRecommendation', 'Label', 'IsPublic', 'DeploymentType', 'ProjectsUsed',
			'CurrentReleases', 'DeploymentStage', 'NumCloudUsers', 'APIFormats', 'Hypervisors', 'OtherHypervisor', 'BlockStorageDrivers', 'OtherBlockStorageDriver',
			'NetworkDrivers', 'OtherNetworkDriver', 'IdentityDrivers', 'OtherIndentityDriver', 'SupportedFeatures', 'ComputeNodes', 'ComputeCores', 'ComputeInstances',
			'BlockStorageTotalSize', 'ObjectStorageSize', 'ObjectStorageNumObjects', 'NetworkNumIPs', 'WorkloadsDescription', 'OtherWorkloadsDescription',
			'WhyNovaNetwork', 'OtherWhyNovaNetwork', 'DeploymentTools', 'OtherDeploymentTools', 'OperatingSystems', 'OtherOperatingSystems', 'SwiftGlobalDistributionFeatures',
			'SwiftGlobalDistributionFeaturesUsesCases', 'OtherSwiftGlobalDistributionFeaturesUsesCases', 'Plans2UseSwiftStoragePolicies', 'OtherPlans2UseSwiftStoragePolicies',
			'UsedDBForOpenStackComponents', 'OtherUsedDBForOpenStackComponents', 'ToolsUsedForYourUsers', 'OtherToolsUsedForYourUsers', 'Reason2Move2Ceilometer');
		$data = array();

		foreach ($res as $row) {
			$member = array();
			foreach ($fields as $field) {
				$member[$field] = $row[$field];
			}
			array_push($data, $member);
		}

		$filename = "survey_results" . $fileDate . ".csv";

		return CSVExporter::getInstance()->export($filename, $data, ',');

	}

	// Export CSV of all App Dev Surveys
	function ExportAppDevSurveyResults()
	{

		$fileDate = date('Ymdhis');

		SangriaPage_Controller::generateDateFilters('s');

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
            where s.Title is not null AND " . SangriaPage_Controller::$date_filter_query . "
            order by s.ID;";

		$res = DB::query($surveyQuery);


		$fields = array('SurveyID', 'SurveyCreated', 'SurveyEdited', 'OrgName', 'OrgID', 'AppSurveyID', 'AppSurveyCreated', 'AppSurveyEdited', 'FirstName',
			'Surname', 'Email', 'Title', 'Industry', 'OtherIndustry', 'PrimaryCity', 'PrimaryState', 'PrimaryCountry', 'OrgSize', 'OpenStackInvolvement', 'InformationSources',
			'OtherInformationSources', 'FurtherEnhancement', 'FoundationUserCommitteePriorities', 'UserGroupMember', 'UserGroupName', 'OkToContact', 'BusinessDrivers',
			'OtherBusinessDrivers', 'WhatDoYouLikeMost', 'Toolkits', 'OtherToolkits', 'ProgrammingLanguages', 'OtherProgrammingLanguages', 'APIFormats', 'DevelopmentEnvironments', 'OtherDevelopmentEnvironments',
			'OperatingSystems', 'OtherOperatingSystems', 'ConfigTools', 'OtherConfigTools', 'StateOfOpenStack', 'DocsPriority', 'InteractionWithOtherClouds');
		$data = array();

		foreach ($res as $row) {
			$member = array();
			foreach ($fields as $field) {
				$member[$field] = $row[$field];
			}
			array_push($data, $member);
		}

		$filename = "app_dev_surveys" . $fileDate . ".csv";

		return CSVExporter::getInstance()->export($filename, $data, ',');
	}

	function exportFoundationMembers(){
		$params = $this->owner->getRequest()->getVars();
		if(!isset($params['fields']) || empty($params['fields']) )
			return $this->owner->httpError('412','missing required param fields');

		if(!isset($params['ext']) || empty($params['ext']) )
			return $this->owner->httpError('412','missing required param ext');

		$fields = $params['fields'];
		$ext    = $params['ext'];

		$sanitized_fields = array();

		if(!count($fields)){
			return $this->owner->httpError('412','missing required param fields');
		}

		$allowed_fields = array('ID'=>'ID','FirstName'=>'FirstName','SurName'=>'SurName','Email'=>'Email');

		for($i=0 ; $i< count($fields);$i++){
			if(!array_key_exists($fields[$i],$allowed_fields))
				return $this->httpError('412','invalid field');
			array_push($sanitized_fields, 'Member.'.$fields[$i]);
		}

		$query  = new SQLQuery();

		$query->setFrom('Member');
		$query->setSelect($sanitized_fields);
		$query->addInnerJoin('Group_Members','Group_Members.MemberID = Member.ID');
		$query->addInnerJoin('Group',"Group.ID = Group_Members.GroupID AND Group.Code='foundation-members'");
		$query->setOrderBy('SurName,FirstName');

		$result = $query->execute();

		$data   = array();

		foreach($result as $row){
			$member = array();
			foreach($fields as $field){
				$member[$field] = $row[$field];
			}
			array_push($data,$member);
		}

		$filename = "FoundationMembers" . date('Ymd') . ".".$ext;

		return CSVExporter::getInstance()->export($filename, $data);
	}

	function exportCorporateSponsors(){

		$params = $this->owner->getRequest()->getVars();

		if(!isset($params['levels']) || empty($params['levels']) )
			return $this->owner->httpError('412','missing required param level');

		if(!isset($params['fields']) || empty($params['fields']) )
			return $this->owner->httpError('412','missing required param fields');

		if(!isset($params['ext']) || empty($params['ext']) )
			return $this->owner->httpError('412','missing required param ext');

		$level  = $params['levels'];

		$fields = $params['fields'];

		$ext    = $params['ext'];

		$sanitized_fields = array();

		if(!count($fields)){
			return $this->owner->httpError('412','missing required param fields');
		}

		if(!count($level)){
			return $this->owner->httpError('412','missing required param $level');
		}

		$allowed_fields = array('MemberLevel'=>'MemberLevel','Name'=>'Name','City'=>'City','State'=>'State','Country'=>'Country','Industry'=>'Industry','ContactEmail'=>'ContactEmail','AdminEmail'=>'AdminEmail');
		$allowed_levels = array('Platinum'=>'Platinum', 'Gold'=>'Gold','Startup'=>'Startup','Mention'=>'Mention');
		for($i = 0 ; $i< count($fields);$i++){
			if(!array_key_exists($fields[$i],$allowed_fields))
				return $this->httpError('412','invalid field');
			array_push($sanitized_fields, 'Company.'.$fields[$i]);
		}
		for($i = 0 ; $i< count($level);$i++){
			if(!array_key_exists($level[$i],$allowed_levels))
				return $this->httpError('412','invalid level');
		}

		$query  = new SQLQuery();

		$query->setFrom('Company');
		$query->setSelect($sanitized_fields);
		$query->setWhere(" MemberLevel IN ('".implode("','",$level) ."')");
		$query->setOrderBy('MemberLevel');

		$result = $query->execute();

		$data   = array();

		foreach($result as $row){
			$company = array();
			foreach($fields as $field){
				$company[$field] = $row[$field];
			}
			array_push($data,$company);
		}

		$filename = "Companies" . date('Ymd') . ".".$ext;

		return CSVExporter::getInstance()->export($filename, $data);
	}

}