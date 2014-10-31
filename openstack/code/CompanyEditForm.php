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
class CompanyEditForm extends Form
{

    function __construct($controller, $name, $company)
    {
        // Define fields //////////////////////////////////////
        if ($company->canEditProfile()) {

	        $fields = new FieldList (
                new TextField('Name', 'Company Name'),
                new TextField ('URL', 'Company Web Address (URL)'),
                new LiteralField('Break', '<p></p>'),
                new LiteralField('Break', '<hr/>'),
                $big_logo   = new CustomUploadField('BigLogo', 'Large Company Logo'),
	            $small_logo = new CustomUploadField('Logo', 'Small Company Logo'),
                new LiteralField('Break', '<p></p>'),
                new LiteralField('Break', '<hr/>'),
                new TextField('Industry', 'Industry (less than 4 Words)'),
                $desc = new HtmlEditorField('Description', 'Company Description'),
                new LiteralField('Break', '<p></p>'),
                $contrib = new HtmlEditorField('Contributions', 'How you are contributing to OpenStack (less than 150 words)'),
                new LiteralField('Break', '<p></p>'),
                $products = new HtmlEditorField('Products', 'Products/Services Related to OpenStack (less than 100 words)'),
	            new LiteralField('Break', '<p></p>'),
                new LiteralField('Break', '<p></p>'),
                new ColorField("Color","Company Color"),
                new LiteralField('Break', '<p></p>'),
                new LiteralField('Break', '<hr/>'),
                new TextField('ContactEmail', 'Best Contact email address (optional)'),
                new LiteralField('Break', '<p>This email address will be displayed on your profile and may be different than your own address.')
            );
            $desc->addExtraClass("company-description");
            $contrib->addExtraClass("company-contributions");
            $products->addExtraClass("company-products");

	        $big_logo_validator = new Upload_Image_Validator();
	        $big_logo_validator->setAllowedExtensions(array('jpg','png','jpeg'));
	        $big_logo_validator->setAllowedMaxImageWidth(500);
	        $big_logo->setCanAttachExisting(false);
	        $big_logo->setAllowedMaxFileNumber(1);
	        $big_logo->setAllowedFileCategories('image');
	        $big_logo->setCanPreviewFolder(false); // Don't show target filesystem folder on upload field
	        $big_logo->setFolderName('companies/main_logo');
	        $big_logo->setValidator($big_logo_validator);

	        $small_logo_validator = new Upload_Image_Validator();
	        $small_logo_validator->setAllowedExtensions(array('jpg','png','jpeg'));
	        $small_logo_validator->setAllowedMaxImageWidth(200);
	        $small_logo->setCanAttachExisting(false);
	        $small_logo->setAllowedMaxFileNumber(1);
	        $small_logo->setAllowedFileCategories('image');
	        $small_logo->setCanPreviewFolder(false); // Don't show target filesystem folder on upload field
	        $small_logo->setFolderName('companies/main_logo');
	        $small_logo->setValidator($small_logo_validator);

        } else if ($company->canEditLogo()) {
            $fields = new FieldList (
                new ReadonlyField('Name', 'Company Name'),
                new ReadonlyField ('URL', 'Company Web Address (URL)'),
                new LiteralField('Break', '<p></p>'),
                new LiteralField('Break', '<hr/>'),
                new CustomUploadField('BigLogo', 'Large Company Logo'),
                new CustomUploadField('Logo', 'Small Company Logo')
            );
        }
        $actionButton = new FormAction('save', 'Save Changes');
        //$actionButton->addExtraClass('btn green-btn');

        $actions = new FieldList(
            $actionButton
        );


        parent::__construct($controller, $name, $fields, $actions);

    }

    function forTemplate()
    {
        return $this->renderWith(array(
            $this->class,
            'Form'
        ));
    }

}