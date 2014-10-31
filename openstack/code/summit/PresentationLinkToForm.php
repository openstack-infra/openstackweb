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
class PresentationLinkToForm extends Form {

   function __construct($controller, $name) {

            $LinkField = new TextField('HostedMediaURL','Link (URL) for your online presentation:');

             $fields = new FieldList(
                     $LinkField
             );
             $actions = new FieldList(
                     new FormAction('saveLink', 'Save Link')
             );
             $validator = new RequiredFields(array('HostedMediaURL'));

             parent::__construct($controller, $name, $fields, $actions, $validator);

     }

     function forTemplate() {
        return $this->renderWith(array(
           $this->class,
           'Form'
        ));
     }

     function saveLink($data, $form) {

       $url = $data['HostedMediaURL'];

       $EventID = Session::get('UploadMedia.PresentationID');
       if($EventID) $Event  = SchedEvent::get()->byID($EventID);
       if($Event) $Metadata = SchedEventMetadata::get()->filter('event_key', $Event->event_key)->first();
       
       // If this event exists but has no metadata, create a new record and link it to the event.
       if($Event && !$Metadata) {
            $Metadata = new SchedEventMetadata();
            $Metadata->event_key = $Event->event_key;
        }

        // Attach a protocol if needed
        if(substr($url,0,7) != 'http://' && substr($url,0,8) != 'https://') $url = 'http://'.$url;

        if(!filter_var($url, FILTER_VALIDATE_URL))
        {
            $form->sessionMessage('That does not appear to be a valid URL','bad'); 
            return $this->controller()->redirectBack(); 
        } elseif(!$Event || !$Metadata) {
            $data["HasError"] = TRUE;
            return $this->controller()->Customise($data);
        } else {
            $Metadata->HostedMediaURL = $url;
            $Metadata->MediaType = 'URL';
            $Metadata->write();
            Session::set('UploadMedia.Success', TRUE);
            Session::set('UploadMedia.URL', $url);
            Session::set('UploadMedia.Type', 'URL');

	        Controller::curr()->redirect($form->controller()->link().'Success');

        }

     }

}