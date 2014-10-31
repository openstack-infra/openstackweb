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
class SpeakerSubmission extends DataObject {

	static $db = array(
		'Content' => 'HTMLText',
		'FirstName' => 'Text',
		'LastName' => 'Text',
		'Email' => 'Text',
		'JobTitle' => 'Text',
		'Company' => 'Text',
		'Bio' => 'HTMLText',
		'Topic' => 'Text',
		'PresentationTitle' => 'HTMLText',
		'Abstract' => 'HTMLText',
		'BeenEmailed' => 'Boolean',
		'MainTopic' => 'Text',
		'VoteTotal' => 'Int',
		'DisplayOnSite' => 'Boolean'		
	);
	
	static $has_one = array(
		'Photo' => 'BetterImage'
	);

	static $has_many = array(
		'SpeakerVotes' => 'SpeakerVote'
	);
	
	static $singular_name = 'Speaker Submission';
	static $plural_name = 'Speaker Submissions';

	public function canView($member = null) { 
		return true; 
	}

	function SpeakerEditHash() {
			$id = $this->ID;
			$prefix = "000";
			$hash = base64_encode($prefix . $id);
			return $hash;
		}

	function GetRating() {
		$Member = member::currentUser();
		if($Member) {
			$id = $this->ID;

			$Vote = SpeakerVote::get()->filter(array('SpeakerSubmissionID'=>$id,'VoterID'=> $Member->I))->first();
			if($Vote) {
				return $Vote->VoteValue;
			} else {
				return 0;
			}
		}
	}

	function RatingBar() {
		$Rating = $this->GetRating();
		$RatingSet = array( array( 'Title' => "Would Not See", 
                      'Value' => -1
                    ),
               array( 'Title' => "No Opinion", 
                      'Value' => 0
                    ),
               array( 'Title' => "I might See This", 
                      'Value' => 1
                    ),
               array( 'Title' => "I'd Try To See", 
                      'Value' => 2
                    ),
               array( 'Title' => "Would Love To See This!", 
                      'Value' => 3
                    )               
             );

		$RatingBar = new ArrayList();
		$CurrentRating = $this->GetRating();

		foreach ($RatingSet as $RatingRow) {
			$do=new DataObject();
			$do->ID = $this->ID; 
			$do->Value = $RatingRow['Value'];
			$do->RatingTitle = $RatingRow['Title'];
			if($RatingRow['Value'] == $CurrentRating) {
				$do->Selected = 'selected'; 
			}
			$RatingBar->push($do); 
		}

		return $RatingBar;
	}	

	function GetNote() {
		$Member = member::currentUser();
		if($Member) {
			$id = $this->ID;
			$Vote = SpeakerVote::get()->filter(array('SpeakerSubmissionID' => $id, 'VoterID' => $Member->ID))->first();
			if($Vote) {
				return $Vote->Note;
			} else {
				return '';
			}
		}
	}

	function RatingTotal() {

		$Votes = SpeakerVote::get()->filter(array('SpeakerSubmissionID' => $this->ID));
		$VoteTotal = 0;
		if($Votes) {
			foreach ($Votes as $CurrentVote) {
				$VoteTotal = $VoteTotal + $CurrentVote->VoteValue;
			}
		}
		$this->VoteTotal = $VoteTotal;
		$this->write();
		return $VoteTotal;
	}

	function CountVotes($value) {

		$Votes = SpeakerVote::get()->filter(array( 'SpeakerSubmissionID' => $this->ID , 'VoteValue' => $value));
		if($Votes) {
		return $Votes->count();
		} else {
			return 0;
		}
	}

	function Votes() {
		$Votes = SpeakerVote::get()->filter(array('SpeakerSubmissionID' => $this->ID));
		return $Votes;
	}

	function SetMainTopicLinks() {
		if($this->Topic) {
			$links = "";
			$topics = explode(",", $this->Topic);
			foreach ($topics as $topic) {
				$urlEncodedTopic = urlencode($topic);
				$topicLink = '<a href="/summit/portland-2013/speaker-submissions/SetMainTopic/?id='.$this->ID.'&topic='.$urlEncodedTopic.'">'.$topic.'</a> &nbsp; &nbsp; ';
				$links = $links . $topicLink;
			}
			return $links;
		}
	}

	public function SiteAdmin() { 
		if(Permission::check('ADMIN')) return true; 
	}

}