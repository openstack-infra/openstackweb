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
class SponsorsPage extends Page {

    public static $many_many = array(
        'Companies' => 'Company'
    );

    //sponsor type
    static $many_many_extraFields = array(
        'Companies' => array(
            'SponsorshipType' => "Enum('Headline, Premier, Event, Startup, InKind, Spotlight', 'Startup')",
            'SubmitPageUrl'=>'Text',
            'LogoSize' => "Enum('None, Small, Medium, Large, Big', 'None')",
        ),
    );

    static $defaults = array(
        "Title" => "sponsors",
        "MenuTitle"=>"Sponsors"
    );

    static $allowed_children = "none";
    static $can_be_root = false;
    static $default_parent = "ConferencePage";

    function getCMSFields() {

        $fields = parent::getCMSFields();
        //set current page id
        $_REQUEST["PageId"] = $this->ID;

	    $config = GridFieldConfig_RelationEditor::create();

	    $config->getComponentByType('GridFieldDataColumns')->setDisplayFields(array(
		    array( 'Name'            => 'Name',
			    "DDLSponsorshipType" => "Sponsorship Type",
			    "DDLLogoSize"        => "Logo Size",
			    "InputSubmitPageUrl" => "Sponsor Link"),
	    ));

	    $companies = new GridField('Companies','Companies',$this->Companies(),$config);

	    $fields->addFieldToTab('Root.SponsorCompanies',$companies);

        //remove main content
        $fields->removeFieldFromTab("Root.Main","Content");
        return $fields;
    }

    function onAfterWrite() {
        parent::onAfterWrite();
        //update all relationships with sponsors
        foreach($this->Companies() as $company){
            if(isset($_REQUEST["SponsorshipType_{$company->ID}"])){
                $type = $_REQUEST["SponsorshipType_{$company->ID}"];
                $sql = "UPDATE SponsorsPage_Companies SET SponsorshipType ='{$type}' WHERE CompanyID={$company->ID} AND SponsorsPageID={$this->ID};";
                DB::query($sql);
            }
            if(isset($_REQUEST["SubmitPageUrl_{$company->ID}"])){
                $page_url = $_REQUEST["SubmitPageUrl_{$company->ID}"];
                $sql = "UPDATE SponsorsPage_Companies SET SubmitPageUrl ='{$page_url}' WHERE CompanyID={$company->ID} AND SponsorsPageID={$this->ID};";
                DB::query($sql);
            }

            if(isset($_REQUEST["LogoSize_{$company->ID}"])){
                $logo_size = $_REQUEST["LogoSize_{$company->ID}"];
                $sql = "UPDATE SponsorsPage_Companies SET LogoSize ='{$logo_size}' WHERE CompanyID={$company->ID} AND SponsorsPageID={$this->ID};";
                DB::query($sql);
            }
        }
    }

}

class SponsorsPage_Controller extends Page_Controller {

    public function init() {
        parent::init();
        Requirements::css(THEMES_DIR ."/openstack/css/sponsorspage.css");
    }

    private function Sponsors($type){
        $page_id = $this->ID;
        $page    = SponsorsPage::get()->byID($page_id);
        $res     = $page->getManyManyComponents("Companies","SponsorshipType='{$type}'","ID");
        return $res;
    }

    public function StartupSponsors(){
        return $this->Sponsors("Startup");
    }

    public function HeadlineSponsors(){
       return $this->Sponsors("Headline");
    }

    public function PremierSponsors(){
        return $this->Sponsors("Premier");
    }

    public function EventSponsors(){
        return $this->Sponsors("Event");
    }

    public function InKindSponsors(){
        return $this->Sponsors("InKind");
    }

    public function SpotlightSponsors(){
        return $this->Sponsors("Spotlight");
    }

}