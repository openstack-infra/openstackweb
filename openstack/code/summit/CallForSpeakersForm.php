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
class CallForSpeakersForm extends HoneyPotForm {
 
   function __construct($controller, $name) {

      $CurrentSummitID = $controller->parent()->Summit()->ID;
      $SummitCategories = SummitCategory::get()->filter('SummitID', $CurrentSummitID);

      $SummitCategoriesMap = NULL;
      if ($SummitCategories) {
         // Create a map with descriptions for radio buttons
         $SummitCategoriesDescriptionMap = $SummitCategories->map('Name', 'Name');
         // Create a map with titles only for main topic dropdown
         $SummitCategoriesMap = $SummitCategories->map('Name', 'ShortPhrase');
      }


      $SummitTalkTags = SummitTalkTag::get()->filter('SummitID',$CurrentSummitID);

      $SummitTalkTagsDescriptionMap = NULL;
      if ($SummitTalkTags) {
         // Create a map with descriptions for the pulldown
         $SummitTalkTagsDescriptionMap = $SummitTalkTags->map('Name', 'Name');
      }
                           
      // Presentation Topic
      $TopicField = new OptionsetField('Topic', 'What is the general topic of your presentation? (Select the best fit)', $SummitCategoriesDescriptionMap);

      // Other Field
      $OtherField = new TextField('OtherTopic', 'Other Topic (if one above does not match)'); 

      // Presentation Tag
      $TagField = new DropdownField('Tag', 'Please select a level for your presentation content', $SummitTalkTagsDescriptionMap);


      // Main Topic
      /* $MainTopicField = new DropdownField(
            'MainTopic',
            'If you selected more than one topic, which would you consider your primary topic?',
            $SummitCategoriesMap
      ); */

      // Proposed Title & Abstract
      $ProposedTitleField = new TextField('PresentationTitle', 'Proposed Presentation Title');
      $AbstractField = new HtmlEditorField('Abstract','Presentation Abstract');

      $TalkIDField = new HiddenField('TalkID');

      $Params = $controller->getURLParams();
      if(isset($Params['ID']) && is_numeric($Params['ID'])) {
         $TalkIDField->setValue($Params['ID']);
      }

      $fields = new FieldList(
         $ProposedTitleField,
         $TopicField,
         $OtherField,
         $TagField,
         $AbstractField,
         $TalkIDField
      );
      

	  $actions = new FieldList(FormAction::create("saveAction")->setTitle( 'Save & Continue'));

      $validator = new RequiredFields(
         'Abstract',
         'PresentationTitle'
      );

	  Requirements::customScript('
	      tinymce.init({
            mode: "textareas",
            resize: false,
            menubar: false,
            statusbar: false,
            setup : function(ed) {
               ed.onChange.add(function(ed, l) {
                    tinymce.triggerSave();
                });
            }
        });
      ');

      parent::__construct($controller, $name, $fields, $actions, $validator);


   }
 
   function forTemplate() {
      return $this->renderWith(array(
         $this->class,
         'Form'
      ));
   }
   
   function saveAction($data, $form) {

            $getVars = $form->controller()->request->getVars();

            if(!Member::currentUser()) {
               $form->sessionMessage('Sorry, you must log in to create and edit speaker submissions.','bad');
               Controller::curr()->redirectBack();
               return;               
            }

            // Make sure at least one topic was selected
            if(!isset($data['Topic']) && $data['OtherTopic'] == NULL) {
               $form->sessionMessage('You need to select at least one topic (or provide an alternative topic).','bad');
	           Controller::curr()->redirectBack();
               return;
            }

            // Look to see if we're editing a current talk or creating a new one
            if($data['TalkID'] != NULL) {

               if(!is_numeric($data['TalkID'])) {
                  return $this->httpError(400, 'That talk could not be found.');
               } else {
                  $SpeakerSubmission = Talk::get()->byID((int)$data['TalkID']);
               }

               if (!$SpeakerSubmission->CanEdit(Member::currentUser()->ID)) {
                  return $this->httpError(401, 'Sorry, you do not have permission to edit this talk.');
               }

            } else {

               if(!$form->controller()->PastSubmissionDeadline() || Permission::check('ADMIN'))
               {
                  $SpeakerSubmission = new Talk();
                  $SpeakerSubmission->OwnerID = Member::currentUser()->ID;
                  $SpeakerSubmission->SummitID = $this->controller()->parent()->Summit()->ID;

                  // Hide talks if requested in the URL
                  if(Session::get('HiddenTalk') == 1) $SpeakerSubmission->MarkedToDelete = TRUE;

               } else {
                  return $this->httpError(401, 'Sorry, it is past the submission deadline for new talks.');
               } 
            
            }

			if(isset($data['Topic'])){
				$category = SummitCategory::get()->filter( array( 'SummitID' => $this->controller()->parent()->Summit()->ID, 'Name'=>$data['Topic']))->first();
				if($category)
					$SpeakerSubmission->SummitCategoryID = $category->ID;
			}

            $form->saveInto($SpeakerSubmission);
            $SpeakerSubmission->write();

            // Mark that a new talk was added (to email admin after complete)
            Session::set('NewTalkAdded',TRUE);

            // Redirect back to the page with a success message
            Controller::curr()->redirect($form->controller()->Link().'SpeakerList/'.$SpeakerSubmission->ID);
            
   }
      
  
}