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
class OSLogoProgramResponse extends DataObject {

	static $db = array(
		'FirstName' => 'Text',
		'Surname' => 'Text',
		'Email' => 'Text',
		'Phone' => 'Text',
        'Program' => 'Text',
		'CurrentSponsor' => 'Boolean',
		'CompanyDetails' => 'Text',
        'Product' => 'Text',
		'Category' => 'Text',
		'Regions' => 'Text',
		'APIExposed' => 'Boolean',
		'OtherCompany' => 'Text',
		'Projects' => 'Text'
	);

	static $has_one = array ( 
		'Company' => 'Company' 
	); 

	static $singular_name = 'OSLogoProgramResponse';
	static $plural_name = 'OSLogoProgramResponses';

	public static $avialable_categories = array (
		'Public Clouds' => 'Public Clouds',
		'Distributions' => 'Distributions',
		'Converged Appliances' => 'Converged Appliances',
		'Storage' => 'Storage',
		'Consultants & System Integrators' => 'Consultants & System Integrators',
		'Training' => 'Training',
		'PaaS' => 'PaaS',
		'Management & Monitoring' => 'Management & Monitoring',
		'Apps on OpenStack' => 'Apps on OpenStack',
		'Compatible HW & SW' => 'Compatible Hardware & Software'
	);

	public static $avialable_regions = array (
		'North America' => 'North America',
		'South America' => 'South America',
		'Europe' => 'Europe',
		'Asia Pacific' => 'Asia Pacific'
	);

    public static $avialable_programs = array (
        'Powered' => 'Powered',
        'Compatible' => 'Compatible',
        'Training' => 'Training'
    );

}