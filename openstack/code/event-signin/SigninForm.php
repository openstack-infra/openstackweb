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
class SigninForm extends Form {
 
   function __construct($controller, $name) {
   
   
	   	// Name Set
		$FirstNameField = new TextField('FirstName', 'First Name'); 
		$FirstNameField->addExtraClass('input-text');
		$LastNameField = new TextField('LastName', 'Last Name'); 
		$LastNameField->addExtraClass('input-text');
				
		// Email
		$EmailAddressField = new EmailField('EmailAddress', 'Email Address');
		$EmailAddressField->addExtraClass('input-text');	
   
		$fields = new FieldList(
		     $FirstNameField,
		     $LastNameField,
		     $EmailAddressField
		);
	 
       $actions = new FieldList(
          new FormAction('doSigninForm', 'Sign Up')
       );
   
      parent::__construct($controller, $name, $fields, $actions);
      
      // Create Validators
      $validator = new RequiredFields('FirstName');
      $validator = new RequiredFields('LastName');
      $validator = new RequiredFields('EmailAddress');
      
      $this->disableSecurityToken();
      
      
   }
 
   function forTemplate() {
      return $this->renderWith(array(
         $this->class,
         'Form'
      ));
   }
 
   function submit($data, $form) {
      // do stuff here
   }
 
}