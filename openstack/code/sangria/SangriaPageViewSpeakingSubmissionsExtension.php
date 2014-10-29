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
 * Class SangriaPageViewSpeakingSubmissionsExtension
 */
final class SangriaPageViewSpeakingSubmissionsExtension
extends Extension {

	public function onBeforeInit(){

		Config::inst()->update(get_class($this), 'allowed_actions',array(
			'ViewSpeakingSubmissions',
		));

		Config::inst()->update(get_class($this->owner), 'allowed_actions',array(
			'ViewSpeakingSubmissions',
		));
	}

	// Speaking Submissions
	function SpeakingSubmissions() {
		$submissions = DataObject::get("SpeakerSubmission","Created > '2012-11-01'","Created desc");
		SangriaPage_Controller::$submissionsCount = $submissions->Count();
		return $submissions;
	}

	function SpeakingSubmissionCount() {
		$this->SpeakingSubmissions();
		return SangriaPage_Controller::$submissionsCount;
	}
}