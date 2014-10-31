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
	class DirectAfterLoginPage extends Page {
		static $db = array(
		);
		static $has_one = array(
	     );
		static $defaults = array(
       		'ShowInMenus' => false
    	);

	}

	class DirectAfterLoginPage_Controller extends Page_Controller {
		function init() {
			parent::init();
		}		

		function FoundationMember() {
			$currentMember = Member::currentUser();
			// see if the member is inu the foundation group
			if ($currentMember && $currentMember->inGroup(5)) return TRUE;
		}

		function CallForSpeakersLink() {
			// Find the call for speakers page for the current summit
			$SummitPage = Page::get()->filter('URLSegment','summit')->first();
			$URL = $SummitPage->Link() . 'call-for-speakers/';
			return $URL;
		}


	}