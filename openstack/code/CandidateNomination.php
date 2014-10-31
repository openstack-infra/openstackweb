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
class CandidateNomination extends DataObject {

	static $db = array(
	);
	
	static $has_one = array(
		'Member'    => 'Member', # Who made the nomination
		'Candidate' => 'Member', # Which candidate was nominated
		'Election'  => 'ElectionPage' # Which election the nomination was for
	);

	function getVotingMember() {
		return Member::get()->byID($this->MemberID);
	}

	function getNominee() {
		return Candidate::get()->filter('MemberID',$this->CandidateID)->first();
	}

}