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
class MemberListPage extends Page
{
	static $db = array();
	static $has_one = array();
	static $has_many = array();
}

class MemberListPage_Controller extends Page_Controller
{

	function init()
	{
		parent::init();

		//CSS
		Requirements::css("themes/openstack/css/jquery.autocomplete.css");

		Requirements::javascript("themes/openstack/javascript/jquery.autocomplete.min.js");
		Requirements::CustomScript("
							
					jQuery(function(){

					  $('#SearchForm_MemberSearchForm_mq').autocomplete('" . $this->Link('results') . "', {
					        minChars: 3,
					        selectFirst: true,
					        autoFill: true,
					   });

						$('#SearchForm_MemberSearchForm_mq').focus();

					});						
					
			
			");

		// If we're in HTTPS mode, reset to HTTP for this page
		if (isset($_SERVER['HTTPS'])) {
			$destURL = str_replace('https:', 'http:', Director::absoluteURL($_SERVER['REQUEST_URI']));

			// check to make sure we're not already there to prevent redirect loop
			if ($destURL != Director::absoluteURL($_SERVER['REQUEST_URI'])) {
				$this->redirect($destURL);
			}
		}

	}

	static $allowed_actions = array(
		'confirmNomination',
		'CurrentElection',
		'saveNomination',
		'profile',
		'results',
		'MemberSearchForm',
		'candidateStats',
		'CandidateApplication',
		'idList' => 'ADMIN',
		'assignGroup' => 'ADMIN',
		'ListExport',
		'CSVExport' => 'ADMIN'
	);

	function MemberList()
	{

		if (isset($_GET['letter'])) {

			$requestedLetter = Convert::raw2xml($_GET['letter']);

			if ($requestedLetter == 'intl') {
				$likeString = "NOT Surname REGEXP '[A-Za-z0-9]'";
			} elseif (ctype_alpha($requestedLetter)) {
				$likeString = "Surname LIKE '" . substr($requestedLetter, 0, 1) . "%'";
			} else {
				$likeString = "Surname LIKE 'a%'";
			}

		} else {
			$likeString = "Surname LIKE 'a%'";
		}


		$list = Member::get()
			->where("Group_Members.GroupID = 5 AND " . $likeString)
			->leftJoin('Group_Members', 'Member.ID = Group_Members.MemberID')
			->sort('Surname');
		return GroupedList::create($list);
	}


	// Return the currently running election
	// This simple function primarily exists to be used in the template
	function CurrentElection()
	{
		// Load the election system
		$Elections = ElectionSystem::get()->first();
		if ($Elections && $Elections->CurrentElectionID != 0) return $Elections->CurrentElection();
	}

	function alreadyNominated($candidateID, $CurrentElection)
	{

		$memberID = Member::currentUserID();
		$NominationsForThisCandidate = $CurrentElection->CandidateNominations("`MemberID` = " . $memberID . " AND `CandidateID` = " . $candidateID);

		if ($NominationsForThisCandidate->Count() >= 1) {
			return TRUE;
		}
	}

	function findMember($CandidateID)
	{
		$Candidate = Member::get()->byID($CandidateID);
		// Check to make sure they are in the foundation membership group
		If ($Candidate && $Candidate->inGroup(5, TRUE)) return $Candidate;
	}

	function confirmNomination()
	{

		$results = array();

		$results["Success"] = FALSE;

		// Grab candidate ID from the URL
		$CandidateID = $this->request->param("ID");

		// Check to see if the candidate ID is numeric and if the person is logged in
		if ($this->validateNomation($CandidateID) == 'VALID') {

			$Nominee = Member::get()->filter(array('ID' => $CandidateID));
			$results["Success"] = TRUE;
			$results["Candidate"] = $Nominee;
			$results["NominateLink"] = $this->Link() . "saveNomination/" . $CandidateID;
			$results["BackLink"] = $this->Link() . "profile/" . $CandidateID;

		} elseif ($this->validateNomation($CandidateID) == 'ALREADY NOMINATED') {

			$Nominee = Member::get()->filter(array('ID' => $CandidateID));

			$CurrentElection = $this->CurrentElection();

			$results["Election"] = $CurrentElection;
			$results["Success"] = FALSE;
			$results["NominatedByMe"] = TRUE;
			$results["Candidate"] = $Nominee;
			$results["BackLink"] = $this->Link() . "profile/" . $CandidateID;


		} elseif ($this->validateNomation($CandidateID) == 'LIMIT EXCEEDED') {

			$Nominee = Member::get()->filter(array('ID' => $CandidateID));

			$results["Success"] = FALSE;
			$results["LimitExceeded"] = TRUE;
			$results["Candidate"] = $Nominee;
			$results["BackLink"] = $this->Link() . "profile/" . $CandidateID;


		} else {
			$results["Success"] = FALSE;
			$results["BackLink"] = $this->Link() . "profile/" . $CandidateID;

		}


		return $results;

	}


	// Checks whether a nomination is valid:
	// Requires there to be a current election, the member to be logged in, and the ID of a member that hasn't been nominated yet.
	function validateNomation($CandidateID)
	{

		$CurrentElection = $this->CurrentElection();

		// Look for nominations for this candidate
		$CandidateNominations = CandidateNomination::get()->where("CandidateID = " . $CandidateID . " AND ElectionID = " . $CurrentElection->ID);
		$NumberOfNominations = 0;
		if ($CandidateNominations) $NumberOfNominations = $CandidateNominations->Count();



		// 1. Check to see if there's a current, active election
		if (!$CurrentElection || !$CurrentElection->NominationsAreOpen()) {
			return 'NO ACTIVE NOMINATIONS';
		}

		// 2. Check to see if a member is logged in
		if (!Member::currentUserID()) {
			return 'NOT LOGGED IN';
		}

		// 3. Check to make sure there's a valid Candidate ID
		if (!is_numeric($CandidateID) || !$this->findMember($CandidateID)) {
			return 'INVALID CANDIDATE';
		}

		// 4. Check to see if the member has already nominated this person
		if ($this->alreadyNominated($CandidateID, $CurrentElection)) {
			return 'ALREADY NOMINATED';
		}

		// 5. Check to see if this person already has 10 nominations
		if ($NumberOfNominations >= 10) {
			return 'LIMIT EXCEEDED';
		}

		// 6. Make sure that the person nominating is a foundation member
		$CurrentMember = Member::currentUser();
		If (!$CurrentMember->inGroup(5, TRUE)) {
			return 'INVALID VOTER';
		}

		// If all of the above tests pass, this is a valid nomination
		return 'VALID';

	}


	function saveNomination()
	{
		$CandidateID = $this->request->param("OtherID");
		$NominationStatus = $this->validateNomation($CandidateID);

		// Check to see if this is a valid nomination
		if ($NominationStatus == 'VALID') {

			// Grab the currently logged in member
			$currentMember = Member::currentUser();

			$CurrentElection = $this->CurrentElection();

			// Record the nomination
			$CandidateNomination = new CandidateNomination();
			$CandidateNomination->MemberID = Member::currentUserID();
			$CandidateNomination->CandidateID = $CandidateID;
			$CandidateNomination->ElectionID = $CurrentElection->ID;
			$CandidateNomination->write();

			// Create a candidate record for the nominations page if one does not exist
			$Candidate = Candidate::get()->filter(array('MemberID'=>$CandidateID,'ElectionID'=>$CurrentElection->ID))->first();
			if (!$Candidate) {

				// Create a new candidate entry
				$Candidate = new Candidate();

				$Candidate->MemberID = $CandidateID;
				$Candidate->ElectionID = $CurrentElection->ID;

				$Candidate->write();


			}

			// Log this new candidate
			$logLine = $currentMember->FirstName . " " . $currentMember->Surname . " nominated " . $Candidate->Member()->FirstName . " " . $Candidate->Member()->Surname . " (ID " . $Candidate->Member()->ID . ") on " . $CandidateNomination->Created . " \n";
			$file = fopen(ASSETS_PATH . '/candidate-nomination-log.txt', 'a');
			fwrite($file, $logLine);
			fclose($file);

			// Email the member

			// In dev and testing, send the nomination emails to the person who did the nomination
			$To = $currentMember->Email;

			// In live mode, send the email to the candidate
			if (Director::isLive()) $To = $Candidate->Member()->Email;

			$Subject = "You have been nominated in the " . $CurrentElection->Title;
			$email = EmailFactory::getInstance()->buildEmail(CANDIDATE_NOMINATION_FROM_EMAIL, $To, $Subject);
			$email->setTemplate('NominationEmail');

			// Gather Data to send to template
			$data["Candidate"] = $Candidate;
			$data["Election"] = $CurrentElection;


			$email->populateTemplate($data);
			$email->send();


			$this->setMessage('Success', "You've just nominated " . $Candidate->Member()->FirstName . ' for the OpenStack Board.');
			$this->redirect('/community/members/candidateStats/' . $Candidate->Member()->ID);


		} elseif ($NominationStatus = 'ALREADY NOMINATED') {

			$this->setMessage('Error', "Oops, you have already nominated this person.");
			$this->redirect($this->Link());

		} elseif ($NominationStatus = 'INVALID CANDIDATE') {
			$this->setMessage('Error', "Oops, no candidate was selected.");
			$this->redirect($this->Link());
		} else {
			$this->setMessage('Error', "There was an error logging your nomination.");
			$this->redirect($this->Link());
		}
	}

	//Show the profile of the member using the MemberListPage_profile.ss template
	function profile()
	{

		// Grab member ID from the URL
		$MemberID = Convert::raw2sql($this->request->param("ID"));

		// Check to see if the ID is numeric
		if (is_numeric($MemberID)) {

			// Check to make sure there's a member with the current id
			if ($Profile = $this->findMember($MemberID)) {

				$CurrentElection = $this->CurrentElection();

				if ($CurrentElection) {
					$Candidate = Candidate::get()->filter(array('MemberID'=>$MemberID,'ElectionID'=>$CurrentElection->ID))->first();
					$data["Candidate"] = $Candidate;
					$data["CurrentElection"] = $CurrentElection;
				}

				$data["Profile"] = $Profile;

				// A member is looking at own profile
				if (Member::currentUserID() == $MemberID) $data["OwnProfile"] = TRUE;

				//return our $Data to use on the page
				return $this->Customise($data);
			}
		}
		return $this->httpError(404, 'Sorry that member could not be found');
	}

	function candidateStats()
	{

		// Grab candidate ID from the URL
		$CandidateID = $this->request->param("OtherID");

		// Check to see if the candidate is valid
		if (is_numeric($CandidateID) && $this->findMember($CandidateID)) {

			$CurrentElection = $this->CurrentElection();
			$Candidate = $CurrentElection->Candidates("MemberID = " . $CandidateID);

			$results["Success"] = TRUE;
			$results["Candidate"] = $Candidate;

			return $results;

		} else {

			//Member not found
			return $this->httpError(404, 'Sorry that candidate could not be found');
		}
	}

	public function MemberSearchForm()
	{
		$fields = new FieldList(
			new TextField('mq', 'Search Member name', $this->getSearchQuery())
		);

		$form = new SearchForm($this, 'MemberSearchForm', $fields);

		$form->setFormAction($this->Link('results'));

		return $form;
	}

	public function results()
	{
		if ($query = $this->getSearchQuery()) {

			// Search for only foundation members (Group 5) against the query.

			$filter = "(MATCH (FirstName, Surname) AGAINST ('{$query}')
					OR FirstName LIKE '%{$query}%'
					OR Surname LIKE '%{$query}%') AND Group_Members.GroupID=5";

			$Results = Member::get()
				->where($filter)
				->leftJoin("Group_Members", "Member.ID = Group_Members.MemberID");
			// No Member was found
			if (!isset($Results)) {
				$this->setMessage("Error", "No member by that name could be found.");
				$this->redirectBack();
				return;
			}

			// For AutoComplete
			if (Director::is_ajax()) {

				$Members = $Results->map('ID', 'Name');
				$Suggestions = '';

				foreach ($Members as $Member) {
					$Suggestions = $Suggestions . $Member . '|' . '1' . "\n";
				}

				return $Suggestions;
			} // For Results Template
			else {


				$filter = "(CONCAT(FirstName, ' ', Surname) LIKE '%$query%') AND Group_Members.GroupID=5";

				$OneMember = Member::get()
					->where($filter)
					->leftJoin("Group_Members", "Member.ID = Group_Members.MemberID");
				// see if one member exactly matches the search term

				if ($OneMember) {
					$Results = $OneMember;
				}

				// If there is only one person with this name, go straight to the resulting profile page
				if ($OneMember && $OneMember->Count() == 1) {
					$this->redirect($this->Link() . 'profile/' . $OneMember->First()->ID);
				}

				$Output = new ArrayData(array(
					'Title' => 'Results',
					'Results' => $Results
				));

				return $this->customise($Output);
			}
		}

		$this->redirect($this->Link());
	}

