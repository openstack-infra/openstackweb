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
class OpenstackUser extends Page {
   static $db = array(
   	'ListedOnSite' => 'Boolean',
   	'FeaturedOnSite' => 'Boolean',
   	'Objectives' => 'HTMLText',
   	'PullQuote' => 'HTMLText',
   	'PullQuoteAuthor' => 'Varchar(255)',
   	'URL' => 'Varchar(255)',
   	'Industry' => 'Varchar(255)',
   	'Headquarters' => 'Text',
   	'Size' => 'Varchar(255)',
   	"Category" => "Enum('StartupSMB, Enterprise, ServiceProvider, AcademicGovResearch')",
   	"UseCase" => "Enum('Unknown, Saas, TestDev, BigDataAnalytics')"
   );
   static $has_one = array(
   	'Logo' => 'Image'
   );
   static $has_many = array (
   	'Attachments' => 'AttachmentFile',
   	'Photos' => 'AttachmentImage',
   	'Links' => 'Link'
   );
   
   // Many Users Can Have Many Features
   static $many_many = array (
    'Projects' => 'Project'
   );
 
   static $allowed_children = array('UserStoryPage');
   /** static $icon = "icon/path"; */
   	
	public function getCMSFields()
	{
	
		$fields = parent::getCMSFields();
		
	
		//
		// Add in the projects tab with a checklist of projects
		//
			
		// Get all existing projects
		$projects = Project::get();
		
		if (!empty($projects)) {
			// create an arry('ID' => 'Name')
			$map = $projects->map('ID','Name');
						
			// create a Checkbox group based on the array
			$fields->addFieldToTab('Root.Projects',
				new CheckboxSetField(
					$name = 'Projects',
					$title = 'Select Projects',
					$source = $map
			));
		}
		
		//
		// Add a image field for uploading Logo
		$logo = new CustomUploadField('Logo', 'Logo');
		$logo->setAllowedFileCategories('image');
		$logo->setFolderName('logos');
		$fields->addFieldToTab("Root.Main",$logo );
		
		
		// 
		// Add fields for quote and quote author
		//
		$fields->addFieldToTab("Root.Main", new TextAreaField ('PullQuote','Company quote about Openstack'));
		$fields->addFieldToTab("Root.Main", new TextField ('PullQuoteAuthor','Author of the quote'));
		
		//
		// Add in the files tab to upload files
		//
		$fields->addFieldToTab("Root.Files", new GridField('Attachments','Attachments',$this->Attachments()));
		
		//
		// Add in the Photos tab to upload photos
		//
		$imagesTable = new GridField('Photos', 'Photos',$this->Photos());
		$fields->addFieldToTab('Root.Photos',$imagesTable);
		
		
		//
		// Add in the Links tab to set links for the OpenStack User
		//
		$linksTable = new GridField('Links', 'Links',$this->Links());
		$fields->addFieldToTab('Root.Links',$linksTable);
		
		//
		// Hide unneeded tabs and rename the main tab
		//
		$fields->removeFieldsFromTab('Root.Content', 
		   array( 
		      'GoogleSitemap'
		   ) 
		);
		
		$fields->fieldByName('Root.Main')->setTitle('User Details');
		
		//
		// Adjust the fields on the newly renamed User Details tab
		//
		$fields->removeFieldFromTab("Root.Main","MenuTitle");
		$fields->renameField("Title", "Company / Org Name");
		$fields->renameField("Content", "Company / Org Description");
		
		$fields->addFieldToTab('Root.Main',
							 	new CheckboxField('ListedOnSite','Display this company on OpenStack.org'),
							 	'Content');
		
		$fields->addFieldToTab('Root.Main',
							 	new CheckboxField('FeaturedOnSite','Feature this company on the main User Stories page'),
							 	'Content');
		
		$fields->addFieldToTab('Root.Main',
							 	new TextField('URL','Company URL'),
							 	'Content');
							 	
		$fields->addFieldToTab('Root.Main',
							 	new TextField('Headquarters','Company Headquarters (Location)'),
							 	'Content');
		
		$fields->addFieldToTab('Root.Main',
							 	new TextField('Industry'),
							 	'Content');
		
		$fields->addFieldToTab('Root.Main',
							 	new HTMLEditorField('Objectives','Objectives for deploying OpenStack'),
							 	'Content');

		//
		// Add category fields
		//

		$fields->addFieldToTab(
		    "Root.Main",
		    new DropdownField(
		        'Category',
		        'Choose a category', 
		        $this->dbObject('Category')->enumValues()),
		    'Content'
		);

		$fields->addFieldToTab(
		    "Root.Main",
		    new DropdownField(
		        'Use Case',
		        'Choose a use case', 
		        $this->dbObject('UseCase')->enumValues()),
		    'Content'
		);

				
		return $fields;
		
		
	}
}
 
class OpenstackUser_Controller extends Page_Controller {
	
	function init() {
	    parent::init();
	}
}