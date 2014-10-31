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
class DeploymentSurveyAppDevSurveyForm extends Form {

   function __construct($controller, $name) {

      // Define fields //////////////////////////////////////

      $fields = new FieldList (
        new LiteralField('Break', ColumnFormatter::$left_column_start),
        new CheckboxSetField(
            'Toolkits',
            'What toolkits do you use or plan to use to interact with the OpenStack API?',
            ArrayUtils::AlphaSort(AppDevSurvey::$toolkits_options,null,array('Other' => 'Other (please specify)'))),
        new TextField('OtherToolkits','Other toolkits'),

        new LiteralField('Break', ColumnFormatter::$right_column_start),
        new CheckboxSetField('ProgrammingLanguages',
            'If you wrote your own code for interacting with the OpenStack API, what programming language did you write it in?',
            ArrayUtils::AlphaSort(AppDevSurvey::$languages_options,null,array('Other' => 'Other (please specify)'))),
        new TextField('OtherProgrammingLanguages','Other programming languages'),

        new LiteralField('Break', ColumnFormatter::$end_columns),

        new LiteralField('Break', ColumnFormatter::$left_column_start),
        new CheckboxSetField('APIFormats',
            'If you wrote your own code for interacting with the OpenStack API, what API format are you using?',
            ArrayUtils::AlphaSort( AppDevSurvey::$api_format_options)),
        new CheckboxSetField(
            'OperatingSystems',
            'What operating systems are you using or plan on using to develop/deploy your applications?',
            ArrayUtils::AlphaSort( AppDevSurvey::$opsys_options, null, array(	'Other' => 'Other (please specify)'))),
        new TextField('OtherOperatingSystems','Other operating systems'),
        new CheckboxSetField('DevelopmentEnvironments',
            'What development environment do you use or plan to use?',
            ArrayUtils::AlphaSort( AppDevSurvey::$ide_options,null, array('Other' => 'Other (please specify)'))),
        new TextField('OtherDevelopmentEnvironments','Other development environments'),

        new LiteralField('Break', ColumnFormatter::$right_column_start),
        new CheckboxSetField(
            'ConfigTools',
            'What tools are you using or plan on using to deploy/configure your applications?',
            ArrayUtils::AlphaSort( AppDevSurvey::$config_tool_options,null, array(	'Other' => 'Other (please specify)'))),
        new TextField('OtherConfigTools','Other configuration/deployment tools'),

        new CheckboxSetField(
	        'InteractionWithOtherClouds',
	        'Are you interacting with other clouds in your IT environment?',
	        ArrayUtils::AlphaSort(AppDevSurvey::$interaction_with_other_clouds__options)),
        new LiteralField('Break', ColumnFormatter::$end_columns),
        new LiteralField('Break', '<hr/>'),
        new LiteralField('Break', '<p>Please share your thoughts with us on the state of applications on OpenStack. Here are some questions to consider:
        <ol>
 <li>What do you struggle with when developing and deploying applications on OpenStack?</li>
 <li>What’s missing that would make your life easier?</li>
 <li>Why did/didn’t you choose a particular tool?</li>
 </ol></p>'),
        new TextAreaField('StateOfOpenStack','Your thoughts on the state of applications on OpenStack:'),
        new TextAreaField('DocsPriority','What is your top priority in evaluating API and SDK docs?')
      );

      // $prevButton = new CancelFormAction($controller->Link().'Login', 'Previous Step');
      $nextButton = new FormAction('SaveAppDevSurvey', '  Next Step  ');

      $actions = new FieldList(
          $nextButton
      );

      // Create Validators
      $validator = new RequiredFields();

      parent::__construct($controller, $name, $fields, $actions, $validator);

        if ($AppDevSurvey = $this->controller->LoadAppDevSurvey()) {
            $this->loadDataFrom($AppDevSurvey->data());
        }
   }


    public function SaveAppDevSurvey($data, $form)
    {
        $survey = $form->controller->GetCurrentSurvey();
        $AppDevSurvey = $form->controller->LoadAppDevSurvey();

        // If a deployment wasn't returned, we'll create a new one
        if (!$AppDevSurvey) {
            $AppDevSurvey = new AppDevSurvey();
            $AppDevSurvey->MemberID = Member::currentUser()->ID;
            $AppDevSurvey->DeploymentSurveyID = $survey->ID;
        }

        $form->saveInto($AppDevSurvey);
        $AppDevSurvey->write();

        $survey->CurrentStep        = 'Deployments';
        $survey->HighestStepAllowed = 'Deployments';
	    $survey->UpdateDate         = SS_Datetime::now()->Rfc2822();
        $survey->write();

	    Controller::curr()->redirect($form->controller->Link() . 'Deployments');
    }

   function forTemplate() {
      return $this->renderWith(array(
         $this->class,
         'Form'
      ));
   }

}
