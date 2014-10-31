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
class UserStoriesSlides extends DataObject {

	static $db = array(
		'Type' => 'Text',
		'Quote' => 'Text',
		'Author' => 'Text',
		'SlideLabel' => 'Text',
		'SortOrder' => 'Int'
	);

	static $has_one = array(
		'UserStoriesTopics' => 'UserStoriesTopics',
	);

	static $summary_fields = array(
		'Type' => 'Type',
		'Quote' => 'Quote',
		'UserStoriesTopics.Topic' => 'Topic'
	);

	static $singular_name = 'Slide';
	static $plural_name = 'Slides';

	
   function getCMSFields() {

		$fields = parent::getCMSFields();
			 
		$topics = UserStoriesTopics::get();
		if ($topics) {
				$topics = $topics->map('ID', 'Topic', '(Select one)', true);
		}

		$types = array(
			'Topic' => 'Topic',
			'Quote' => 'Quote'
		);
		
		$fields->addFieldstoTab('Root.Main', 
			array(
				new DropdownField('Type', 'Type',$types),
				new HiddenField('SortOrder')
			)
		);

		 $fields->addFieldstoTab('Root.Quote', 
			array(
				new TextField('Quote','Quote'),
				new TextField('Author','Quote Author'),
				new TextField('SlideLabel','Label for Slider')
			)
		);

		$fields->addFieldstoTab('Root.Topic', 
			array(
				new DropdownField('UserStoriesTopicsID', 'Topic', $topics),
			)
		);
		 
		return $fields;
	}

	public function Content(){
		if ($this->Type == 'Quote'){
			return $this->Quote;
		}
		else{
			return $this->UserStoriesTopics()->Topic;
		}
	}

	public function getStories(){
		return UserStoriesTopicsFeatured::get()->filter('UserStoriesTopicsID', $this->UserStoriesTopicsID);
	}
}


class UserStoriesSlides_Controller extends Page_Controller{

}