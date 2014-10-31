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
class Bio extends DataObject {

	static $db = array(
		'FirstName' => 'Text',
		'LastName' => 'Text',
		'Email' => 'Text',
		'JobTitle' => 'Text',
		'Company' => 'Text',
		'Bio' => 'HTMLText',
		'DisplayOnSite' => 'Boolean',
		'Role' => 'Text'
	);

	Static $defaults = array('DisplayOnSite' => TRUE);
	
	static $has_one = array(
		'Photo' => 'BetterImage',
		'BioPage' => 'BioPage'
	);

	static $summary_fields = array( 
	      'FirstName' => 'First Name', 
	      'LastName' => 'Last Name',
	      'Email' => 'Email'
	 );	
	
	static $singular_name = 'Bio';
	static $plural_name = 'Bios';

	function getCMSFields() {
		$photo = new CustomUploadField('Photo', 'Photo');
		$photo->setAllowedFileCategories('image');
		$fields = new FieldList (
			new TextField('FirstName','First Name'),
			new TextField('LastName','Last Name'),
			new TextField('Email','Email'),
			new TextField('Role','Role / Position For This OpenStack Group (if any)'),			
			new TextField('JobTitle','Job Title'),
			new TextField('Company','Company'),
			new HtmlEditorField('Bio','Brief Bio'),
			new CheckboxField ('DisplayOnSite','Inlcude this bio on openstack.org'),
			$photo
		);
		return $fields;
	}

}