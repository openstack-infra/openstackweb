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
class Org extends DataObject {

	private static $create_table_options = array('MySQLDatabase' => 'ENGINE=MyISAM');

	static $db = array(
		'Name' => 'Text',
		'IsStandardizedOrg' => 'Boolean',
		'FoundationSupportLevel' => "Enum('Platinum Member, Gold Member, Corporate Sponsor, Startup Sponsor, Supporting Organization')",
	);

	static $has_one = array(
		'OrgProfile' => 'Company'
	);

	static $has_many = array(
		'Members' => 'Member'
	);

	static $many_many = array(
		'InvolvementTypes' => 'InvolvementType'
	);

	static $singular_name = 'Org';
	static $plural_name = 'Orgs';

}