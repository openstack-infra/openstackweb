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
class UserStoriesTopicsFeatured extends DataObject {

	static $db = array(
		'Title' => 'Text',
		'Type' => 'Text',
		'VideoURL' => 'Text',
	);

	static $has_one = array(
		'UserStory' => 'UserStory',
		'UserStoriesTopics' => 'UserStoriesTopics',
		'Thumbnail' => 'Image'
	);

	static $summary_fields = array(
		'UserStory.Title' => 'Story',
		'UserStoriesTopics.Topic' => 'Topic', 
	);

	static $singular_name = 'Story by Topic';
	static $plural_name = 'Stories by Topic';

	function getCMSFields() {
		$fields = parent::getCMSFields();
			 
		$user_stories = UserStory::get();
		if ($user_stories) {
				$user_stories = $user_stories->map('ID', 'Title', '(Select one)', true);
		}

		$topics = UserStoriesTopics::get();
		if ($topics) {
				$topics = $topics->map('ID', 'Topic', '(Select one)', true);
		}

		$types = array(
			'video' => 'Video',
			'case_study' => 'Case Study'
		);
		
		$fields->addFieldstoTab('Root.Main', 
			array(
				new TextField('Title', 'Title'),
				new DropdownField('UserStoryID', 'User Story', $user_stories),
				new DropdownField('UserStoriesTopicsID', 'Topic', $topics),
				new DropdownField('Type', 'Type', $types),
				new TextField('VideoURL', 'YouTube Video ID','If this is empty and type is Video, it will be used the User Story video'),
				new CustomUploadField('Thumbnail', 'Thumbnail'),
				new HiddenField('SortOrder')
			)
		);
		 
		return $fields;
	}

	public function Thumbnail305(){
		return $this->Thumbnail()->SetWidth(305);
	}

	public function LabelTitle(){
		if ($this->UserStoryID > 0){
			return $this->UserStory()->Title;
		}
		else{
			return $this->Title;
		}
	}
}