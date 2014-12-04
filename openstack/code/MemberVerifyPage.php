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
	class MemberVerifyPage extends Page {
		static $db = array(
		);
		static $has_one = array(
	     );
	}

	class MemberVerifyPage_Controller extends Page_Controller {

		static $allowed_actions = array(
			'member',
		);

		function init() {
			parent::init();
		}

		public function member() {

			$EmailAddress = "";
			$Member = "";
			$APPSEC = "yEKVvoxEdGPxevE2";  // Shared secret to mitigate spamming attacks

			// Make sure the access is POST, not GET
			if(!$this->request->isPOST())  return $this->httpError(403, 'Access Denied.'); 

			// Make sure the APPSEC shared secret matches
			if($this->request->postVar('APPSEC') != $APPSEC)  return $this->httpError(403, 'Access Denied.');

			// Pull email address from POST variables
			$EmailAddress = $this->request->postVar('email');
			// Sanitize the input
			$EmailAddress = convert::raw2sql($EmailAddress);

			// If an email address was provided, try to find a member with it
			if($EmailAddress) {
				$Member = 	Member::get()->filter('Email',$EmailAddress)->first();
			}

			$response = new SS_HTTPResponse();

			// If a member was found return status 200 and 'OK'
			if ($Member && $Member->isFoundationMember()) {
				$response->setStatusCode(200);
				$response->setBody('OK');
				$response->output();
			} elseif ($EmailAddress) {
				$response->setStatusCode(404);
				$response->setBody('No Member Found.');
				$response->output();
			} else {
				$response->setStatusCode(500);
				$response->setBody('An error has occurred retrieving a member.');
				$response->output();				
			}

		}	
	}
