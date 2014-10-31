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
 * Used to view, edit, and categorize summit presentations
 * Designed for admin use only
 */
class PresentationEditorPage extends Page
{
	static $db = array();
	static $has_one = array();
	static $defaults = array(
		'ShowInMenus' => false
	);
}

class PresentationEditorPage_Controller extends Page_Controller
{

	public static $allowed_actions = array(
		'Show',
		'SetMainTopic',
		'Category',
		'Delete',
		'Restore',
		'Next',
		'Previous',
		'SearchForm',
		'FlagForm',
		'CleanTalks',
		'CleanSpeakers',
		'EmailSpeakers',
		'EmailSubmitters',
		'SpeakersWithoutEmail' => 'ADMIN',
		'TalkStatus' => 'ADMIN',
		'SendAdminEmails' => 'ADMIN',
		'SendSpeakerEmails' => 'ADMIN',
		'SpeakerList' => 'ADMIN',
		'AdjustSpeakers' => 'ADMIN',
		'SpeakersForSched' => 'ADMIN',
		'ScheduleForSched' => 'ADMIN',
		'SpeakerCleanup' => 'ADMIN',
		'AcceptedSpeakersWithoutEmails' => 'ADMIN',
		'AcceptedSpeakersNotContacted' => 'ADMIN',
		'AcceptedSpeakersNotConfirmed' => 'ADMIN',
		'AcceptedTalksWithoutSpeakers' => 'ADMIN',
		'FullSpeakerReport' => 'ADMIN',
		'FullTalksReport' => 'ADMIN',
		'FullTalksReportSpeakerDetails' => 'ADMIN',
		'SendFirstEmail' => 'ADMIN',
		'SpeakersWithTalksInSummit' => 'ADMIN',
		'SpeakerVerify' => 'ADMIN',
		'SpeakersWithUnassignedTalks' => 'ADMIN',
		'SpeakerCompanyReport' => 'ADMIN',
		'SpeakerSpreadsheetExport' => 'ADMIN',
		'ExportTalks' => 'ADMIN',
		'SetSpeakerOrgs' => 'ADMIN'
	);

	function init()
	{
		if (!Permission::check("ADMIN")) Security::permissionFailure();

		parent::init();
	}

	function Show()
	{

		// Grab member ID from the URL
		$Talk = $this->findTalk();


		if ($Talk) {

			if ($CategoryID = Session::get('CategoryID')) {
				$Talks = $this->PresentationsByCategory($CategoryID);
				if ($Talks) $data["Presentations"] = True;
				$data["PresentationList"] = $Talks;
			} else {
				$Talks = $this->PresentationList();
				if ($Talks) $data["Presentations"] = True;
				$data["PresentationList"] = $Talks;
			}

			$data["Presentation"] = $Talk;

			Session::set('TalkID', $Talk->ID);

			//return our $Data to use on the page
			return $this->Customise($data);
		} else {
			//Talk not found
			return $this->httpError(404, 'Sorry that talk could not be found');
		}

	}

	function FindTalk()
	{

		$TalkId = NULL;

		// Grab member ID from the URL or the session
		if ($this->request->param("ID") != NULL && $this->request->param("Action") == 'Show') {
			$TalkId = Convert::raw2sql($this->request->param("ID"));
		} elseif (Session::get('TalkID') != NULL) {
			$TalkId = Session::get('TalkID');
		}

		// Check to see if the ID is numeric
		if (is_numeric($TalkId)) {
			Session::set('TalkID', $TalkId);
			return $Talk = Talk::get()->byID($TalkId);
		} else {
			return $Talk = $this->PresentationList()->first();
		}

	}

	// Find a talk given an id

	function PresentationList()
	{
		return Talk::get()->filter(array('MarkedToDelete' => 0))->sort('PresentationTitle', 'ASC');
	}


	//Show the details of a talk

	function PresentationsByCategory($CategoryID)
	{

		if ((1 <= $CategoryID) && ($CategoryID <= 10)) {

			$Categories = $this->CategoryList();
			$CategoryName = $Categories[$CategoryID - 1]['Name'];

			$Talks = Talk::get()->filter(array('MainTopic' => $CategoryName, 'MarkedToDelete' => 0))->sort('PresentationTitle', 'ASC');

		} elseif ($CategoryID == 'All') {

			$Talks = Talk::get()->filter(array('MarkedToDelete' => 0))->sort('PresentationTitle', 'ASC');

		} elseif ($CategoryID == 'Deleted') {

			$Talks = Talk::get()->filter(array('MarkedToDelete' => 1))->sort('PresentationTitle', 'ASC');

		} elseif ($CategoryID == 'None') {

			$Talks = Talk::get()->filter(array('MarkedToDelete' => 0))->where('MainTopic IS NULL')->sort('PresentationTitle', 'ASC');
		}

		return $Talks;

	}

	function CategoryList()
	{
		return array(
			array('Name' => 'Getting Started', 'Number' => '1'),
			array('Name' => 'Operations', 'Number' => '2'),
			array('Name' => 'Related OSS Projects', 'Number' => '3'),
			array('Name' => 'Workshops', 'Number' => '4'),
			array('Name' => 'Apps on OpenStack', 'Number' => '5'),
			array('Name' => 'Strategy', 'Number' => '6'),
			array('Name' => 'Products & Services', 'Number' => '7'),
			array('Name' => 'Techincal Deep Dive', 'Number' => '8'),
			array('Name' => 'Community Building', 'Number' => '9'),
			array('Name' => 'Growing An OpenStack Business', 'Number' => '10'),
			array('Name' => 'General Session', 'Number' => '11')
		);
	}

