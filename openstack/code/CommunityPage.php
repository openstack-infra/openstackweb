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
	class CommunityPage extends Page {
		static $db = array(
		      'TopSection' => 'HTMLText'
		);
		static $has_one = array(
	     );

	 	function getCMSFields() {
	    	$fields = parent::getCMSFields();
	    	$fields->addFieldToTab('Root.Main', new HTMLEditorField('TopSection','Top Section'), 'Content');
	    	$fields->renameField("Content", "Middle Area");

	    	return $fields;
	 	}  

	}

	class CommunityPage_Controller extends Page_Controller {

		function init() {
			parent::init();
       	}
		
		function DeveloperActivityFeed(){ 
	   		$data = file_get_contents('http://www.openstack.org/feeds/developer-activity.php');
	   		return $data;
		}
		
		function PullFeed(){ 
			   		$data = file_get_contents('http://www.openstack.org/simplepie/flickr.php'); 
			   		return $data;
		}
		
		function CompanyCount() {
			$Count = Company::get()->filter('DisplayOnSite',true)->count();
			// Round down to nearest multiple of 5
			$Count = round(($Count-2.5)/5)*5;
			return $Count;
		}
				
	}