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
 * Used to view, edit, and categorize summit presentations
 * Designed for admin use only
 */
class TrackChairPage extends Page
{
	static $db = array();
	static $has_one = array();
	static $defaults = array(
		'ShowInMenus' => false
	);
}

class TrackChairPage_Controller extends Page_Controller implements PermissionProvider
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
		'SubcategoryForm',
		'CleanTalks',
		'CleanSpeakers',
		'EmailSpeakers',
		'EmailSubmitters',
		'FlaggedTalks',
		'setSortOrder',
		'SelectTalk',
		'UnselectTalk',
		'SelectionList',
		'SaveSortOrder',
		'SuggestCategoryChange',
		'AcceptCategoryChange',
		'SetUpTrackChair' => 'ADMIN',
		'TrackChairs',
		'EmailTrackChairs',
		'Tutorial'
	);

	function init()
	{
		if (!Permission::check("TRACK_CHAIR")) {
			Security::permissionFailure();
		} elseif (!$this->request->param('Action')) {

			$CategoryID = Session::get('CategoryID');
			if (!$CategoryID && $this->MemberChairCategory()) {
				// if there's no category in the session, set the member's category (if available)
				$CategoryID = $this->MemberChairCategory()->ID;
			} elseif (!$CategoryID) {
				// If there's no category in the session and setting the member's category didn't work, use default...
				$CategoryID = 1;
			}

			$this->redirect($this->Link() . 'Category/' . $CategoryID);
		}
		parent::init();

	}

	function MemberChairCategory()
	{
		if ($memberID = Member::currentUser()->ID) {
			$SummitTrackChair = SummitTrackChair::get()->filter('MemberID',$memberID)->first();
			if ($SummitTrackChair) {
				$categoryID = $SummitTrackChair->CategoryID;
				return SummitCategory::get()->byID($categoryID);
			}

		}
	}

	function providePermissions()
	{
		return array(
			"TRACK_CHAIR" => "An OpenStack Track Chair"
		);
	}

	function SetSortOrder()
	{

		// Get the URL params
		$sortColumn = $this->request->param("ID");
		$sortOrder = $this->request->param("OtherID");

		// Assume invalid input
		$validSortOrder = FALSE;
		$validSortColumn = FALSE;

		// Check to see if the params provided were valid
		if ($sortColumn) $validSortColumn = in_array($sortColumn, array("PresentationTitle", "TotalPoints", "VoteCount", "VoteAverage", "Status"));
		if ($sortOrder) $validSortOrder = in_array($sortOrder, array("ASC", "DESC"));

		// if bot params are valid, save them in the session
		// Pageload looks in session for sort order to sort the presentations in PresentationList()
		if ($validSortOrder && $validSortColumn) {
			Session::set('SortOrder', $sortOrder);
			Session::set('SortColumn', $sortColumn);
		}

		$this->redirectBack();

	}

	function SideNavItems()
	{

		$CurrentPage = $this->request->param("Action");
		if (($CurrentPage) == 'Show') $CurrentPage = 'Category';

		$SideNavArray = array();

		$SideNavArray["Category"] = array(
			'URLSegment' => '',
			'Name' => 'Browse Presentations',
			'Icon' => 'browse'
		);

		$SideNavArray["SelectionList"] = array(
			'URLSegment' => 'SelectionList',
			'Name' => 'Team Selections',
			'Icon' => 'team-selections'
		);

		$SideNavArray["TrackChairs"] = array(
			'URLSegment' => 'TrackChairs',
			'Name' => 'Chair Directory',
			'Icon' => 'directory'
		);

		$SideNavArray["Tutorial"] = array(
			'URLSegment' => 'Tutorial',
			'Name' => 'Quick Tutorial',
			'Icon' => 'tutorial'
		);

		// Mark current page as selected
		$SideNavArray[$CurrentPage]['Selected'] = TRUE;

		// Format array for SS's use in the template
		$list = new ArrayList();
		foreach ($SideNavArray as $item => $data) {
			$list->push(new ArrayData($data));
		}
		return $list;

	}

	function PresentationTableColumns()
	{


		// Define the columns
		$columnArray = array();

		$columnArray["PresentationTitle"] = array(
			'Column' => 'PresentationTitle',
			'DisplayName' => 'Name',
			'SortOrder' => 'ASC'
		);

		$columnArray["VoteCount"] = array(
			'Column' => 'VoteCount',
			'DisplayName' => 'Total Votes',
			'SortOrder' => 'ASC'
		);

		$columnArray["TotalPoints"] = array(
			'Column' => 'TotalPoints',
			'DisplayName' => 'Total Points',
			'SortOrder' => 'ASC'
		);

		$columnArray["VoteAverage"] = array(
			'Column' => 'VoteAverage',
			'DisplayName' => 'Vote Average',
			'SortOrder' => 'ASC'
		);

		if ($this->CurrentSortOrder() && $this->CurrentSortColumn()) {
			$columnArray[$this->CurrentSortColumn()]['SortOrder'] = $this->CurrentSortOrder();
		}

		$list = new ArrayList();
		foreach ($columnArray as $column => $data) {
			$list->push(new ArrayData($data));
		}
		return $list;

	}

	function CurrentSortOrder()
	{
		return Session::get('SortOrder');
	}

	// Find a talk given an id

	function CurrentSortColumn()
	{
		return Session::get('SortColumn');
	}

	function Show()
	{

		$Talk = $this->findTalk();

		if ($Talk) {

			Session::set('CategoryID', $Talk->SummitCategoryID);

			$data = $this->PresentationsByCategory();

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


	//Show the details of a talk

	function PresentationList($categoryID = NULL, $sortBy = NULL, $order = NULL)
	{

		// Set some defaults for sorting
		if ($sortBy == NULL) $sortBy = 'VoteAverage';
		if ($order == NULL) $order = 'DESC';

		$Results = new ArrayList();

		$categoryClause = "";
		if ($categoryID) $categoryClause = " AND SummitCategoryID = '" . $categoryID . "'";

		$Talks = Talk::get()->where("MarkedToDelete = FALSE" . $categoryClause);
		if ($sortBy && $order) {
			$Talks->sort($sortBy, $order);
		} else {
			// default sort
			$Talks->sort('PresentationTitle', 'ASC');
		}

		if ($Talks) {
			foreach ($Talks as $Talk) {
				$Talk->TotalPoints = $Talk->CalcTotalPoints();
				$Talk->TotalPoints = $Talk->CalcTotalPoints();
				$Talk->VoteCount = $Talk->CalcVoteCount();
				$Talk->VoteCount = $Talk->CalcVoteCount();
				$Talk->VoteAverage = $Talk->CalcVoteAverage();
				$Results->push($Talk);
			}
		}



		return $Results;
	}

	function PresentationsByCategory()
	{

		if ($CategoryID = Session::get('CategoryID')) {

			$Talks = $this->PresentationList($CategoryID, Session::get('SortColumn'), Session::get('SortOrder'));
			if ($Talks) $data["Presentations"] = True;
			$data["PresentationList"] = $Talks;

		} else {

			$Talks = $this->PresentationList('', Session::get('SortColumn'), Session::get('SortOrder'));
			if ($Talks) $data["Presentations"] = True;
			$data["PresentationList"] = $Talks;

		}

		return $data;

	}


	function CurrentCategory()
	{
		$category   = NULL;
		$categoryID = Session::get('CategoryID');
		if ($categoryID) {
			return SummitCategory::get()->byID($categoryID);
		} else {
			return new ArrayData(array('Name' => 'All Categories'));
		}
	}

	//Used to list presentations from a specific category
	function Category()
	{

		$CategoryID = Convert::raw2sql($this->request->param("ID"));

		if ($CategoryID == 'All') {

			Session::clear('CategoryID');
			$data = $this->PresentationsByCategory();
			return $this->Customise($data);

			// if it's numberic and a category by that number exists
		} elseif (is_numeric($CategoryID) && SummitCategory::get()->byID($CategoryID)) {
			Session::set('CategoryID', $CategoryID);
			$data = $this->PresentationsByCategory();
			return $this->Customise($data);
		}

	}


	// Render category buttons
	function CategoryButtons()
	{

		$Talk = $this->findTalk();
		$Categories = $this->CategoryList();

		return $Categories;

	}

	function CategoryList()
	{
		return SummitCategory::get()->filter('SummitID',3);
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

	function SearchForm()
	{
		$SearchForm = new PresentationSearchForm($this, 'SearchForm');
		$SearchForm->disableSecurityToken();
		return $SearchForm;
	}

	function doSearch($data, $form)
	{

		$Talks = NULL;

		if ($data['Search'] && strlen($data['Search']) > 1) {
			$query = Convert::raw2sql($data['Search']);

			$sqlQuery = new SQLQuery();
			$sqlQuery->addSelect(array(
				'DISTINCT Talk.URLSegment AS URLSegment',
				'Talk.PresentationTitle AS PresentationTitle',
				'Talk.SummitID AS SummitID',
				// IMPORTANT: Needs to be set after other selects to avoid overlays
				'Talk.ClassName AS ClassName',
				'Talk.ClassName AS RecordClassName',
				'Talk.ID AS ID'
			));
			$sqlQuery->addFrom(array(
				"Talk",
				"left join Talk_Speakers on Talk.ID = Talk_Speakers.TalkID left join Speaker on Talk_Speakers.SpeakerID = Speaker.ID"
			));
			$sqlQuery->addWhere( array(
				"(Talk.MarkedToDelete IS FALSE) AND ((concat_ws(' ', Speaker.FirstName, Speaker.Surname) like '%$query%') OR (Talk.PresentationTitle like '%$query%') or (Talk.Abstract like '%$query%'))"
			));

			$result = $sqlQuery->execute();

			// let Silverstripe work the magic

			$arrayList = new ArrayList();

			foreach($result as $rowArray) {
				// concept: new Product($rowArray)
				$arrayList->push(new $rowArray['ClassName']($rowArray));
			}

			$Talks = $arrayList;

		}

		$data['SearchMode'] = TRUE;
		if ($Talks) $data["SearchResults"] = $Talks;

		$Talk = $this->findTalk();

		if ($Talk) {
			$data["Presentation"] = $Talk;
		}

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
		if ($data['FlagComment'] && $Talk) {
			$Talk->FlagComment = $data['FlagComment'];
		} elseif ($Talk) {
			$Talk->FlagComment = NULL;
		}
		$Talk->write();
		$this->redirectBack();
	}

	function SubcategoryForm()
	{
		$SubcategoryForm = new PresentationSubcategoryForm($this, 'SubcategoryForm');
		$SubcategoryForm->disableSecurityToken();
		$Talk = $this->findTalk();
		if ($Talk) $SubcategoryForm->loadDataFrom($Talk->data());
		return $SubcategoryForm;
	}

	function doSubcategory($data, $form)
	{
		$Talk = $this->findTalk();
		if ($data['Subcategory'] && $Talk) {
			$Talk->Subcategory = $data['Subcategory'];
		} elseif ($Talk) {
			$Talk->Subcategory = NULL;
		}
		$Talk->write();
		$this->redirectBack();
	}

	function FlaggedTalks()
	{
		$Talks = Talk::get()->where('FlagComment is not null');

		foreach ($Talks as $Talk) {
			$curOrg = $Talk->Owner()->getCurrentOrganization();
			echo $Talk->FlagComment . '| ';
			echo (!is_null($curOrg) ? $curOrg->Name : "") . '| ';
			echo $Talk->Owner()->FirstName . '| ';
			echo $Talk->Owner()->Surname . '| ';
			echo $Talk->PresentationTitle . '<br/> ';
		}
	}

	function SelectTalk()
	{

		//  Look for talk
		$TalkID = Convert::raw2sql($this->request->param("ID"));
		if (is_numeric($TalkID) && $Talk = Talk::get()->byID($TalkID)) {

			// Check permissions of user on talk
			if ($Talk->CanAssign()) {

				$SummitSelectedTalkList = SummitSelectedTalkList::get()->filter('SummitCategoryID',$Talk->SummitCategoryID)->first();;

				// if a summit talk list doens't exist for this category, create it
				if (!$SummitSelectedTalkList) {
					$SummitSelectedTalkList = new SummitSelectedTalkList();
					$SummitSelectedTalkList->SummitCategoryID = $Talk->SummitCategoryID;
					$SummitSelectedTalkList->write();
				}

				$AlreadyAssigned = $SummitSelectedTalkList->SummitSelectedTalks('TalkID = ' . $Talk->ID);

				if ($AlreadyAssigned->count() == 0) {
					$SelectedTalk = new SummitSelectedTalk();
					$SelectedTalk->SummitSelectedTalkListID = $SummitSelectedTalkList->ID;
					$SelectedTalk->TalkID = $Talk->ID;
					$SelectedTalk->MemberID = Member::currentUser()->ID;
					$SelectedTalk->write();
				}

				$this->redirectBack();


			} else {
				echo "You do not have permission to select this presentation.";
			}

		}

	}

	function UnselectTalk()
	{

		//  Look for talk
		$TalkID = Convert::raw2sql($this->request->param("ID"));
		if (is_numeric($TalkID) && $Talk = Talk::get()->byID($TalkID)) {

			// Check permissions of user on talk
			if ($Talk->CanAssign()) {

				$AssignedTalks = SummitSelectedTalk::get()->filter('TalkID',$Talk->ID);

				if ($AssignedTalks) {
					foreach ($AssignedTalks as $TalkToRemove) {
						$TalkToRemove->delete();
					}
				}

				$this->redirectBack();


			} else {
				echo "You do not have permission to select this presentation.";
			}

		}

	}

	function SelectedTalkList()
	{

		//Set the category is one is defined
		$CategoryID = $this->request->param('ID');
		if (is_numeric($CategoryID) && SummitCategory::get()->byID($CategoryID)) Session::set('CategoryID', $CategoryID);

		// pull up the selected talks list from the current category (if set)
		$ListID = Session::get('CategoryID');

		if ($memberID = Member::currentUser()->ID) {
			$SummitTrackChair = SummitTrackChair::get()->filter('MemberID', $memberID);
			if ($SummitTrackChair || Permission::check("ADMIN")) {

				// if a ListID is set, look to see if the current member is actually a track chair of that category (or admin) and able to see the list
				if ($ListID && SummitTrackChair::get()->filter(array('CategoryID'=> $ListID, 'MemberID' => $memberID))->count() || Permission::check("ADMIN")) {
					$categoryID = $ListID;
				} else {
					$categoryID = $SummitTrackChair->first()->CategoryID;
				}

				// a selected talks list hasn't been created yet, so start a new empty list
				if (!$SummitSelectedTalkList = SummitSelectedTalkList::get()->filter('SummitCategoryID',$categoryID)->first()) {
					$SummitSelectedTalkList = new SummitSelectedTalkList();
					$SummitSelectedTalkList->SummitCategoryID = $categoryID;
					$SummitSelectedTalkList->write();
				}

				return $SummitSelectedTalkList;
			}

		}
	}

	function SaveSortOrder()
	{

		foreach ($_GET['listItem'] as $position => $item) {
			$SelectedTalk = SummitSelectedTalk::get()->byID($item);
			if ($SelectedTalk) {
				$SelectedTalk->Order = $position + 1;
				$SelectedTalk->write();
			}
		}

		return "Order Saved!";

	}

	function SuggestCategoryChange()
	{
		$TalkID = Convert::raw2sql($this->request->param("ID"));
		$NewCategoryID = Convert::raw2sql($this->request->param("OtherID"));

		if ($TalkID && is_numeric($TalkID) && $NewCategoryID && is_numeric($NewCategoryID)) {

			// Look for current category and talk
			$CurrentCategory = NULL;
			$MemberIsTrackChair = NULL;

			$Talk = Talk::get()->byID( $TalkID);
			if ($Talk) $CurrentCategory = SummitCategory::get()->byID($Talk->SummitCategoryID);

			// Look for new category
			$NewCategory = SummitCategory::get()->byID($NewCategoryID);

			$MemberID = Member::currentUser()->ID;
			if ($CurrentCategory) $MemberIsTrackChair = $CurrentCategory->SummitTrackChairs('MemberID = ' . $MemberID)->count();

			if ($NewCategory && $CurrentCategory && $Talk && $MemberIsTrackChair) {

				$ChangeRequest = new SummitCategoryChange();
				$ChangeRequest->TalkID = $Talk->ID;
				$ChangeRequest->NewCategoryID = $NewCategory->ID;
				$ChangeRequest->RequesterID = $MemberID;
				$ChangeRequest->write();

				if ($TrackChairs = $NewCategory->SummitTrackChairs()) {

					foreach ($TrackChairs as $Chair) {
						echo 'Email sent to ' . $Chair->Member()->Email . '<br/>';
					}
				}
			}
		}
	}

	function AcceptCategoryChange()
	{
		$CategoryChangeID = Convert::raw2sql($this->request->param("ID"));

		// Check the provided value and pull up the category change
		if ($CategoryChangeID && is_numeric($CategoryChangeID) && $CategoryChange = SummitCategoryChange::get()->byID($CategoryChangeID)) {

			$MemberIsTrackChair = NULL;

			$Talk = $CategoryChange->Talk();
			$NewCategory = SummitCategory::get()->byID($CategoryChange->NewCategoryID);
			$MemberID = Member::currentUser()->ID;
			if ($NewCategory) $MemberIsTrackChair = $NewCategory->SummitTrackChairs('MemberID = ' . $MemberID)->Count();

			if ($Talk && $NewCategory && $MemberIsTrackChair) {
				$Talk->SummitCategoryID = $NewCategory->ID;
				$Talk->write();

				$CategoryChange->ApproverID = $MemberID;
				$CategoryChange->Approved = TRUE;
				$CategoryChange->write();

				echo 'Changed "' . $Talk->PresentationTitle . '" to the category ' . $NewCategory->Name;

			} elseif (!$MemberIsTrackChair) {

				echo 'You must be a track chair to apporve a category change.';

			}
		}

	}

	function SetUpTrackChair()
	{
		$CategoryID = Convert::raw2sql($this->request->param("ID"));
		$MemberID = Convert::raw2sql($this->request->param("OtherID"));

		$CategoryID = intval($CategoryID);
		$MemberID = intval($MemberID);

		$Member = Member::get()->byID($MemberID);
		$Category = SummitCategory::get()->byID($CategoryID);

		//Find or create the 'track-chairs' group
		if (!$Group = Group::filter('Code', 'track-chairs')->first()) {
			$Group = new Group();
			$Group->Code = "track-chairs";
			$Group->Title = "Track Chairs";
			$Group->Write();
			$Member->Groups()->add($Group);
		}
		//Add member to the group
		$Member->Groups()->add($Group);
		$Member->write();
		$ExistingTrackChair = SummitTrackChair::get()->filter(array('MemberID'=>$MemberID,'CategoryID'=>$CategoryID));
		if (!$ExistingTrackChair) {
			$TrackChair = new SummitTrackChair();
			$TrackChair->MemberID = $MemberID;
			$TrackChair->CategoryID = $CategoryID;
			$TrackChair->write();
		}


		echo "Added " . $Member->FirstName . ' ' . $Member->Surname . ' as track chair to ' . $Category->Name . '<br/>';

	}

	function AllTrackChairs()
	{
		return SummitTrackChair::get()->sort('CategoryID','ASC');
	}

	function EmailTrackChairs()
	{
		$TrackChairs = SummitTrackChair::get();

		foreach ($TrackChairs as $Chair) {

			$To = $Chair->Member()->Email;
			$Subject = "Openstack Track Chairs - Rank Your Sessions by Sept 9";

			$email = EmailFactory::getInstance()->buildEmail(TRACK_CHAIRS_EMAIL_FROM, $To, $Subject);
			$email->setTemplate("TrackChairsUpdateEmail");
			$email->populateTemplate($Chair);
			$email->send();

			echo 'Email sent to ' . $Chair->Member()->Email . '<br/>';
		}

	}
}