	function Category()
	{

		$CategoryID = Convert::raw2sql($this->request->param("ID"));
		Session::set('CategoryID', $CategoryID);

		$Talks = $this->PresentationsByCategory($CategoryID);


		if ($Talks) {
			$Talk = $Talks->first();
			$data["Presentation"] = $Talk;
			Session::set('TalkID', $Talk->ID);
			$data["Presentations"] = True;
			$data["PresentationList"] = $Talks;
		} else {
			$data["Presentations"] = FALSE;
			$Talk = $this->findTalk();
		}

		return $this->Customise($data);

	}

	//Used to list presentations from a specific category

	function NumTalksWithNoCategory()
	{
		return $this->CountCategoryMembers('No Category Assigned');
	}

	function CountCategoryMembers($Category)
	{

		if ($Category == 'No Category Assigned') {
			$TalksInCategory = Talk::get()->where("MainTopic IS NULL");
		} else {
			$TalksInCategory = Talk::get()->filter('MainTopic', $Category);
		}
		if ($TalksInCategory) {
			return $TalksInCategory->count();
		} else {
			return 0;
		}

	}

	function CategoryButtons()
	{

		$Talk = $this->findTalk();

		$items = new ArrayList();

		$Categories = $this->CategoryList();

		foreach ($Categories as $Category) {

			$Category['Count'] = $this->CountCategoryMembers($Category['Name']);

			if (stripos($Talk->Topic, $Category['Name']) !== false) $Category['Class'] = 'user-selected';
			if (stripos($Talk->MainTopic, $Category['Name']) !== false) $Category['Class'] = 'main-topic';

			$items->push(new ArrayData($Category));
		}

		return $items;

	}


	// Render category buttons

	function SetMainTopic()
	{

		// Grab IDs from the URL
		$TopicID = Convert::raw2sql($this->request->param("OtherID"));
		$TalkID = Convert::raw2sql($this->request->param("ID"));

		$Categories = $this->CategoryList();

		if ((1 <= $TopicID) && ($TopicID <= 11)) {
			$CategoryName = $Categories[$TopicID - 1]['Name'];
			$Talk = Talk::get()->byID($TalkID);
			if ($Talk) {
				if ($Talk->MainTopic != $CategoryName) {
					$Talk->MainTopic = $CategoryName;

					$CategorySelected = SummitCategory::get()->filter('Name', $CategoryName)->first();
					if ($CategorySelected) $Talk->SummitCategoryID = $CategorySelected->ID;

					$SummitSelectedTalkList = SummitSelectedTalkList::get()->filter('SummitCategoryID', $Talk->SummitCategoryID)->first();
					// if a summit talk list doens't exist for this category, create it
					if (!$SummitSelectedTalkList) {
						$SummitSelectedTalkList = new SummitSelectedTalkList();
						$SummitSelectedTalkList->SummitCategoryID = $Talk->SummitCategoryID;
						$SummitSelectedTalkList->write();
					}

					$SelectionsToRemove = SummitSelectedTalk::get()->filter('TalkID', $Talk->ID);

					// start reassigning by removing any current selections
					if ($SelectionsToRemove) {
						foreach ($SelectionsToRemove as $Selection) {
							$Selection->delete();
						}

						// Create a new selection in the new category if needed
						$SelectedTalk = new SummitSelectedTalk();
						$SelectedTalk->SummitSelectedTalkListID = $SummitSelectedTalkList->ID;
						$SelectedTalk->TalkID = $Talk->ID;
						$SelectedTalk->MemberID = Member::CurrentUser()->ID;
						$SelectedTalk->write();

					}

				}

				$Talk->write();
			}
		}
		$this->redirectBack();
	}

	function Delete()
	{
		$TalkID = Convert::raw2sql($this->request->param("ID"));
		if (is_numeric($TalkID)) {
			$Talk = Talk::get()->byID($TalkID);
			$Talk->MarkedToDelete = TRUE;
			$Talk->write();
			$this->Next();
		}

	}

	function Next()
	{

		$Talks = $this->TalksForNav('next');

		if ($Talks) {
			$NextTalk = $Talks->first();
			$this->redirect($this->Link() . 'Show/' . $NextTalk->ID);
		} else {
			// Last talk
			$this->redirectBack();
		}
	}

	function TalksForNav($PrevOrNext)
	{

		if ($PrevOrNext == 'next') $Order = '>';
		if ($PrevOrNext == 'prev') $Order = '<';

		if ($PrevOrNext == 'next') $Sort = 'ASC';
		if ($PrevOrNext == 'prev') $Sort = 'DESC';

		// Check to see if we're in a category
		// If so, we'll add that to our SQL query to filter the results
		$Category = $this->CurrentCategory();

		$TalkID = Session::get('TalkID');
		$Talk = Talk::get()->byID($TalkID);
		$Title = Convert::raw2sql($Talk->PresentationTitle);

		if ($Category && $Category != 'All Categories' && $Category != 'No Category Assigned' && $Category != 'Deleted') {
			$WhereClause = "PresentationTitle " . $Order . " '" . $Title . "' AND `MainTopic` = '" . $Category . "' AND `MarkedToDelete` = FALSE";
		} else {
			$WhereClause = "PresentationTitle " . $Order . " '" . $Title . "' AND `MarkedToDelete` = FALSE";
		}

		if ($Category == 'Deleted') {
			$WhereClause = "PresentationTitle " . $Order . " '" . $Title . "' AND `MarkedToDelete` = TRUE";
		}

		if ($Category == 'No Category Assigned') {
			$WhereClause = "PresentationTitle " . $Order . " '" . $Title . "' AND `MainTopic` IS NULL AND `MarkedToDelete` = FALSE";
		}

		$Talks = Talk::get()->where($WhereClause)->sort('PresentationTitle', $Sort);

		return $Talks;

	}

