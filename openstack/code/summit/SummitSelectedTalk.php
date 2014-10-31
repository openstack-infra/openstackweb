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
class SummitSelectedTalk extends DataObject {

	static $db = array(
		'Order' => 'Int'
	);
	
	static $has_one = array(
		'SummitSelectedTalkList' => 'SummitSelectedTalkList',
		'Talk' => 'Talk',
		'Member' => 'Member'
	);

	function TalkPosition() {

		$Talks = SummitSelectedTalk::get()->filter(array('SummitSelectedTalkListID' => $this->SummitSelectedTalkList()->ID, 'Order:not' => 0))->sort('Order','ASC');
		$TalkPosition = 0;

		$counter = 1;

		if($Talks) {
			foreach($Talks as $Talk) {
				if ($Talk->ID == $this->ID) {
					$TalkPosition = $counter;
				}
				$counter = $counter + 1;
			}
		}

		$Talks = SummitSelectedTalk::get()->filter(array('SummitSelectedTalkListID' => $this->SummitSelectedTalkList()->ID, 'Order' => 0))->sort('Order','ASC');
		
		if($Talks) {
			foreach($Talks as $Talk) {
				if ($Talk->ID == $this->ID) {
					$TalkPosition = $counter;
				}
				$counter = $counter + 1;
			}
		}


		return $TalkPosition;

	}

	function IsAlternate() {
		$TalkList = $this->SummitSelectedTalkList();
		$currentNum = $TalkList->SummitSelectedTalks()->Count();
		$maxNum = $this->Talk()->SummitCategory()->NumSessions;

		if($currentNum > $maxNum && ($this->TalkPosition() > $maxNum)) return TRUE;

	}

}