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
require_once 'Zend/Date.php';

class CompanyListPage extends Page
{
    static $db = array();
    static $has_one = array();
    static $has_many = array(
        'Company' => 'Company'
    );

    function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $companiesTable = new GridField('Company', 'Company',$this->Company());
        $fields->addFieldToTab('Root.Companies', $companiesTable);
        return $fields;
    }
}

class CompanyListPage_Controller extends Page_Controller
{

    static $allowed_actions = array(
        'profile',
        'edit',
        'save',
        'CompanyEditForm',
    );

    function init()
    {
        parent::init();

        // require custom CSS

        Requirements::css("themes/openstack/css/chosen.css", "screen,projection");
        Requirements::css("themes/openstack/css/jquery.autocomplete.css");
        Requirements::css(THEMES_DIR, "/openstack/javascript/jquery-ui-1.10.3.custom/css/smoothness/jquery-ui-1.10.3.custom.min.css");

        Requirements::javascript("themes/openstack/javascript/chosen.jquery.min.js");
        Requirements::javascript("themes/openstack/javascript/jquery.autocomplete.min.js");

	    Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
	    Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
        Requirements::javascript(THEMES_DIR, "/openstack/javascript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js");
    }

    function DisplayedCompanies($type)
    {
        if ($type == 'Combined') {

	        $DisplayedCompanies = Company::get()->filter(array( 'DisplayOnSite' => 1 ))->filterAny( array( 'MemberLevel' => 'Startup', 'MemberLevel' => 'Corporate' ))->sort('Name');

        } else {

            $DisplayedCompanies =  Company::get()->filter(array('DisplayOnSite' => 1, 'MemberLevel' => $type ))->sort('Name');
        }
        if ($DisplayedCompanies) {
            return $DisplayedCompanies;
        } else {
            return NULL;
        }
    }

    function MostRecent()
    {

        $DisplayedCompanies =  Company::get()->filter(array('DisplayOnSite' => 1))->sort('Name');
        $DisplayedCompanies->sort('Created');
        $MostRecent = $DisplayedCompanies->Last();
        return $MostRecent;
    }

    function Featured()
    {
        $FeaturedCompanies = Company::get()->filter('Featured' , 1)->sort('Name');
        return $FeaturedCompanies;
    }

    //Show the Company detail page using the CompanyListPage_show.ss template
    function profile()
    {
        if ($Company = $this->getCompanyByURLSegment()) {
            $Data = array(
                'Company' => $Company
            );

            //return our $Data to use on the page
            return $this->Customise($Data);
        } else {
            //Company member not found
            return $this->httpError(404, 'Sorry that comapny could not be found');
        }
    }

    // EditCompanyForm
    function CompanyEditForm()
    {
        $current_company= $this->getCompany();
        if(!$current_company){
            $current_company = $this->CurrentCompany();
        }
        $CompanyEditForm = new CompanyEditForm($this, 'CompanyEditForm',$current_company);
        $CompanyEditForm->disableSecurityToken();
        // Fill in the form
        if ($current_company) {
            Session::set('CompanyID', $current_company->ID);
            $CompanyEditForm->loadDataFrom($current_company, False);
            return $CompanyEditForm;
        } elseif ($this->request->isPost()) {
            // SS is returning to the form controller to post data
            return $CompanyEditForm;
        } else {
            // Attempted to load the edit form, but the id was missing or didn't match an id in the database
            return $this->httpError(404, 'Sorry that company could not be found');
        }


    }

    // Save an edited company
    function save($data, $form)
    {
        $CompanyID = Session::get('CompanyID');
        // Check to see if it is set and numeric
        if ($CompanyID && is_numeric($CompanyID)) {
            // Try to pull the company data record by ID

            $Company =  Company::get()->byID($CompanyID);
            $MemberID = Member::currentUserID();
            $allow = $Company->CompanyAdminID == $MemberID;
            if (!$allow) {
                //check groups
                $allow = $Company->canEditProfile() || $Company->canEditLogo();
            }
            // Check to see if the currently logged in member is an admin for this company
            if ($allow) {
                // Load the data from the form and save the edits to the company
                $form->saveInto($Company);
                $Company->write();

                $this->setMessage('Success', 'Your edits have been saved.');

                Session::clear('CompanyID');

                $this->redirectBack();
            } else {
                $this->setMessage('Error', 'You do not seem to have permission to edit this company.');
                $this->redirectBack();
            }

        } else {
            $this->setMessage('Error', 'There was an error saving your edits.');
            $this->redirectBack();
        }

    }

    public function isCompanyAdmin()
    {
        if (($company = $this->getCompany()) && ($MemberID = Member::currentUserID())) {
           return $company->canEditProfile() || $company->canEditLogo();
        } else {
            return false;
        }
    }

    // Check to see if a member is logged in and allowed to edit this company
    public function canEditCompanyProfile(){
        if (($Company = $this->getCompany()) && ($MemberID = Member::currentUserID())) {
            return $Company->canEditProfile() || $Company->canEditLogo();
        } else {
            return false;
        }
    }

    //Get the current Company from the URL, if any
    public function getCompany()
    {
        $params = $this->getURLParams();
        if (is_numeric($params['ID']) && $Company = Company::get()->byID((int)$params['ID'])) {
            Session::set('CompanyID', $Company->ID);
            return $Company;
        }
        return null;
    }

    //Get the current Company from the URL, if any
    public function getCompanyByURLSegment()
    {
        $Params = $this->getURLParams();
        $Segment = convert::raw2sql($Params['ID']);

        if ($Params['ID'] && $Company =  Company::get()->filter('URLSegment',$Segment)->first()) {
            return $Company;
        }
    }

    //Return our custom breadcrumbs
    public function Breadcrumbs()
    {

        //Get the default breadcrumbs
        $Breadcrumbs = parent::Breadcrumbs();

        if ($Company = $this->getCompany()) {
            //Explode them into their individual parts
            $Parts = explode(SiteTree::$breadcrumbs_delimiter, $Breadcrumbs);

            //Count the parts
            $NumOfParts = count($Parts);

            //Change the last item to a link instead of just text
            $Parts[$NumOfParts - 1] = ("<a href=\"" . $this->Link() . "\">" . $this->Title . "</a>");

            //Add our extra piece on the end
            $Parts[$NumOfParts] = $Company->Name;

            //Return the imploded array
            $Breadcrumbs = implode(SiteTree::$breadcrumbs_delimiter, $Parts);
        }

        return $Breadcrumbs;
    }

    function CurrentCompany()
    {
        $CompanyID = Session::get('CompanyID');
        if ($CompanyID && is_numeric($CompanyID)) {
            $Company = Company::get()->byID((int)$CompanyID);
            return $Company;
        }
        return false;
    }
}