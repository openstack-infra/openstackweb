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
class SummitSelectedTalkList extends DataObject {

	static $db = array(
		'Name' => 'Text'
	);
	
	static $has_one = array(
		'SummitCategory' => 'SummitCategory'
	);

	static $has_many = array(
		'SummitSelectedTalks' => 'SummitSelectedTalk'
	);

	function SortedTalks() {
      return SummitSelectedTalk::get()->filter(array( 'SummitSelectedTalkListID' => $this->ID, 'Order:not' => 0))->sort('Order','ASC');
    }

	function UnsortedTalks() {
      return SummitSelectedTalk::get()->filter(array('SummitSelectedTalkListID' => $this->ID,  'Order' => 0))->sort('Order','ASC');
    }

    function UnusedPostions() {

      // Define the columns
      $columnArray = array();

      $NumSlotsTaken = $this->SummitSelectedTalks()->Count();
      $NumSlotsAvailable = $this->SummitCategory()->NumSessions - $NumSlotsTaken;

      $list = new ArrayList();


      for ($i = 0; $i < $NumSlotsAvailable; $i++) {
      	$data = array('Name' => 'Available Slot');
      	$list->push(new ArrayData($data));
      }

      return $list; 

    }

}