	function CurrentCategory()
	{

		$CategoryID = Session::get('CategoryID');

		if ((1 <= $CategoryID) && ($CategoryID <= 10)) {

			$Categories = $this->CategoryList();
			return $Categories[$CategoryID - 1]['Name'];

		} elseif ($CategoryID == 'Deleted') {

			return 'Deleted Presentations';

		} elseif ($CategoryID == 'None') {

			return 'No Category Assigned';

		} else {
			return 'All Categories';
		}
	}

	function Restore()
	{
		$TalkID = Convert::raw2sql($this->request->param("ID"));
		if (is_numeric($TalkID)) {
			$Talk = Talk::get()->byID($TalkID);
			$Talk->MarkedToDelete = FALSE;
			$Talk->write();
			$this->redirectBack();
		}

	}

	function Previous()
	{

		$Talks = $this->TalksForNav('prev');

		if ($Talks) {
			$NextTalk = $Talks->first();
			$this->redirect($this->Link() . 'Show/' . $NextTalk->ID);
		} else {
			// First talk
			$this->redirectBack();
		}
	}

	function SearchForm()
	{
		$SearchForm = new PresentationSearchForm($this, 'SearchForm');
		$SearchForm->disableSecurityToken();
		return $SearchForm;
	}

	function doSearch($data, $form)
	{

		$Talks = NULL;

		if ($data['Search']) {
			$query = Convert::raw2sql($data['Search']);
			$Talks = Talk::get()->filter('PresentationTitle:PartialMatch', $query);
		}

		if ($Talks) $data["Presentations"] = True;
		$data["PresentationList"] = $Talks;

		$Talk = $this->findTalk();
		$data["Presentation"] = $Talk;
		return $this->Customise($data);

	}

	function FlagForm()
	{
		$FlagForm = new PresentationFlagForm($this, 'FlagForm');
		$FlagForm->disableSecurityToken();
		$Talk = $this->findTalk();
		if ($Talk) $FlagForm->loadDataFrom($Talk->data());
		return $FlagForm;
	}


	function doFlag($data, $form)
	{
		$Talk = $this->findTalk();
		if ($data['FlagComment']) {
			$Talk->FlagComment = $data['FlagComment'];
		} else {
			$Talk->FlagComment = NULL;
		}
		$Talk->write();
		$this->redirectBack();
	}

	function CleanTalks()
	{
		// Set up HTML Purifier
		$config = HTMLPurifier_Config::createDefault();

		// Remove any CSS or inline styles
		$config->set('CSS.AllowedProperties', array());
		$config->set('HTML.AllowedAttributes', 'a.href,img.src');
		$config->set('HTML.Allowed', 'br,b,strong,li,ol,ul,a,p,img');
		$purifier = new HTMLPurifier($config);

		$Talks = Talk::get();

		foreach ($Talks as $Talk) {
			if ($Talk->Abstract) {
				$Talk->Abstract = $purifier->purify($Talk->Abstract);
				$Talk->write();
			}
		}
	}

	function CleanSpeakers()
	{
		// Set up HTML Purifier
		$config = HTMLPurifier_Config::createDefault();

		// Remove any CSS or inline styles
		$config->set('CSS.AllowedProperties', array());
		$config->set('HTML.AllowedAttributes', 'a.href,img.src');
		$config->set('HTML.Allowed', 'br,b,strong,li,ol,ul,a,p,img');
		$purifier = new HTMLPurifier($config);

		$Speakers = Speaker::get();

		foreach ($Speakers as $Speaker) {
			if ($Speaker->Bio) {
				$Speaker->Bio = $purifier->purify($Speaker->Bio);
				$Speaker->write();
			}
		}
	}

	public function EmailSubmitters()
	{

		$Talks = Talk::get()->filter(array('BeenEmailed' => 0, 'MarkedToDelete' => 0))->limit(10);

		if (!$Talks->count()) {
			echo 'no talks match criteria.';
			return;
		}


		foreach ($Talks as $Talk) {

			echo '<br/> Woring on talkID ' . $Talk->ID . '<br/>';

			//Send email about submission

			$To = $Talk->Owner()->Email;

			$CurrentSpeaker = $Talk->Speakers('MemberID = ' . $Talk->OwnerID . ' AND TalkID = ' . $Talk->ID);

			// Email the admin if :
			// the admin is not a speaker,
			// or if there is more than one speaker,
			// or if there are any speakers wihtout email accounts

			if (!$CurrentSpeaker || $Talk->Speakers()->count() > 1 || $this->SpeakerWithoutEmail($Talk)) {

				if (!$CurrentSpeaker) echo 'Not one of the speakers. <br/>';
				if ($Talk->Speakers()->count() > 1) echo 'More than one speaker. <br/>';

				$Subject = "OpenStack Summit Presentation Voting";

				$email = EmailFactory::getInstance()->buildEmail(OS_SUMMIT_PRESENTATION_VOTING_FROM_EMAIL, $To, $Subject);
				$email->setTemplate("VotingLiveToAdmins");
				$email->populateTemplate($Talk);
				$email->send();

				echo 'Email sent to ' . $Talk->Owner()->Email . '<br/>';
			}

			$Talk->BeenEmailed = TRUE;
			$Talk->write();

		}
	}

