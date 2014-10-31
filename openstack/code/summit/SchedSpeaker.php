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
class SchedSpeaker extends DataObject {

	static $db = array(
		'username' => 'Varchar',
		'name' => 'Varchar',
		'email' => 'Varchar'
	);

	static $singular_name = 'Speaker';
	static $plural_name = 'Speakers';

	function PresentationsForThisSpeaker() {

		$Presentations = SchedEvent::get()->filter('speakers:PartialMatch',$this->name);
		return $Presentations;
	}

	function PresentationsWithoutMedia() {

		$Presentations = SchedEvent::get()->filter('speakers:PartialMatch',$this->name);

		$MissingMedia = FALSE;

		if($Presentations) {

			// Loop over each presentation and see if this speaker has one without either a link or an uploaded file.
			foreach ($Presentations as $Presentation) {
				if (!$Presentation->HostedMediaURL() && !$Presentation->UploadedMedia()) $MissingMedia = TRUE;
			}

		}

		return $MissingMedia;
	}

	function SpeakerHash() {
	        $prefix = "000";
	        $hash = base64_encode($prefix . $this->username);

	        return $hash;
	}

	function HashToUsername($hash) {
		return substr(base64_decode($hash),3);
	}

	// Look to see if a presenter has a general session or a keynote.
	function GeneralOrKeynote() {
		$Presentations = $this->PresentationsForThisSpeaker();
		if(!$Presentations) return FALSE;

		foreach ($Presentations as $Presentation) {
			if($Presentation->event_type == 'General Session' || $Presentation->event_type == 'Keynotes') return TRUE;
			break;
		}
	}
	
}