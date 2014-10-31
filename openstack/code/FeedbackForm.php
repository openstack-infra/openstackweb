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

class FeedbackForm extends Form {
 
   function __construct($controller, $name) {
   

		$FeedbackField = new TextAreaField('Content', 'My Feedback About This Page');
   
		$fields = new FieldList(
		     $FeedbackField
		);

      $tellUsButton = new FormAction('submitFeedback', 'Tell Us');
      $tellUsButton->addExtraClass('button');
	 
       $actions = new FieldList(
          $tellUsButton
       );
   
      parent::__construct($controller, $name, $fields, $actions);
   }
 
   function forTemplate() {
      return $this->renderWith(array(
         $this->class,
         'Form'
      ));
   }
   
   function submitFeedback($data, $form) {

      // TRUE if the submission contains a link. Crude spam mitigation.
      $ContainsLink = strpos($data['Content'], "http://") !== false;

      if ($data['Content'] != NULL && !$ContainsLink) {
            $FeedbackSubmission = new FeedbackSubmission();
            $form->saveInto($FeedbackSubmission);

            // Tie the URL of the current page to the feedback submission
            $page = Director::get_current_page();
            $FeedbackSubmission->Page = $page->Link();

            //Send email alert about submission
            $Subject = "New Website Feedback Submission";
            $email = EmailFactory::getInstance()->buildEmail(FEEDBACK_FORM_FROM_EMAIL, FEEDBACK_FORM_TO_EMAIL, $Subject);
            $email->setTemplate("FeedbackSubmissionEmail");
            $email->populateTemplate($FeedbackSubmission);
            $email->send();

            // Redirect back to the page with a success message
            $form->controller->setMessage('Success', 'Thanks for providing feedback to improve the OpenStack website!');
            $form->controller->redirectBack();
            
         } else {

            $form->controller->setMessage('Error', "Oops! It doesn't look like you provided any feedback. Please check the form and try again.");
            $form->controller->redirectBack();
         }
   }

}