	public function EmailSpeakers()
	{


		$Talks = Talk::get()->filter(array('MarkedToDelete' => 0, 'BeenEmailed' => 0))->sort('PresentationTitle')->limit(50);

		if (!$Talks->count()) {
			echo 'no talks match criteria.';
			return;
		}

		foreach ($Talks as $Talk) {

			//Send email about submission

			$Subject = "Your OpenStack Summit Speaking Submission";


			if ($Talk->HasSpeaker()) {

				foreach ($Talk->Speakers() as $Speaker) {

					if ($Speaker->Member()->Email && $this->validEmail($Speaker->Member()->Email)) {

						$To = $Speaker->Member()->Email;
						$email = EmailFactory::getInstance()->buildEmail(OS_SUMMIT_SPEAKING_SUBMISSION_FROM_EMAIL, $To, $Subject);
						$email->setTemplate("VotingAnnounceEmail");
						$email->populateTemplate($Talk);
						$email->send();

						echo 'Email sent to ' . $Speaker->Member()->Email . '<br/>';
					}

				}

				$Talk->BeenEmailed = TRUE;
				$Talk->write();

			} else {

				if ($Talk->Owner()->Email && $this->validEmail($Talk->Owner()->Email)) {
					$To = $Talk->Owner()->Email;
					$email = EmailFactory::getInstance()->buildEmail(OS_SUMMIT_SPEAKING_SUBMISSION_FROM_EMAIL, $To, $Subject);
					$email->setTemplate("VotingAnnounceEmail");
					$email->populateTemplate($Talk);
					$email->send();

					$Talk->BeenEmailed = TRUE;
					$Talk->write();

					echo 'Email sent to ' . $Talk->Owner()->Email . '<br/>';
				}

			}


		}
	}

