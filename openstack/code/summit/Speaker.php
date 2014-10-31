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
class Speaker extends DataObject {

	static $db = array(
		'FirstName' => 'Varchar(255)',
		'Surname' => 'Varchar(255)',
		'Bio' => 'HTMLText',
		'IRCHandle' => 'Text',
		'TwitterName' => 'Text',
		'Title' => 'Text',
		'BeenEmailed' => 'Boolean',
		'OldMemberID' => 'Int',
        'Confirmed' => 'Boolean',
        'OnsiteNumber' => 'Text',
        'AskedAboutBureau' => 'Boolean',
        'AviableForBureau' => 'Boolean',
        'FundedTravel' => 'Boolean',
        'Expertise' => 'Text'
	);
	
	static $has_one = array(
		'Member' => 'Member',
		'Admin' => 'Member',
		'Photo' => 'BetterImage'
	);

	static $belongs_many_many = array(
		'Talks' => 'Talk', 
	);

    public function HasTalksInCurrentSummit() {
        return ($this->Talks('`MarkedToDelete` = 0 AND `SummitID` = '.Summit::CurrentSummitID())->count() != 0);
    }

    function TalksBySummitID($SummitID) {
        return $this->Talks('`SummitID` = '.$SummitID);
    }

    function CanEdit($member = null) {
    	if($memberID = Member::currentUser()->ID) {
	    	$IsSpeaker = $this->MemberID == $memberID;
	    	$IsAdmin = ($this->AdminID == $memberID || Permission::check("ADMIN"));
	    	return $IsSpeaker || $IsAdmin;
    	}
    }

    function CanRemoveFromTalk($TalkID) {
    	$talk = null;
    	if (is_numeric($TalkID)) $talk = Talk::get()->byID($TalkID);
    	if ($talk) {
    		if ($talk->CanEdit()) return TRUE;
    	}
    }

    function AcceptedTalks() {
    	$AcceptedTalks = new ArrayList();

    	$Talks = $this->Talks('`SummitID` = '.Summit::CurrentSummitID());
    	foreach ($Talks as $Talk) {
    		if($Talk->Status() == "accepted") $AcceptedTalks->push($Talk);
    	}

    	return $AcceptedTalks;
    }

    function UnacceptedTalks() {
    	$UnacceptedTalks = new ArrayList();

    	$Talks = $this->Talks('`SummitID` = '.Summit::CurrentSummitID());
    	foreach ($Talks as $Talk) {
    		if($Talk->Status() == "unaccepted") $UnacceptedTalks->push($Talk);
    	}

    	return $UnacceptedTalks;
    }

    function AlternateTalks() {
    	$AlternateTalks = new ArrayList();

        $Talks = $this->Talks('`SummitID` = '.Summit::CurrentSummitID());
    	foreach ($Talks as $Talk) {
    		if($Talk->Status() == "alternate") $AlternateTalks->push($Talk);
    	}

    	return $AlternateTalks;
    }

    function SpeakerConfirmHash() {
        $id = $this->ID;
        $prefix = "000";
        $hash = base64_encode($prefix . $id);
        return $hash;
    }

    function RegistrationCode() {
        return SummitRegCode::get()->filter('MemberID', $this->MemberID)->first();
    }

}
