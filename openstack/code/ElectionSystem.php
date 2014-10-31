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
class ElectionSystem extends Page
{
	static $db = array();

	static $has_one = array(
		'CurrentElection' => 'ElectionPage'
	);

	static $defaults = array(
		"ElectionActive" => TRUE, // Turn on an election by default
	);

	static $allowed_children = array('ElectionPage');

	/** static $icon = "icon/path"; */

	function getCMSFields()
	{
		$fields = parent::getCMSFields();

		$drop = new DropdownField('CurrentElectionID', 'Select An Election', ElectionPage::get()->map('ID','Title'));
		$drop->setEmptyString('No Election');
		$fields->addFieldToTab('Root.CurrentElection', $drop);

		$elections_config = GridFieldConfig_RecordEditor::create();
		$elections = DataList::create('ElectionPage');
		$elections_grid = new GridField(
			'All Elections Grid', // Field name
			'Elections', // Field title
			$elections,
			$elections_config
		);
		$fields->addFieldToTab('Root.CurrentElection', $elections_grid);

		return $fields;
	}

}

class ElectionSystem_Controller extends Page_Controller
{

	function init()
	{
		parent::init();
		// Redirect to current election
		$this->redirect($this->CurrentElection()->Link());
	}

}