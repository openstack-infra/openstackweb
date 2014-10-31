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
 * Defines the JobsHolder page type
 */
class BioPage extends Page {
   static $db = array(
   );
   static $has_one = array(
   );
   static $has_many = array(
   		'Bios' => 'Bio'
   );

	function getCMSFields() {
	   	$fields = parent::getCMSFields();

		$biosTable = new GridField('Bio', 'Bio', $this->Bios());
	  	   	$fields->addFieldToTab('Root.Bios',$biosTable);
	   	return $fields;
	}   
 
}
 
class BioPage_Controller extends Page_Controller {
	
	public function Children() {
		return Bio::get()->filter(array('BioPageID'=>$this->ID))->sort('LastName ASC');
	}

}