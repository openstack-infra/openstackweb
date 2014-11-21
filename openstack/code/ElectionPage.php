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
	class ElectionPage extends Page {
		static $db = array(
	    	'NominationsOpen' => 'Date', // When Individual Member Nominations Start
	    	'NominationsClose' => 'Date', // When Individual Member Nomination CLose
	    	'NominationAppDeadline' => 'Date', // When a candidate must have completed the application in order to be listed
	    	'ElectionsOpen' => 'Date', // The day elections start
	    	'ElectionsClose' => 'Date', // The day they close
	    	'ElectionActive' => 'Boolean' // A manual override. Set to true, the election runs by the dates above. False hides the election from the site.
		);
		static $has_one = array(
	    );
	    static $has_many = array(
	   		'Candidates' => 'Candidate', // Candidates for the current election
	   		'CandidateNominations' => 'CandidateNomination' // Nominations for each candidate
	    );

	    function getCMSFields()
	    {
	        // don't overwrite the main fields
	        $fields = parent::getCMSFields();


	        // Nominations Open
		    $NominationsOpenField = new DateField('NominationsOpen','Date the nominations open (12:00AM this day)');
	    	$NominationsOpenField->setConfig('showcalendar', true);
    		$NominationsOpenField->setConfig('showdropdown', true);
    		$fields->addFieldToTab('Root.Main', $NominationsOpenField, 'Content');
    		$NominationsOpenHeader = new HeaderField('Candidate Nomination Dates');
    		$fields->addFieldToTab('Root.Main', $NominationsOpenHeader, 'NominationsOpen');


	        // Nominations Close
		    $NominationsCloseField = new DateField('NominationsClose','Date the nominations close (11:59PM this day)');
	    	$NominationsCloseField->setConfig('showcalendar', true);
    		$NominationsCloseField->setConfig('showdropdown', true);
    		$fields->addFieldToTab('Root.Main', $NominationsCloseField, 'Content');

	        // Nomination App Deadline
		    $NominationAppDeadlineField = new DateField('NominationAppDeadline','Date candidates must have completed the application in order to be listed (11:59PM this day)');
	    	$NominationAppDeadlineField->setConfig('showcalendar', true);
    		$NominationAppDeadlineField->setConfig('showdropdown', true);
    		$fields->addFieldToTab('Root.Content.Main', $NominationAppDeadlineField, 'Content');

	        // Elections Open
		    $ElectionsOpenField = new DateField('ElectionsOpen','Date the elections open (12:00AM this day)');
	    	$ElectionsOpenField->setConfig('showcalendar', true);
    		$ElectionsOpenField->setConfig('showdropdown', true);
    		$fields->addFieldToTab('Root.Main', $ElectionsOpenField, 'Content');
    		$NominationsOpenHeader = new HeaderField('Election Dates');
    		$fields->addFieldToTab('Root.Main', $NominationsOpenHeader, 'ElectionsOpen');

	        // Elections Close
		    $ElectionsCloseField = new DateField('ElectionsClose','Date the elections close (11:59PM this day)');
	    	$ElectionsCloseField->setConfig('showcalendar', true);
    		$ElectionsCloseField->setConfig('showdropdown', true);
    		$fields->addFieldToTab('Root.Main', $ElectionsCloseField, 'Content');

	        return $fields;
	    }

	    // These are in the model layer to be available to other pages.

	    // Return the nominations made for the logged-in user
		public function NominationsForCurrentMember() {
			$CurrentMemberID = Member::currentUser()->ID;
			return $this->CandidateNominations('CandidateID = '.$CurrentMemberID);
		}

	    // Return the nominations made for the logged-in user
		public function NominationsByCurrentMember() {
			$CurrentMemberID = Member::currentUser()->ID;
			return $this->CandidateNominations('MemberID = '.$CurrentMemberID);
		}


	    // Used to determine plural wording (0 times, 1 time, 2 times, etc.)
		public function PluralNominations() {
			$CurrentMemberID = Member::currentUser()->ID;
			return $this->CandidateNominations('CandidateID = '.$CurrentMemberID)->Count() <> 1;
		}

		// Find the current user and see if they've accepted a candidate nomination
		public function CurrentMemberHasAcceoted() {
			$CurrentUserCandidate = Candidate::get()->filter(array('ElectionID' => $this->ID,'MemberID' => Member::currentUser()->ID))->first();
			if ($CurrentUserCandidate) return $CurrentUserCandidate->HasAcceptedNomination;
		}


		// Return whether the nominations are open by looking at the dates provided
		function NominationsAreOpen() {
			$now = SS_Datetime::now()->value;
			$NominationsClose = strtotime(date("Y-m-d", strtotime($this->NominationsClose)) . " +1 day"); # used to keep time of day from being a factor
			return strtotime($this->NominationsOpen) <= strtotime($now) && strtotime($now) <= $NominationsClose && $this->ElectionActive = TRUE;
		}

		// Return whether the election is currently running by looking at the dates provided
		function ElectionIsActive() {
			$now = SS_Datetime::now()->value;
			$ElectionsClose = strtotime(date("Y-m-d", strtotime($this->ElectionsClose)) . " +1 day"); # used to keep time of day from being a factor
			return  strtotime($this->ElectionsOpen) <= strtotime($now) && strtotime($now) <= $ElectionsClose && $this->ElectionActive = TRUE;
		}

		// An election is 'open' if nominations are open or the election is actively being held
		public function IsOpen() {
			return $this->NominationsAreOpen() || $this->ElectionIsActive();
		}

		// Returns candidates for this election that have accepted the nomination
		function AcceptedCandidatesList() {
			return $this->Candidates("HasAcceptedNomination = TRUE");
		}

		// Returns candidates for this election that have accepted the nomination
		function GoldCandidatesList() {
			return $this->Candidates("IsGoldMemberCandidate = TRUE");
		}

	}

	class ElectionPage_Controller extends Page_Controller {

		function init() {
			parent::init();
		}

		static $allowed_actions = array(
			'CandidateList',
			'CandidateListGold'
		);

	}
