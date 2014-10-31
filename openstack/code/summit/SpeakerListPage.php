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
	class SpeakerListPage extends Page {
		static $db = array(
		);
		static $has_one = array(
	     );
	}

	class SpeakerListPage_Controller extends Page_Controller {

		public static $allowed_actions = array (
			'EmailSpeakers',
			'Remove',
			'SetMainTopic',
			'RemoveStyleTags',
			'SetOneTopic'
		);

		function init() {
			parent::init();
		}
		
		function SpeakerSubmissions() {
			return SpeakerSubmission::get()->sort("LastName", "ASC");
		}

		function CheckID($id) {
			if (isset($_GET[$id]) && is_numeric($_GET[$id])) {
				return $_GET[$id];
			} else {
				return NULL;
			}
		}

		function Remove() {
			if ($SubmissionID = $this->CheckID('id')){
				$SubmissionToDelete = SpeakerSubmission::get()->byId($SubmissionID);
				$SubmissionToDelete->delete();
				$this->redirectBack();
			} else {
				user_error('Removing a submission requires a valid id.');
			}
		}

		function SetMainTopic() {
			if ($this->CheckID('id') && isset($_GET['topic'])){
				$SubmissionID = $this->CheckID('id');
				$MainTopic = $_GET['topic'];
				$Submission = SpeakerSubmission::get()->byID($SubmissionID);
				$Submission->MainTopic = $MainTopic;
				$Submission->write();
				$this->redirect($this->Link().'#'.$Submission->ID);
			} else {
				user_error('Setting the main topic requires a submission id and a topic.');
			}
		}		


		public function EmailSpeakers() {
			
			// Pull all the speaker submissions

			$SpeakerSet = SpeakerSubmission::get()->filter(array('BeenEmailed' => 0,'DisplayOnSite' => 1))->sort('BeenEmailed','ASC')->limit(250);

			// Iterate through to send an email to each speaker
	      	foreach ($SpeakerSet as $item) {

	      		if ($item->BeenEmailed != TRUE && EmailUtils::validEmail($item->Email)) {

	      			//Send email to submitter
		    		$To = $item->Email;
					$Subject = "Your OpenStack Summit Presentation Submission";     
		    		$email   = EmailFactory::getInstance()->buildEmail(SPEAKER_EMAIL_FROM, $To, $Subject);
		    		$email->setTemplate('SpeakerSubmissionVoterEmail');
		    		$email->populateTemplate($item);
		    		$email->send();

		    		// Set flag in DB that this speaker has been emailed
		         	$item->BeenEmailed = TRUE;
			    	$item->write();
					echo "Email sent to " . $item->Email . "<br/>";
		    	}
	      	} 

	      	return "Speakers emailed successfully.";

		}

		public function RemoveStyleTags() {

			$SpeakerSet = SpeakerSubmission::get()->sort("LastName","ASC");

			foreach ($SpeakerSet as $item) {

				// Remove Style Tags from HTML
				$item->Bio = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $item->Bio);
				$item->Abstract = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $item->Abstract);
				$item->write();

			}

			echo "Done removing Style Tags.";


		}

		public function SetOneTopic() {
			
				$submissions = SpeakerSubmission::get();

				foreach ($submissions as $submission) {
						if($submission->Topic) {
							$topics = explode(",", $submission->Topic);
							if (count($topics) == 1) {
								$submission->MainTopic = $topics[0];
								$submission->Write();
							}
						}
				}
		}
				
	}