	function getSearchQuery()
	{
		if ($this->request){
			$query = $this->request->getVar("mq");
			if(!empty($query))
				return Convert::raw2sql($query);
			return false;
		}
	}

	private function GetMembersInDateRange($StartTime = NULL, $EndTime = NULL)
	{

		$DateRange = "";

		if ($StartTime && $EndTime) {
			$DateRange = " AND (LastEdited BETWEEN '" . $StartTime . "' AND '" . $EndTime . "')";

		} elseif ($EndTime) {
			$DateRange = " AND (LastEdited <= '" . $EndTime . "')";

		} elseif ($StartTime) {
			$DateRange = " AND (LastEdited >= '" . $StartTime . "')";

		} else {
			$DateRange = "";
		}

		// Pull Members using a custom db query. This returns a MySQLQuery object
		$MemberList = DB::query("

				SELECT Member.ID, `FirstName`, `Surname`, `IRCHandle`, `TwitterName`, `Email`, `SecondEmail`, `ThirdEmail`
				FROM `Member`
				LEFT JOIN `Group_Members` ON `Member`.`ID` = `Group_Members`.`MemberID`
				WHERE `Group_Members`.`GroupID`= 5" . $DateRange

		);


		return $MemberList;

	}

	function ListExport()
	{

		if (isset($_GET['token']) && $_GET['token'] == "fcv4x7Nl8v") {

			// Check URL parameters for start and end times
			$Start = isset($_GET['start']) ? $_GET['start'] : NULL;
			$End = isset($_GET['end']) ? $_GET['end'] : NULL;

			$StartTime = $Start ? date("Y-m-d H:i:s", strtotime($Start)) : NULL;
			$EndTime = $End ? date("Y-m-d H:i:s", strtotime($End)) : NULL;


			$MemberList = $this->GetMembersInDateRange($StartTime, $EndTime);


			$results = array();


			// Transform the MySQLQuery object created above into an ArrayData object

			if ($MemberList) {
				foreach ($MemberList as $Member) {

					$dbMember = Member::get()->byID($Member['ID']);

					if (!is_null($dbMember)) {

						$AffiliationList = $dbMember->OrderedAffiliations();

						// If there are Affiliation updates, push a new copy of the member to the results array filled in with the org and date from the update
						if ($AffiliationList && $AffiliationList->Count() > 0) {

							foreach ($AffiliationList as $a) {
								$currentMemberOrg = $a->Organization();
								$Member['OrgAffiliations'] = $currentMemberOrg->Name;
								if ($a->Current)
									$Member['untilDate'] = NULL;
								else
									$Member['untilDate'] = $a->EndDate;

								// Push the member to the results
								array_push($results, $Member);
							}
						} else {
							//no affiliations
							$Member['OrgAffiliations'] = NULL;
							$Member['untilDate'] = NULL;
							array_push($results, $Member);
						}
					}

				}
			}


			// Finally, convert the array from the ArrayData object to JSON
			$json = Convert::Array2JSON($results);
			return $json;
		}

	}

	function FullMemberList(){
		return Member::get()->leftJoin('Group_Members','`Member`.`ID` = `Group_Members`.`MemberID` AND Group_Members.GroupID=5');
	}
}
