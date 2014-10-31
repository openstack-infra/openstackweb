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
class UserStoriesLink extends DataObject {

	static $db = array(
		'LinkName' => 'Text',
		'LinkURL' => 'Text',
		'Description' => 'Text',
	);

	static $has_one = array(
		'UserStory' => 'UserStory'
	);

	static $summary_fields = array(
		'UserStory.Title' => 'Story',
		'LinkName' => 'Link Name', 
		'LinkURL' => 'URL'
	);

	static $singular_name = 'Link';
	static $plural_name = 'Links';

	function getCMSFields() {
		$fields = parent::getCMSFields();
			 
		$user_stories = UserStory::get();
		if ($user_stories) {
				$user_stories = $user_stories->map('ID', 'Title', '(Select one)', true);
		}
		
		$fields->addFieldstoTab('Root.Main', 
			array(
				new DropdownField('UserStoryID', 'User Story', $user_stories),
				new TextField('LinkName', 'Link Name'),
				new TextField('LinkURL', 'Full URL'),
				new TextField('Description', 'Description'),
				new HiddenField('SortOrder')
			)
		);
		 
		return $fields;
	}
}