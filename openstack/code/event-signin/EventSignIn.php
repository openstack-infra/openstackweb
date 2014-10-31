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
class EventSignIn extends DataObject {
   // Define what variables this data object has
   static $db = array(
	'EmailAddress' => 'Text',
	'FirstName' => 'Text',
	'LastName' => 'Text'
   );
   
   // Create a relationship between the data object and its parent page. This is needed especially for the DOM's edit windows to work.
	static $has_one = array (
		'SigninPage' => 'SigninPage'
	);

	//Define fields to show in the DOM list view table
	static $summary_fields = array(
		// 'field name' => 'column label'
		'EmailAddress' => 'Email Address',
		'FirstName' => 'First Name',
		'LastName' => 'Last Name'
	);
	
	//Define fields to show in the popup editor window
	public function getCMSFields()
	{
		return new FieldList(
			new TextField('FirstName'),
			new TextField('LastName'),
			new TextField('EmailAddress')
		);
	}

}