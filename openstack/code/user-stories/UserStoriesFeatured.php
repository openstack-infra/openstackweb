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
class UserStoriesFeatured extends DataObject {

	static $has_one = array(
		'UserStory' => 'UserStory',
		'UserStoriesIndustry' => 'UserStoriesIndustry'
	);

	static $summary_fields = array(
		'UserStory.Title' => 'Story',
		'UserStoriesIndustry.IndustryName' => 'Industry', 
	);

	static $singular_name = 'Featured Story';
	static $plural_name = 'Featured Stories';

	function getCMSFields() {
		$fields = parent::getCMSFields();
			 
		$user_stories = UserStory::get();
		if ($user_stories) {
				$user_stories = $user_stories->map('ID', 'Title', '(Select one)', true);
		}

		$industries = UserStoriesIndustry::get();
		if ($industries) {
				$industries = $industries->map('ID', 'IndustryName', '(Select one)', true);
		}
		
		$fields->addFieldstoTab('Root.Main', 
			array(
				new DropdownField('UserStoryID', 'User Story', $user_stories),
				new DropdownField('UserStoriesIndustryID', 'Industry', $industries)
			)
		);
		 
		return $fields;
	}
}