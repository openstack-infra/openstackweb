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
class PresentationVotingSearchForm extends Form {
 
   function __construct($controller, $name) {
   

		$SearchField = new TextField('Search', 'Search');
   
		$fields = new FieldList(
		     $SearchField
		);

      $searchButton = new FormAction('doSearch', 'Search');
      $searchButton->addExtraClass('button');
	 
       $actions = new FieldList(
          $searchButton
       );
   
      parent::__construct($controller, $name, $fields, $actions);
   }
 
   function forTemplate() {
      return $this->renderWith(array(
         $this->class,
         'Form'
      ));
   }  
  
}