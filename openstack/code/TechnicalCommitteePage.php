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

class TechnicalCommitteePage extends Page{

    static $can_be_root  = false;
    static $default_parent='OneColumn';
    static $allowed_children = array();
    static $db = array();

    function getCMSFields() {
        $fields = parent::getCMSFields();
        // remove unneeded fields
        return $fields;
    }
}

class TechnicalCommitteePage_Controller extends Page_Controller{

    public function TechnicalCommitteeMembers(){
        //Group_Member
        $group = Group::get()->filter('Code', 'technical-committee' )->first();
        $res= $group->getManyManyComponents("Members",'','Member.FirstName, Member.SurName');
        return $res;
    }

    public function init() {
        parent::init();
        Requirements::css(THEMES_DIR ."/openstack/css/bio.css");
    }
}