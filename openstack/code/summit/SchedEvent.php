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
class SchedEvent extends DataObject {

	static $db = array(
		'event_key' => 'Varchar',
		'eventtitle' => 'Text',
		'event_start' => 'Datetime',
		'event_end' => 'Datetime',
		'event_type' => 'Varchar',
		'description' => 'Text',
		'speakers' => 'Text'
	);

	static $singular_name = 'Event';
	static $plural_name = 'Events';

	function Metadata() {
		return SchedEventMetadata::get()->filter('event_key',$this->event_key)->first();
	}

	function IsASpeaker($SpeakerID) {
		if(is_numeric($SpeakerID)) {

			$Speaker = SchedSpeaker::get()->byID($SpeakerID);

			// Check to see if the speaker is listed on this event
			if( $Speaker &&
				$Speaker->name && 
				strpos($this->speakers, $Speaker->name) !== FALSE ) 
			{
				return TRUE;
			}	
		}
	}

	function UploadedMedia() {

		$Metadata = $this->Metadata();

		if($Metadata && $Metadata->UploadedMediaID) {
			$File = File::get()->byID($Metadata->UploadedMediaID);
			return $File;
		}
	}
	
	function HostedMediaURL() {
		$Metadata = $this->Metadata();
		if($Metadata) return $Metadata->HostedMediaURL;
	}

	function isFile() {
		$Metadata = $this->Metadata();
		if($Metadata) return $Metadata->MediaType == 'File';
	}

	function HasAttachmentOrLink() {
		$Metadata = $this->Metadata();
		if($Metadata) return ($Metadata->MediaType || $Metadata->HostedMediaURL);
	}


}