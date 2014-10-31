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
 * Class OsLogoProgramForm
 */
class OsLogoProgramForm extends HoneyPotForm {

    function __construct($controller, $name) {

        Requirements::css(THEMES_DIR . "/openstack/css/OsLogoProgramForm.css");

        Requirements::customScript("
            jQuery(document).ready(function() {

                if($('#OsLogoProgramForm_Form_CurrentSponsor').prop('checked') != true){
                    $('#openstack-companies').hide();
                    $('#non-sponsor-company').show();                    
                } else {
                    $('#openstack-companies').show();
                    $('#non-sponsor-company').hide();                                        
                }

                $('#OsLogoProgramForm_Form_CurrentSponsor').click(function () {                
                    $('#openstack-companies').toggle();
                    $('#non-sponsor-company').toggle();
                });      

            });
        ");


        $companies = Company::get()->filter('MemberLevel:not','Mention')->where('MemberLevel IS NOT NULL')->sort('Name','ASC');
        if($companies){
            $companiesField = new DropdownField('CompanyID','Company',$companies->map('ID','Name','--Select Company--'));
        }        

        $projects = Project::get();
        if($projects){
            $projectsField = new CheckboxSetField('Projects','Select the OpenStack projects your product uses:',$projects->map('Name','Name'));
        }

        $fields = new FieldList (
            new TextField('FirstName','First Name'),
            new TextField('Surname','Last Name'),
            new EmailField('Email','Email Address'),
            new TextField('Phone','Phone Number'),
            new CheckboxSetField(
                'Program',
                'Which logo program best fits your product offering?',
                OsLogoProgramResponse::$avialable_programs
            ),
            new LiteralField('HR','<hr/>'),
            new CheckboxField('CurrentSponsor','My company is a Corporate Sponsor or Gold/Platinum Member of the OpenStack Foundation.'),
            new LiteralField('DIV','<div id="openstack-companies">'),
            $companiesField,
            new TextField('OtherCompany','Other Company (if not listed above)'),
            new LiteralField('DIV','</div>'),
            new LiteralField('DIV','<div id="non-sponsor-company">'),
            new TextField('NonSponsorCompany','Company Name'),            
            new LiteralField('DIV','</div>'),
            new TextField('Product','Product or Service Name'),
            new LiteralField('ProductNote','If your proposed product name includes the OpenStack word mark, it will need to be approved as part of the licensing process.<br><br>'),
            new LiteralField('HR','<hr/>'),            
            new TextAreaField('CompanyDetails','Product or Service Description'),
            new CheckboxSetField(
                'Category',
                'Which of the following categories does your product fit into?  This will help us recommend the approprite licensing and associated marketing programs and assets:', 
                OsLogoProgramResponse::$avialable_categories
            ),
            new CheckboxSetField(
                'Regions',
                'In which regions does your company operate?', 
                OsLogoProgramResponse::$avialable_regions
            ),
            $projectsField,
            new CheckboxField('APIExposed','My product exposes the OpenStack API')
        );

        $actionButton = new FormAction('save', 'Request Information');
         
        $actions = new FieldList(
           $actionButton
        );


        $validator = new RequiredFields(
             'FirstName',
             'Surname',
             'Email',
             'Phone'
        );


        parent::__construct($controller, $name, $fields, $actions, $validator);
    }

    function forTemplate() {
        return $this->renderWith(array(
            $this->class,
            'Form'
        ));
    }

    function save($data, $form) {

        $response = new OSLogoProgramResponse();
        $form->saveInto($response);


        // Combine these two fields so we just store a manually typed name in
        // $data[OtherCompany]

        if($data['NonSponsorCompany']) {
            $response->OtherCompany = $data['NonSponsorCompany'];
        }

        $response->write();

        // Now set the official company name for the email
        $data['CompanyName'] = 'Not Provided';

        if($response->OtherCompany) {
            $response->CompanyName = $response->OtherCompany;
        } elseif ($response->CompanyID != 0) {
            $company = Company::get()->byID($response->CompanyID);
            if($company) {
                $response->CompanyName = $company->Name;
            }
        }

        // Email the logo email list
        $Subject = "Contact Form for Commercial Logo Inquiries";
        $email   = EmailFactory::getInstance()->buildEmail($data['Email'], OS_LOGO_PROGRAM_FORM_TO_EMAIL, $Subject);
        $email->setTemplate('OSLogoProgramResponseEmail');
        $email->populateTemplate($response);
        $email->send();

        return Controller::curr()->redirect(Controller::curr()->Link()."thanks");
    }

}
