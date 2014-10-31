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
	class SpeakerVotingPage extends Page {
		static $db = array(
		);
		static $has_one = array(
	     );
	}

	class SpeakerVotingPage_Controller extends Page_Controller {

		public static $allowed_actions = array (
			'SaveRating',
			'SaveComment',
			'CompleteVoting',
			'ReopenVoting',
			'Remove',
			'EmailList',
			'presentation'
		);


        protected function CustomScripts(){
            Requirements::javascript("themes/openstack/javascript/jquery.raty-2.1.0/js/jquery.raty.min.js");
            parent::CustomScripts();
        }

		function init() {
			parent::init();
			// Look to see if there's a cookie with the voter id 
		    $VoterCookie = new Cookie; 
		    if(!$VoterCookie->get('voter_id') && $Member = member::currentUser()) {
		       $VoterCookie->set('voter_id', $Member->ID);
		    } 

		}
		
		function SpeakerSubmissions() {
			return SpeakerSubmission::get()->filter('DisplayOnSite',1)->sort('LastName','ASC');
		}

		function HasCompletedVoting() {
			$VoterCookie = new Cookie;
			return $VoterCookie->get('voting_complete');
		}

		function CompleteVoting() {
			$VoterCookie = new Cookie;
			$VoterCookie->set('voting_complete','TRUE');
			$this->redirectBack();
		}

		function ReopenVoting() {
			$VoterCookie = new Cookie;
			$VoterCookie->set('voting_complete','');
			$this->redirectBack();
		}

		function ClientIP() {
			$inSSL = ( isset($_SERVER['SSL']) || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ) ? true : false;
			if($inSSL) {
				$clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$clientIP = $_SERVER['REMOTE_ADDR'];
			}
			return $clientIP;
		}

		function SaveRating() {

			if(isset($_GET['rating']) && is_numeric($_GET['rating'])) {
				$rating = $_GET['rating'];
			}

			if(isset($_GET['id']) && is_numeric($_GET['id'])) {
				$submissionID = $_GET['id'];
			}

			$Member = member::currentUser();

			if($Member && isset($rating) && $submissionID) {
				$previousVote = SpeakerVote::get()->filter(array('SpeakerSubmissionID'=>$submissionID , 'VoterID' =>$Member->ID))->first();
				if(!$previousVote) {
					$speakerVote = new SpeakerVote;
					$speakerVote->SpeakerSubmissionID = $submissionID;
					$speakerVote->VoteValue = $rating;
					$speakerVote->IP = $this->ClientIP();
					$speakerVote->VoterID = $Member->ID;
					$speakerVote->write();
					if (Director::is_ajax()) {
						return $submissionID;
					} else {
						$this->redirect($this->Link()."#".$submissionID);
					}
				} else {
					$previousVote->VoteValue = $rating;
					$previousVote->IP = $this->ClientIP();
					$previousVote->write();
					if (Director::is_ajax()) {
						return $submissionID;
					} else {
						$this->redirect($this->Link()."#".$submissionID);
					}
				}
				
			} else {
				return false;
			}
		}

		function SaveComment($data) {
			$VarsPassed = $data->requestVars();
			$comment = Convert::raw2sql($VarsPassed['comment']);
			$submissionID = Convert::raw2sql($VarsPassed['submission']);
			$Member = member::currentUser();

			if($Member) {

				$previousVote = SpeakerVote::get()->filter(array( 'SpeakerSubmissionID' => $submissionID, 'VoterID' => $Member->ID))->first();
				if(!$previousVote) {
					$speakerVote = new SpeakerVote;
					$speakerVote->SpeakerSubmissionID = $submissionID;
					$speakerVote->Note = $comment;
					$speakerVote->IP = $this->ClientIP();
					$speakerVote->VoterID = $Member->ID;
					$speakerVote->write();
					return $VarsPassed["comment"];
				} else {
					$previousVote->Note = $comment;
					$previousVote->IP = $this->ClientIP();
					$previousVote->write();
					return $VarsPassed["comment"];
				}
				
			} else {
				return false;
			}
		}

		public function SiteAdmin() { 
			if(Permission::check('ADMIN')) return true; 
		}

		function EmailList() {
			$speakers = SpeakerSubmission::get()->filter('LastName','ASC');
			$SpeakerListText = "";

			foreach($speakers as $speaker) {
				$pos = strpos($SpeakerListText, $speaker->Email);
				if (!$pos) {
					$SpeakerListText = $SpeakerListText . $speaker->Email . ", ";
				}
			}
			return $SpeakerListText;
		}

		function BackLink() {
			$urlEncodedLink = urlencode($this->Link());
			return $urlEncodedLink;
		}

		//Show the Presentation detail page using the SpeakerVotingPage_presentation.ss template
		function presentation() 
		{	

			if($Presentation = $this->getPresentationByURLSegment())
			{
				$Data = array(
					'Presentation' => $Presentation
				);
				
				//return our $Data to use on the page
				return $this->Customise($Data);
			}
			else
			{
				//Presentation not found
				return $this->httpError(404, 'Sorry that presentation could not be found');
			}
		}


		//Get the current Presentation from the URL, if any
		public function getPresentationByURLSegment()
		{
			$Params = $this->getURLParams();
			$id = convert::raw2sql($Params['ID']);
			if(is_numeric($id) && $Presentation = SpeakerSubmission::get()->byID($id))
			{	
				return $Presentation;
			}
		}

				
	}