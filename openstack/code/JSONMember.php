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
  /**
   * Defines the JobPage page type
   */
  class JSONMember extends DataObject {
     static $db = array(
      	'FirstName' => 'Text',
      	'Surname' => 'Text',
      	'IRCHandle' => 'Text',
        'TwitterName' => 'Text',
        'Email' => 'Text',
        'SecondEmail' => 'Text',
        'ThirdEmail' => 'Text',
        'OrgAffiliations' => 'Text',
        'untilDate' => 'Date'
  	);
    
    static $has_one = array(
     );

    Static $defaults = array(
      'untilDate' => NULL
    ); 

    public function canView($member = null) { 
        return true; 
    }

}