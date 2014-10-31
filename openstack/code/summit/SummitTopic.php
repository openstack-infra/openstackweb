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
class SummitTopic extends DataObject {

	static $db = array(
		'Location' => 'Varchar(255)',
		'Time' => 'SS_Datetime'
	);
	
	static $has_one = array(
		'Topic' => 'Topic',
		'Summit' => 'Summit'
	);

	static $has_many = array(
		'Talks' => 'Talk',
		'Chairs' => 'Member'
	);

	static $summary_fields = array( 
		'Topic.Name' => 'Name', 
		'Location' => 'Location',
		'Summit.Name' => 'Summit' 
	);

}