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
class CandidateApplicationForm extends HoneyPotForm {
 
   function __construct($controller, $name) {

      // Define fields //////////////////////////////////////
   
      $fields = new FieldList (
        new TextAreaField('Bio',"Provide Brief Biography of Yourself"),
        new TextAreaField('RelationshipToOpenStack',"What is your relationship to OpenStack, and why is its success important to you? What would you say is your biggest contribution to OpenStack's success to date?"),
        new TextAreaField('Experience',"Describe your experience with other non profits or serving as a board member. How does your experience prepare you for the role of a board member?"),
        new TextAreaField('BoardsRole',"What do you see as the Board's role in OpenStack's success?"),
        new TextAreaField('TopPriority',"What do you think the top priority of the Board should be in 2014?")
      );

      $actionButton = new FormAction('save', 'Save Candidate Application');
      //$actionButton->addExtraClass('btn green-btn');
	 
       $actions = new FieldList(
          $actionButton
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