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
class UserStoriesIndustry extends DataObject {

	static $db = array(
		'IndustryName' => 'Text',
		'Active' => 'Boolean'
	);

	static $summary_fields = array(
		'IndustryName' => 'Industry Name', 
		'Active' => 'Active'
	);

	static $singular_name = 'Industry';
	static $plural_name = 'Industries';


	function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldstoTab('Root.Main', 
			array(
				new TextField('IndustryName', 'Name'),
				new CheckboxField('Active', 'Active'),
				new HiddenField('SortOrder')
			)
		);

		return $fields;
	}

	function FeaturedStory(){
		return UserStoriesFeatured::get()->filter('UserStoriesIndustryID',$this->ID);
	}

	function Stories(){
		return UserStory::get()->filter('UserStoriesIndustryID',$this->ID);
	}

}