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
 * Defines the PDF page type
 */
class PdfPage extends Page {
   static $db = array(
    	'Sidebar' => 'HTMLText',
    	'SubTitle' => 'Text'
	);
   static $has_one = array(
   );
   static $icon = "themes/tutorial/images/treeicons/news";
   
 	function getCMSFields() {
    	$fields = parent::getCMSFields();
    	
    	// create a couple of extra fields
    	$fields->addFieldToTab('Root.Main', new TextField('SubTitle','Subtitle (tagline right below the title)'), 'Content');
    	$fields->addFieldToTab('Root.Main', new HtmlEditorField('Sidebar','Right Sidebar Content'), '');
    	
    	// remove unneeded fields 
    	$fields->removeFieldFromTab("Root.Main","MenuTitle");

    	return $fields;
 	}   
}
 
class PdfPage_Controller extends Page_Controller {
 
}