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
class PresentationCategoryPage extends Page {
   static $db = array(
   		'StillUploading' => 'Boolean'
   );
   static $has_one = array(
   );
   static $has_many = array(
   		'Presentations' => 'Presentation'
   	);
 
   static $allowed_children = array('PresentationCategoryPage');
   /** static $icon = "icon/path"; */

    function getCMSFields() {
    	$fields = parent::getCMSFields();
    	$presentationsTable = new GridField('Presentations', 'Presentations',$this->Presentations());
    	$fields->addFieldToTab('Root.Presentations',$presentationsTable);

    	// Summit Videos
      	$VideosUploadingField = new OptionSetField('StillUploading', 'Are videos still being uploaded?', array(
            '1' => 'Yes - A message will be displayed.',
            '0' => 'No'
        ));

		$fields->addFieldToTab("Root.Main", $VideosUploadingField, 'Content');

    	return $fields;
	}

}
 
class PresentationCategoryPage_Controller extends Page_Controller {


	static $allowed_actions = array(
		'presentation',
		'updateURLS' => 'admin'
	);	
	
	public function Presentations(){
		if(isset($_GET['day'])){
			$sessions = "";
			$day = Convert::raw2xml($_GET['day']);
			$day = (int)$day;
			if (is_numeric($day)) {
		   		$sessions = Presentation::get()->filter(array( 'PresentationCategoryPageID' => $this->ID , 'Day' => $day))->sort('StartTime','ASC');
		   	} else {
		   		$sessions = Presentation::get()->filter(array( 'PresentationCategoryPageID' => $this->ID , 'Day' => 1))->sort('StartTime','ASC');
		   	}
	  	} else {
		   		$sessions = Presentation::get()->filter(array( 'PresentationCategoryPageID' => $this->ID , 'Day' => 1))->sort('StartTime','ASC');
	  	}
		return $sessions;
	}	
	
	function init() {

	   parent::init();
	   if(isset($_GET['day'])) {
	   		Session::set('Day', $_GET['day']);
	   } else {
	   		Session::set('Day', 1);	   	
	   }

	}

	//Show the Presentation detail page using the PresentationCategoryPage_presentation.ss template
	function presentation() 
	{		
		if($Presentation = $this->getPresentationByURLSegment())
		{
			$Data = array(
				'Presentation' => $Presentation
			);
			
			$this->Title = $Presentation->Name;           

			//return our $Data to use on the page
			return $this->Customise($Data);
		}
		else
		{
			//Presentation not found
			return $this->httpError(404, 'Sorry that presentation could not be found');
		}
	}


	// Check to see if the page is being accessed in Chinese
	// We use this in the templates to tell Chinese visitors how to obtain the videos on a non-youtube source
	function ChineseLanguage() {
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		if ($lang == "zh") {
			return TRUE;
		} else {
			return FALSE;
		}
	}


	//Get the current Presentation from the URL, if any
	public function getPresentationByURLSegment()
	{
		$Params = $this->getURLParams();
		$Segment = convert::raw2sql($Params['ID']);
		if($Params['ID'] && $Presentation = Presentation::get()->filter(array('URLSegment' => $Segment, 'PresentationCategoryPageID' => $this->ID))->first())
		{	
			return $Presentation;
		}
	}

	function currentDay()
	{
		$day = Session::get('Day');
		Session::clear('Day');
		// Casting the value as int prevents possible XSS attack
		return (int)$day;
	}

	function updateURLS() {
		$presentations = Presentation::get()->filter('PresentationCategoryPageID', $this->ID)->sort('StartTime','ASC');
		foreach ($presentations as $presentation) {
			if($presentation->URLSegment == NULL) {
				$presentation->write();
			}
		}
		echo "Presentation URLS updated.";
	}		


}