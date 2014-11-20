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
 * Class NewsRequestForm
 */
final class NewsRequestForm extends HoneyPotForm {

	function __construct($controller, $name, $article = null, bool $is_manager, $use_actions = true) {
        $IDField = new HiddenField('newsID');
		//madatory fields
		$HeadlineField = new TextField('headline','Headline');
		$SummaryField = new TextareaField('summary','Summary',2,2);
        $CityField = new TextField('city','City');
        $StateField = new TextField('state','State');
        $CountryField = new TextField('country','Country');
		$TagsField = new TextField('tags','Tags');
		$DateField = new TextField('date','Date of Article/Release');
        $DateField->addExtraClass('date inline');
        if ($is_manager) {
            $DateEmbargoField = new TextField('date_embargo','Embargo <br> Date');
            $DateEmbargoField->addExtraClass('date inline');
            $DateExpireField = new TextField('date_expire','Expire Date');
            $DateExpireField->addExtraClass('date');
        }

        $UpdatedField = new DatetimeField_Readonly('date_updated','Last <br> Updated');
        $UpdatedField->addExtraClass('inline');
        //optional fields
        $BodyField = new TextareaField('body','Body');
        $LinkField = new TextField('link','Link');
        $DocumentField = new FileField('Document','Document');
        $DocumentField->addExtraClass('hidden');
        $ImageField = new CustomSimpleImageField('Image', 'Image');

        if($article) {
            $IDField->setValue($article->ID);
            $HeadlineField->setValue($article->Headline);
            $SummaryField->setValue($article->Summary);
            $CityField->setValue($article->City);
            $StateField->setValue($article->State);
            $CountryField->setValue($article->Country);
            $TagsField->setValue($article->getTagsCSV());
            $DateField->setValue($article->Date);
            $DateEmbargoField->setValue($article->DateEmbargo);
            $UpdatedField->setValue($article->LastEdited);
            $BodyField->setValue($article->Body);
            $LinkField->setValue($article->Link);
            $DateExpireField->setValue($article->DateExpire);
            //submitter read only
            $SubmitterFirstNameField = new ReadonlyField('submitter_first_name','First Name');
            $SubmitterLastNameField = new ReadonlyField('submitter_last_name','Last Name');
            $SubmitterEmailField = new ReadonlyField('submitter_email','Email');
            $SubmitterCompanyField = new ReadonlyField('submitter_company','Company');
            $SubmitterPhoneField = new ReadonlyField('submitter_phone','Phone');

            $SubmitterFirstNameField->setValue($article->getSubmitter()->FirstName);
            $SubmitterLastNameField->setValue($article->getSubmitter()->LastName);
            $SubmitterEmailField->setValue($article->getSubmitter()->Email);
            $SubmitterCompanyField->setValue($article->getSubmitter()->Company);
            $SubmitterPhoneField->setValue($article->getSubmitter()->Phone);
        } else {
            // submitter fields
            $SubmitterFirstNameField = new TextField('submitter_first_name','First Name');
            $SubmitterLastNameField = new TextField('submitter_last_name','Last Name');
            $SubmitterEmailField = new TextField('submitter_email','Email');
            $SubmitterCompanyField = new TextField('submitter_company','Company');
            $SubmitterPhoneField = new TextField('submitter_phone','Phone');
        }

        $fields = new FieldList (
            $IDField,
            $HeadlineField,
            $SummaryField,
            $CityField,
            $StateField,
            $CountryField,
            $TagsField,
            $DateField
        );

        if ($is_manager) {
            $fields->push($DateEmbargoField);
            $fields->push($UpdatedField);
        }

        $fields->push(new LiteralField('clear', '<div class="clear"></div>'));
        $fields->push($BodyField);
        $fields->push($LinkField);
        $fields->push($DocumentField);


        if ($article) {
            $image = $article->Image();
            $document = $article->Document();
            if ($document->exists()) {
                $fields->push(new LiteralField('image_preview', $document->CMSThumbnail()));
            }
            $fields->push(new LiteralField('break', '<br/>'));
            $fields->push($ImageField);
            if ($image->exists()) {
                $fields->push(new LiteralField('break', '<br/>'));
                $fields->push(new LiteralField('image_preview', $image->getFormattedImage('croppedimage',150,100)));
            }
        } else {
            $fields->push(new LiteralField('break', '<br/>'));
            $fields->push($ImageField);
        }

        if ($is_manager) {
            $fields->push(new LiteralField('break', '<br/>'));
            $fields->push($DateExpireField);
        }

        $fields->push(new LiteralField('break', '<br/><hr/>'));
        $fields->push(new LiteralField('title', '<h2>Submitter</h2>'));
        $fields->push($SubmitterFirstNameField);
        $fields->push($SubmitterLastNameField);
        $fields->push($SubmitterEmailField);
        $fields->push($SubmitterCompanyField);
        $fields->push($SubmitterPhoneField);


		// Create action
		$actions = new FieldList();
	    $actions->push(new FormAction('saveNewsArticle', 'Save'));

		// Create validators
		$validator = new ConditionalAndValidationRule(array(new RequiredFields('headline','summary','tags','date')));
	    $this->addExtraClass('news-registration-form');
		parent::__construct($controller, $name, $fields, $actions, $validator);
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