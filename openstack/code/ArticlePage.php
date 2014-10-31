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
/**
 * Defines the ArticlePage page type
 */
class ArticlePage extends Page {
   static $db = array(
    	'Date' => 'Date',
    	'Author' => 'Text'
	);
   static $has_one = array(
   );
   static $icon = "themes/tutorial/images/treeicons/news";
   
 	function getCMSFields() {
    	$fields = parent::getCMSFields();
    	
    	$datefield = new DateField('Date');
    	$datefield->setConfig('showcalendar', true);
    	$datefield->setConfig('showdropdown', true);

    	$fields->addFieldToTab('Root.Main', $datefield, 'Content');
    	$fields->addFieldToTab('Root.Main', new TextField('Author'), 'Content');

    	return $fields;
 	}   
}
 
class ArticlePage_Controller extends Page_Controller {
 
}