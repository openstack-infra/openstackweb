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
	class ElectionVoterPage extends Page {
		static $db = array(
			'MustBeMemberBy' => 'Date'
		);
		static $has_one = array(
	     );
		static $has_many = array(
			'Companies' => 'Company'
		);

	    function getCMSFields() {
	    	$fields = parent::getCMSFields();

	    	// the date field is added in a bit more complex manner so it can have the dropdown date picker
	    	$MustBeMemberBy = new DateField('MustBeMemberBy','Only show foundation members that signed up before this date:');
    		$MustBeMemberBy->setConfig('showcalendar', true);
    		$MustBeMemberBy->setConfig('showdropdown', true);
			$fields->addFieldToTab('Root.Main', $MustBeMemberBy, 'Content');

	    	return $fields;
		}
	}

	class ElectionVoterPage_Controller extends Page_Controller {

		static $allowed_actions = array(
			'ElectionVoters',
			'FullList' => 'ADMIN',
			'FullList2' => 'ADMIN',
			'FullList3' => 'ADMIN'
		);

		function init() {
			parent::init();
		}


		function ElectionVoters() {

			$MustBeMemberBy = $this->MustBeMemberBy;


			if(isset($_GET['letter'])){

				$requestedLetter = Convert::raw2xml($_GET['letter']);

				if($requestedLetter == 'intl') {
					$likeString = "NOT Surname REGEXP '[A-Za-z0-9]'";
				} elseif(ctype_alpha($requestedLetter )){
					$likeString = "Surname LIKE '".substr($requestedLetter,0,1)."%'";
				} else {
					$likeString = "Surname LIKE 'a%'";
				}

			} else {
				$likeString = "Surname LIKE 'a%'";
			}

			$MemberList = Member::get()->where($likeString)->innerJoin('Group_Members','`Member`.`ID` = `Group_Members`.`MemberID` AND Group_Members.GroupID=5')->sort('Surname');
			$VoterList = new ArrayList();

			foreach ($MemberList as $Member) {
				if ($Member->Created <= $MustBeMemberBy) {
					$VoterList->push($Member);
				}
			}

			return GroupedList::create($VoterList);

		}

		function FullList() {


			$MemberList = Member::get()->innerJoin('Group_Members',"Member.ID = Group_Members.MemberID and Group_Members.GroupID = 5")->sort('Surname');

			foreach ($MemberList as $Member) {
				if ($Member->Created <= $this->MustBeMemberBy) {
					echo $Member->ID.',"'.$Member->Email.'","'.$Member->FirstName.'","'.$Member->Surname.'","'.$Member->getOrgName().'",'.'8,"'.'Organization","'.$Member->getOrgName().'" <br/>';
				}
			}

		}

		function FullList2() {

			$MemberList = Member::get()->innerJoin('Group_Members','Member.ID = Group_Members.MemberID and Group_Members.GroupID = 5')->innerJoin('Affiliation','Member.ID = Affiliation.MemberID and Affiliation.Current = 1')->innerJoin('OrgMemberCount','Affiliation.OrganizationID = OrgMemberCount.OrgID and OrgMemberCount.MemberCount > 4')->sort('Surname');

			foreach ($MemberList as $Member) {
				if ($Member->Created <= $this->MustBeMemberBy) {
					echo $Member->ID.',"'.$Member->Email.'","'.$Member->FirstName.'","'.$Member->Surname.'","'.$Member->getOrgName().'",'.'8,"'.'Organization2","'.$Member->getOrgName().'" <br/>';
				}
			}

		}

		function FullList3() {

			$MemberList = Member::get()->innerJoin('Group_Members','Member.ID = Group_Members.MemberID and Group_Members.GroupID = 5')->innerJoin('Affiliation','Member.ID = Affiliation.MemberID and Affiliation.Current = 1')->innerJoin('OrgMemberCount','Affiliation.OrganizationID = OrgMemberCount.OrgID and OrgMemberCount.MemberCount > 19')->sort('Surname');

			foreach ($MemberList as $Member) {
				if ($Member->Created <= $this->MustBeMemberBy) {
					echo $Member->ID.',"'.$Member->Email.'","'.$Member->FirstName.'","'.$Member->Surname.'","'.$Member->getOrgName().'",'.'8,"'.'Organization3","'.$Member->getOrgName().'" <br/>';
				}
			}

		}



	}