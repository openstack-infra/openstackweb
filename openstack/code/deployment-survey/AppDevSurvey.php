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
class AppDevSurvey extends DataObject {

	static $db = array(

		// Section 2
		'Toolkits' => 'Text',
		'OtherToolkits' => 'Text',
		'ProgrammingLanguages' => 'Text',
		'OtherProgrammingLanguages' => 'Text',
		'APIFormats' => 'Text',
		'DevelopmentEnvironments' => 'Text',
		'OtherDevelopmentEnvironments' => 'Text',
		'OperatingSystems' => 'Text',
		'OtherOperatingSystems' => 'Text',
		'ConfigTools' => 'Text',
		'OtherConfigTools' => 'Text',
		'StateOfOpenStack' => 'Text',
		'DocsPriority' => 'Text',
		'InteractionWithOtherClouds'=> 'Text',
	);

	static $has_one = array(
		'DeploymentSurvey' => 'DeploymentSurvey',
		'Member' => 'Member'
	);

	static $singular_name = 'App Development Survey';
	static $plural_name = 'App Development Surveys';

	public function getCountry() {
        return $this->DeploymentSurvey()->PrimaryCountry;
    }

	public function getIndustry() {
        return $this->DeploymentSurvey()->Industry;
    }

	public function getMember() {
        return $this->DeploymentSurvey()->Member();
    }

	public function getOrg() {
        return $this->Org()->Name;
    }

	public static $toolkits_options = array (
		'Deltacloud' => 'Deltacloud (HTTP API)',
		'FOG' => 'FOG (Ruby)',
		'jclouds' => 'jclouds (Java)',
		'OpenStack.net' => 'OpenStack.net (C#)',
		'OpenStack clients' => 'OpenStack clients (Python)',
		'php-opencloud' => 'php-opencloud (PHP)',
		'pkgcloud' => 'pkgcloud (Node.js)',
		'None' => 'None/Wrote my own',
	);

	public static $languages_options = array (
		'C/C++' => 'C/C++',
		'C#' => 'C#',
		'Java' => 'Java',
		'Node.js' => 'Node.js',
		'Perl' => 'Perl',
		'PHP' => 'PHP',
		'Python' => 'Python',
		'Ruby' => 'Ruby',
		'Go' => 'Go',
		'Shell Scripts (eg bash with curl)' => 'Shell Scripts (eg bash with curl)',

	);

	public static $api_format_options = array (
		'JSON' => 'JSON',
		'XML' => 'XML'
	);

	public static $opsys_options = array (
		'Linux' => 'Linux',
		'Mac OS X' => 'Mac OS X',
		'Windows' => 'Windows',

	);

	public static $ide_options = array (
		'Eclipse' => 'Eclipse or Eclipse-based IDE',
		'IntelliJ' => 'IntelliJ IDEA or IDEA-based IDE',
		'Sublime' => 'Sublime',
		'Vim' => 'Vim',
		'Visual Studio' => 'Visual Studio',
		'Atom' => 'Atom',
		'Emacs' => 'Emacs',
		'Pycharm' => 'Pycharm',

	);

	public static $config_tool_options = array (
		'Ansible' => 'Ansible',
		'Chef' => 'Chef',
		'Cloud Foundry' => 'Cloud Foundry and/or BOSH',
		'Docker' => 'Docker',
		'Heat' => 'OpenStack Orchestration (Heat)',
		'Puppet' => 'Puppet',
		'SaltStack' => 'SaltStack',
		'OpenShift' => 'OpenShift',
		'Juju' => 'Juju',

	);

	public static $interaction_with_other_clouds__options = array (
		'Noe' => 'No',
		'Yes, but only OpenStack ones' => 'Yes, but only OpenStack ones',
		'Yes, Amazon also' => 'Yes, Amazon also',
		'Yes, Google Compute Engine also' => 'Yes, Google Compute Engine also',
		'Yes, Azure also' => 'Yes, Azure also',
		'Yes, multiple other clouds' => 'Yes, multiple other clouds',
	);
}