	/**
	 * Validate an email address.
	 * Provide email address (raw input)
	 * Returns true if the email address has the email
	 * address format and the domain exists.
	 */
	function validEmail($email)
	{
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex) {
			$isValid = false;
		} else {
			$domain = substr($email, $atIndex + 1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64) {
				// local part length exceeded
				$isValid = false;
			} else if ($domainLen < 1 || $domainLen > 255) {
				// domain part length exceeded
				$isValid = false;
			} else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
				// local part starts or ends with '.'
				$isValid = false;
			} else if (preg_match('/\\.\\./', $local)) {
				// local part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
				// character not valid in domain part
				$isValid = false;
			} else if (preg_match('/\\.\\./', $domain)) {
				// domain part has two consecutive dots
				$isValid = false;
			} else if
			(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
				str_replace("\\\\", "", $local))
			) {
				// character not valid in local part unless
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/',
					str_replace("\\\\", "", $local))
				) {
					$isValid = false;
				}
			}
			if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
				// domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
	}

	function TalkStatus()
	{
		$Talks = Talk::get();
		foreach ($Talks as $Talk) {
			echo $Talk->ID . " called " . $Talk->PresentationTitle . "<br/>";
		}
	}

	function AcceptedSpeakersWithoutEmails()
	{


		$Speakers = Talk::get();

		foreach ($Speakers as $Speaker) {
			$AcceptedTalks = $Speaker->AcceptedTalks();
			$UnacceptedTalks = $Speaker->UnacceptedTalks();
			$AlternateTalks = $Speaker->AlternateTalks();

			if (($AcceptedTalks->count() || $AlternateTalks->count()) && $Speaker->Member()->Email == NULL) {
				echo $Speaker->ID . ' ' . $Speaker->FirstName . ' ' . $Speaker->Surname . ' was accepted but deos not have an email. <br/>';
			}

		}

	}

	function AcceptedSpeakersNotContacted()
	{


		$Speakers = Speaker::get();

		foreach ($Speakers as $Speaker) {
			$AcceptedTalks = $Speaker->AcceptedTalks();
			$UnacceptedTalks = $Speaker->UnacceptedTalks();
			$AlternateTalks = $Speaker->AlternateTalks();

			if (($AcceptedTalks->count() || $AlternateTalks->count()) && $Speaker->BeenEmailed == 0) {
				echo $Speaker->ID . ' ' . $Speaker->FirstName . ' ' . $Speaker->Surname . ' was accepted but has not been contacted. <br/>';
			}

		}

	}

	function AcceptedSpeakersNotConfirmed()
	{


		$Speakers = Speaker::get();

		foreach ($Speakers as $Speaker) {
			$AcceptedTalks = $Speaker->AcceptedTalks();
			$UnacceptedTalks = $Speaker->UnacceptedTalks();
			$AlternateTalks = $Speaker->AlternateTalks();

			if (($AcceptedTalks->count() || $AlternateTalks->count()) && $Speaker->Confirmed == 0) {
				echo $Speaker->ID . ' ' . $Speaker->FirstName . ' ' . $Speaker->Surname . ' was accepted but has not confirmed. <br/>';
			}

		}

	}

	function AcceptedTalksWithoutSpeakers()
	{


		$Talks = Talk::get();

		foreach ($Talks as $Talk) {
			if ($Talk->Speakers()->count() == 0 && ($Talk->Status() == 'accepted' || $Talk->Status() == 'alternate')) {
				echo $Talk->PresentationTitle . '(' . $Talk->SummitCategory()->Name . ') from ' . $Talk->Owner()->FirstName . ' ' . $Talk->Owner()->Surname . ' ' . $Talk->Owner()->Email . ' was accepted but has no speakers. <br/>';
			}
		}

	}

	function AllAcceptedOrALternateSpeakers()
	{

		$Speakers = Speaker::get();

		$ReturnSet = new ArrayList();

		foreach ($Speakers as $Speaker) {
			if ($Speaker->AcceptedTalks()->count() || $Speaker->AlternateTalks()->count()) {
				$ReturnSet->push($Speaker);
			}
		}

		return $ReturnSet;

	}

	function AllUnAcceptedSpeakers()
	{

		$Speakers = Speaker::get();

		$ReturnSet = new ArrayList();

		foreach ($Speakers as $Speaker) {

			$DuplicateSpeakers = Speaker::get()->filter(array('FirstName' => $Speaker->FirstName, 'Surname' => $Speaker->Surname, 'ID:not' => $Speaker->ID));

			if (!$DuplicateSpeakers->count() && $Speaker->AcceptedTalks()->count() == 0 && $Speaker->AlternateTalks()->count() == 0) {
				$ReturnSet->push($Speaker);
			}
		}

		return $ReturnSet;

	}

	function AllTalks()
	{
		return Talk::get()->filter('SummitCategoryID', 29);
	}

	function SpeakersForSched()
	{

		$Speakers = Speaker::get();


		foreach ($Speakers as $Speaker) {

			$AcceptedTalks = $Speaker->AcceptedTalks();
			$AlternateTalks = $Speaker->AlternateTalks();

			$PhotoURL = "";

			if ($Speaker->PhotoID != 0) $PhotoURL = 'http://www.openstack.org' . $Speaker->Photo()->getURL();

			if ($Speaker->Bio == NULL) {
				$SpeakerWithBio = Speaker::get()->filter('MemberID', $Speaker->MemberID)->where('BIO IS NOT NULL');
				if ($SpeakerWithBio->count()) $Speaker->Bio = $SpeakerWithBio->first()->Bio;
			}

			if ($AcceptedTalks->count()) {
				echo '"' . $Speaker->ID . '"|"' . utf8_decode($Speaker->FirstName) . ' ' . utf8_decode($Speaker->Surname) . '"|"' . utf8_decode($Speaker->Title) . '"|"' . $Speaker->Member()->Email . '"|"' . $PhotoURL . '"|"' . htmlspecialchars(utf8_decode($Speaker->Bio)) . '" <br/>';
			}

		}

	}

	function ScheduleForSched()
	{

		$SummitCategories = SummitCategory::get()->filter('SummitID', 3);

		foreach ($SummitCategories as $Category) {
			// Grab the track chairs selections for the category
			$SelectedTalkList = SummitSelectedTalkList::get()->filter('SummitCategoryID', $Category->ID)->first();
			// Loop through each selected talk to output the details
			// Note that a SummitSelectedTalk is really just a cross-link table that also contains the priority the talk was given
			foreach ($SelectedTalkList->SortedTalks() as $Selection) {
				$Talk = $Selection->Talk();
				echo '"' . $Talk->ID . '"|"' . utf8_decode($Talk->PresentationTitle) . '"|"' . $Talk->SummitCategory()->Name . '"|"' . htmlspecialchars(utf8_decode($Talk->Abstract)) . '"|';

				// Speaker column

				echo '"';

				foreach ($Talk->Speakers() as $Speaker) {
					echo utf8_decode($Speaker->FirstName);
					echo " ";
					echo utf8_decode($Speaker->Surname);
					echo ',';
				}
				echo '" <br/>';
			}
		}

	}

	function SpeakersWithUnassignedTalks()
	{
		$Speakers = Speaker::get();
		foreach ($Speakers as $Speaker) {
			$Talks = $Speaker->Talks('SummitCategoryID = 25');
			if ($Talks->count()) {
				echo 'Speaker: ' . $Speaker->FirstName . ' ' . $Speaker->Surname . '<br/>';
				if ($Speaker->AcceptedTalks()->count() || $Speaker->AlternateTalks()->count()) {
					echo 'Has other accepted / alternate talks <br/>';
				}
				foreach ($Talks as $Talk) {
					echo $Talk->PresentationTitle . '<br/>';
				}
				echo '<br/><br/>';
			}
		}
	}

	function SpeakerCompanyReport()
	{
		$Speakers = Speaker::get()->filter('Email:PartialMatch', 'cisco')->leftJoin('Member', 'Member ON Speaker.MemberID = Member.I')->limit(800);

		echo 'Speaker Count: ' . $Speakers->count() . '<br/>';

		foreach ($Speakers as $Speaker) {

			$AcceptedTalks = $Speaker->AcceptedTalks();
			$UnacceptedTalks = $Speaker->UnacceptedTalks();
			$AlternateTalks = $Speaker->AlternateTalks();

			if ($Speaker->HasTalksInCurrentSummit()) {
				echo '<hr/>';
				echo '<strong> Speaker ' . $Speaker->FirstName . ' ' . $Speaker->Surname . ' summitted these presentations: </strong><br/><br/>';
				if ($AcceptedTalks->count()) {
					echo 'ACCEPTED FOR ATLANTA SUMMIT: <br/>';
					foreach ($AcceptedTalks as $Talk) {
						echo utf8_decode($Talk->PresentationTitle) . '<br/>';
					}
				}

				if ($AlternateTalks->count()) {
					echo 'ACCEPTED AS ALTERNATE FOR ATLANTA: <br/>';
					foreach ($AlternateTalks as $Talk) {
						echo utf8_decode($Talk->PresentationTitle) . '<br/>';
					}
				}

				if ($UnacceptedTalks->count()) {
					echo 'UNFORTUNATELY NOT INCLUDED FOR ATLANTA: <br/>';
					foreach ($UnacceptedTalks as $Talk) {
						echo utf8_decode($Talk->PresentationTitle) . '<br/>';
					}
				}

				echo '<br/><br/>';

			}

		}

		echo '<br/><br/>Done emailing speakers.<br/>';

	}

	function SpeakerSpreadsheetExport()
	{
		$Speakers = Speaker::get();

		echo '<!doctype html><head><meta charset="utf-8"></head><body>';

		foreach ($Speakers as $Speaker) {

			$AcceptedTalks = $Speaker->AcceptedTalks();
			$UnacceptedTalks = $Speaker->UnacceptedTalks();
			$AlternateTalks = $Speaker->AlternateTalks();

			// Assign Registration Codes

			// If the speaker has any accepted talks, they get a speaker-level code
			if($AcceptedTalks->count()) {
				$data['RegistrationCode'] = $this->getRegistrationCode($Speaker->MemberID,"Speaker");
				// If the speaker has no accepted talks, they get an alternate-level code
			} elseif (!$AcceptedTalks->count() && $AlternateTalks->count()) {
				$data['RegistrationCode'] = $this->getRegistrationCode($Speaker->MemberID,"Alternate");
			}

			if($AcceptedTalks->count() || $AlternateTalks->count()) {

				echo $this->EscapeForCSV($Speaker->ID) . ',' . $this->EscapeForCSV($Speaker->FirstName) . ',' . $this->EscapeForCSV($Speaker->Surname) . ',' . $this->EscapeForCSV($data['RegistrationCode']) . ',' . $this->EscapeForCSV($Speaker->OnsiteNumber) . ',"' . $Speaker->Member()->Email . '","';
				if($AcceptedTalks->count()) {
					foreach ($AcceptedTalks as $Talk) {
						echo $Talk->PresentationTitle . ' (Accepted); ';
					}
				}

				if($AlternateTalks->count()) {
					foreach ($AlternateTalks as $Talk) {
						echo $Talk->PresentationTitle . ' (Alternate); ';
					}
				}

				echo '","';

				if($Speaker->Confirmed) {
					echo 'YES';
				} else {
					echo 'NO';
				}

				echo '"<br/>';

			}

		}

		echo "</body>";
	}

	function getRegistrationCode($MemberID, $Type)
	{

		$ExistingCode = SummitRegCode::get()->filter(array('MemberID' => $MemberID, 'Type' => $Type))->first();
		if (!$ExistingCode) {

			$AvailableCode = SummitRegCode::get()->filter(array('MemberID' => 0, 'Type' => $Type))->first();

			if (!$AvailableCode) user_error("Tried to assign a code but no more codes appear to be available.");
			$AvailableCode->MemberID = $MemberID;
			$AvailableCode->write();
			return $AvailableCode->Code;
		} else {
			return $ExistingCode->Code;
		}
	}

	function SendSpeakerEmails()
	{

		echo '<!doctype html><head><meta charset="utf-8"></head><body>';

		$getVars = $this->request->getVars();

		if(!$getVars || !$getVars['limit']) user_error('The parameter limit is expected.');
		$limit = Convert::raw2sql($getVars['limit']);

		$Speakers = Speaker::get()->filter(array('Confirmed'=>false,'BeenEmailed'=>false))->limit($limit);

		echo 'Speaker Count: ' . $Speakers->count() . '<br/>';

		foreach ($Speakers as $Speaker) {

			$AcceptedTalks = $Speaker->AcceptedTalks();
			$UnacceptedTalks = $Speaker->UnacceptedTalks();
			$AlternateTalks = $Speaker->AlternateTalks();

			// Assign Registration Codes

			// If the speaker has any accepted talks, they get a speaker-level code
			if($AcceptedTalks->count()) {
				$data['RegistrationCode'] = $this->getRegistrationCode($Speaker->MemberID,"Speaker");
				// If the speaker has no accepted talks, they get an alternate-level code
			} elseif (!$AcceptedTalks->count() && $AlternateTalks->count()) {
				$data['RegistrationCode'] = $this->getRegistrationCode($Speaker->MemberID,"Alternate");
			}

			// Assign the speaker to speaker manager

			/* $data['SpeakerManagerName'] = '';

			// These are the SummitCategoryIDs that Beth will be managing
			$TamaraTracks = array(12,18,24,14);

			if($AcceptedTalks) {
			  foreach ($AcceptedTalks as $Talk) {
				if (in_array($Talk->SummitCategoryID, $TamaraTracks)) {
				  $data['SpeakerManagerName'] = 'Beth Nowak';
				  $data['SpeakerManagerEmail'] = 'beth@fntech.com';
				  $data['SpeakerManagerPhone'] = '415.994.8059';
				}
			  }

			  // If the speaker hasn't been assigned to Tamara, assign to Beth
			  if($data['SpeakerManagerName'] == '') {
				  $data['SpeakerManagerName'] = 'Tamara Pennington';
				  $data['SpeakerManagerEmail'] = 'tamara@fntech.com';
				  $data['SpeakerManagerPhone'] = '323.691.8222';
			  }
			} */

			// Set the from email depending on whether they have selected talks
			if($AcceptedTalks->count() || $AlternateTalks->count()) {
				$data['SpeakerManagerEmail'] = 'speakersupport@fntech.com';
			} else {
				$data['SpeakerManagerEmail'] = 'events@openstack.org';
			}

			$data['Speaker'] = $Speaker;
			$data['AcceptedTalks'] = $AcceptedTalks;
			$data['AcceptedTalksCount'] = $AcceptedTalks->count();
			$data['UnacceptedTalks'] = $UnacceptedTalks;
			$data['UnacceptedTalksCount'] = $UnacceptedTalks->count();
			$data['AlternateTalks'] = $AlternateTalks;
			$data['AlternateTalksCount'] = $AlternateTalks->count();
			$data['ConfirmationHash'] = $Speaker->SpeakerConfirmHash();



			if($Speaker->HasTalksInCurrentSummit()) {

				if (!$Speaker->Member()->Email) {
					echo 'Speaker '.$Speaker->FirstName.' '.$Speaker->Surname.' ('.$Speaker->ID.') could not be emailed. <br/>';
				} else {
					$To = $Speaker->Member()->Email;
					$From = $data['SpeakerManagerEmail'];

					$Subject = 'Your OpenStack Presentation Submissions';
					$email = EmailFactory::getInstance()->buildEmail($From, $To, $Subject);
					$email->setTemplate("SelectionAnnouncementEmail");
					$email->populateTemplate($data);

					// $email->send();
					echo $email->debug();
					echo 'Speaker '.$Speaker->FirstName.' '.$Speaker->Surname.' ('.$Speaker->ID.') was emailed successfully. <br/>';

				}
			}

			// Moved here so each is marked off regardless of presentation status.
			$Speaker->BeenEmailed = TRUE;
			$Speaker->write();

		}

		echo '<br/><br/>Done emailing speakers.<br/>';

		echo "</body>";
	}

	function SendFirstEmail()
	{

		$Talks = Talk::get()->filter(array('SummitID' => 3, 'MarkedToDelete' => 0));

		echo 'Talks Found: ' . $Talks->count() . '<br/>';

		foreach ($Talks as $Talk) {

			echo 'TALK: ' . $Talk->PresentationTitle . '<br/>';

			$Admin = Member::get()->filter('ID', $Talk->OwnerID)->first();

			// Look again at the data object to make sure it wasn't emailed in this loop
			$AdminNeedsEmailSent = Talk::get()->filter(array('ID' => $Talk->ID, 'BeenEmailed' => 0))->first();

			if ($AdminNeedsEmailSent) {
				$this->AssembleEmail($Admin);
			}

			$Speakers = $Talk->Speakers('BeenEmailed = FALSE');

			if ($Speakers->count() > 0) {
				foreach ($Speakers as $Speaker) {
					// only the speakers who are not also the presentation owner
					if ($Speaker->MemberID != $Talk->OwnerID) {
						$Member = $Speaker->Member();
						echo 'sending a speaker email to ' . $Member->FirstName . ' ' . $Member->Surname . '<br/>';
						$this->AssembleEmail($Member);
					}
				}
			}

		}
	}

	function AssembleEmail($Member)
	{

		$SpeakerTalks = NULL;

		$AdminTalks = Talk::get()->filter(array('SummitID' => 3, 'MarkedToDelete' => 0, 'OwnerID' => $Member->ID));
		$Speaker = Speaker::get()->filter('MemberID', $Member->ID)->first();
		if ($Speaker) $SpeakerTalks = $Speaker->Talks('SummitID = 3 AND OwnerID !=' . $Member->ID);

		if ($AdminTalks || $SpeakerTalks) {
			$data['Recipient'] = $Member;
			$data['AdminTalks'] = $AdminTalks;
			$data['SpeakerTalks'] = $SpeakerTalks;

			// Tally the total number of talks for this person (admin or speaker)
			$SpeakerTalkCount = NULL;
			$AdminTalkCount = NULL;
			if ($SpeakerTalks) $SpeakerTalkCount = $SpeakerTalks->count();
			if ($AdminTalks) $AdminTalkCount = $AdminTalks->count();
			$data['MultipleTalks'] = ($SpeakerTalkCount + $AdminTalkCount) > 1;

			$To = $Member->Email;
			$Subject = 'Your OpenStack Presentation Submissions';
			$email = EmailFactory::getInstance()->buildEmail(OS_PRESENTATION_SUBMISSIONS_FROM_EMAIL, $To, $Subject);
			$email->setTemplate("VotingLiveEmail");
			$email->populateTemplate($data);

			if ($this->validEmail($Member->Email)) {
				echo $email->debug();
				$email->send();
				echo $To . " emailed successfully. <br/>";

				// Set all the admin talks as emailed
				if ($AdminTalks) {
					foreach ($AdminTalks as $Talk) {
						$Talk->BeenEmailed = TRUE;
						$Talk->write();
					}
				}

				// Set the speaker as emailed
				if ($Speaker) {
					$Speaker->BeenEmailed = TRUE;
					$Speaker->write();
				}
			} else {
				echo "invalid email: " . $Member->Email . "<br/>";
			}
		}

	}

	function SendAdminEmails()
	{

		$Talks = Talk::get()->limit(700);

		foreach ($Talks as $Talk) {

			$AdminIsASpeaker = FALSE;
			$AcceptedTalks = new ArrayList();
			$UnacceptedTalks = new ArrayList();
			$AlternateTalks = new ArrayList();
			$Unconfirmed = new ArrayList();

			// Look to see if this admin has any unemailed presentations
			$UnemailedTalk = Talk::get()->filter(array('BeenEmailed' => 0, 'MarkedToDelete' => 0, 'OwnerID' => $Talk->Owner()->ID));;

			// There is a talk that needs to be emailed
			if ($UnemailedTalk) {

				foreach ($UnemailedTalk as $Talk) {

					if ($Talk->Status() == 'accepted') {
						$AcceptedTalks->push($Talk);
					} elseif ($Talk->Status() == 'alternate') {
						$AlternateTalks->push($Talk);
					} else {
						$UnacceptedTalks->push($Talk);
					}

					// Building a list of unconfirmed speakers
					foreach ($Talk->Speakers() as $Speaker) {
						if ($Speaker->Confirmed != TRUE) $Unconfirmed->push($Speaker);
					}

					$Talk->BeenEmailed = TRUE;
					$Talk->write();

					$AdminSpeaker = Speaker::get()->filter('MemberID', $Talk->OwnerID)->first();
					if ($AdminSpeaker && ($AdminSpeaker->AcceptedTalks()->count() || $AdminSpeaker->AlternateTalks()->count())) $AdminIsASpeaker = TRUE;

				}

				$Admin = $Talk->Owner();
				$Unconfirmed->removeDuplicates('ID');

				$data['Admin'] = $Admin;
				$data['AcceptedTalks'] = $AcceptedTalks;
				$data['AcceptedTalksCount'] = $AcceptedTalks->count();
				$data['UnacceptedTalks'] = $UnacceptedTalks;
				$data['UnacceptedTalksCount'] = $UnacceptedTalks->count();
				$data['AlternateTalks'] = $AlternateTalks;
				$data['AlternateTalksCount'] = $AlternateTalks->count();
				$data['AcceptedOrAlternateTalks'] = $AlternateTalks->count() > 0 || $AcceptedTalks->count() > 0;
				$data['UnconfirmedSpeakers'] = $Unconfirmed;
				$data['AdminIsASpeaker'] = $AdminIsASpeaker;

				$To = $Admin->Email;
				$Subject = 'Your OpenStack Presentation Submissions';
				$email = EmailFactory::getInstance()->buildEmail(OS_PRESENTATION_SUBMISSIONS_FROM_EMAIL, $To, $Subject);
				$email->setTemplate("AdminConfirmationEmail");
				$email->populateTemplate($data);

				if (!$AdminIsASpeaker) {
					// echo $email->debug();
					// $email->send();
					echo $To . " emailed successfully. <br/>";
				}


			}

		}

	}

	function SetSpeakerOrgs()
	{
		$Speakers = Speaker::get();
		foreach ($Speakers as $Speaker) {
			if ($Aff = $Speaker->Member()->currentAffiliation) {
				$AffName = $Aff->Organization()->Name;
				$Speaker->Organization = $AffName;
			}
			if ($Member = $Speaker->Member()) {
				$Speaker->Email = $Member->Email;
			}
			$Speaker->write();
			$TalksThisSummit = $Speaker->TalksBySummitID(3);
			if ($Speaker->Organization == '' && $TalksThisSummit->count() != 0) {
				echo $Speaker->FirstName . ' ' . $Speaker->Surname . ' ' . $Speaker->Email . ' has no org. <br/>';
			}
		}
	}

	function ExportTalks()
	{
		$getVars = $this->request->getVars();
		$CategoryID = intval($getVars["id"]);
		echo '<!doctype html><head><meta charset="utf-8"></head><body>';
		$Talks = Talk::get()->filter(array('SummitID' => 3, 'SummitCategoryID' => $CategoryID));
		foreach ($Talks as $Talk) {
			$SpeakerString = '';
			foreach ($Talk->Speakers() as $Speaker) {
				$SpeakerString = $SpeakerString . $Speaker->FirstName . ' ' . $Speaker->Surname;
				if ($Aff = $Speaker->Member()->currentAffiliation) {
					$AffName = $Aff->Organization()->Name;
					$SpeakerString = $SpeakerString . ' (' . $AffName . ')';
				}
				$SpeakerString = $SpeakerString . ', ';
			}
			// Remove the trailing comma and space
			If ($SpeakerString != '') $SpeakerString = substr_replace($SpeakerString, "", -2);
			echo
				$Talk->ID . "," .
				$this->EscapeForCSV('=HYPERLINK("http://www.openstack.org/vote-paris/Presentation/' . $Talk->URLSegment . '/", "' . $Talk->PresentationTitle . '")') . "," .
				'"' . $Talk->Tag . '",' .
				$this->EscapeForCSV($SpeakerString) . "," .
				$Talk->CalcVoteCount() . "," .
				$Talk->CalcTotalPoints() . "," .
				$Talk->CalcVoteAverage() . "<br/>";
		}
		echo '</body>';
	}

	function EscapeForCSV($value)
	{
		return '"' . str_replace('"', '""', $value) . '"';
	}
}