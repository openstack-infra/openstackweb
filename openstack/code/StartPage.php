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
/**
 * Defines the JobsHolder page type
 */
class StartPage extends Page {
   static $db = array(
      'Summary' => 'HTMLText'
   );
   static $has_one = array(
   );
 
   static $allowed_children = array(NULL);
   /** static $icon = "icon/path"; */

   function getCMSFields() {
      $fields = parent::getCMSFields();
      
      // the date field is added in a bit more complex manner so it can have the dropdown date picker
      $SummaryField = new TextareaField('Summary','Quick summary:');
      $fields->addFieldToTab('Root.Main', $SummaryField, 'Content');
          
      return $fields;
   }      
      
}
 
class StartPage_Controller extends Page_Controller {
	
	function init() {
	    parent::init();
	}

  function StartOverview() {
    $StartPageHolder = StartPageHolder::get()->first();
    return $StartPageHolder->Content;
  }   
	
}