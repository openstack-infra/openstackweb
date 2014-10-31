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
class ATCMember extends DataObject {

	static $db = array(
		'Username' => 'Text',
		'Name' => 'Text',
		'Email' => 'Text',
		'AltEmail' => 'Text',		
		'City' => 'Text',
		'Country' => 'Text',
	);
	

	function IsFoundationMember() {

		// Look to see if there's a foundation member using the first email address
		$FoundationMember = Member::get()->filter('Email',$this->Email)->first();

		// If not a match by the first address, look under the second address
		if(!$FoundationMember) {
			$FoundationMember = Member::get()->filter('Email',$this->AltEmail)->first();
		}

		return $FoundationMember;

	}

	function LoadCityCountry() {

		if($FoundationMember = $this->IsFoundationMember()) {
			$this->City = $FoundationMember->City;
			$this->Country = $FoundationMember->Country;
			$this->write();
		}

	}

}