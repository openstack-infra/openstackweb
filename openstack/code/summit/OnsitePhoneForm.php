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
class OnsitePhoneForm extends Form {
 
   function __construct($controller, $name, $speakerHash) {
   

		$PhoneField = new TextField('PhoneNumber', 'Your Onsite Phone Number in Hong Kong');

    // Speaker Hash Field
    $SpeakerHashField = new HiddenField('speakerHash', "speakerHash", $speakerHash); 
   
		$fields = new FieldList(
		     $PhoneField,
         $SpeakerHashField
		);

      $submitButton = new FormAction('doSavePhoneNumber', 'Save');
	 
       $actions = new FieldList(
          $submitButton
       );
   

      $validator = new RequiredFields('PhoneNumber');
      parent::__construct($controller, $name, $fields, $actions, $validator);

   }
 
   function forTemplate() {
      return $this->renderWith(array(
         $this->class,
         'Form'
      ));
   } 

   function doSavePhoneNumber($data, $form) {

      if(isset($data['speakerHash'])) $hashKey = Convert::raw2sql($data['speakerHash']);
      if(isset($hashKey)) $speakerID = substr(base64_decode($hashKey),3);

      if(isset($speakerID) &&  is_numeric($speakerID) && isset($data['PhoneNumber']) && $data['PhoneNumber'] != '' && $Speaker = Speaker::get()->byID($speakerID))
      {
        $Speaker->OnsiteNumber = Convert::raw2sql($data['PhoneNumber']);
        $Speaker->write();
        Controller::curr()->redirect(Controller::curr()->Link().'PhoneNumberSaved/');
      }


   }
  
}