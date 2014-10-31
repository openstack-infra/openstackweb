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
 * Class TrainingActivity
 */
class TrainingActivity extends DataObject{

    public static $singular_name = "Activity Feed";

    static $db = array(
        'Title'       => 'Text',
        'Link'        => 'Text',
        'Description' => 'HTMLText',
        'StartDate'   => 'Date',
        'EndDate'     => 'Date',
    );

    public function getStartMonth(){
        return DateTimeUtils::getMonthShortName($this->StartDate);
    }

    public function getStartDay(){
        return DateTimeUtils::getDay($this->StartDate);
    }

    public function getCMSFields() {
        $fields = new FieldList();

        $fields->push(new LiteralField("Title","<h2>Training Activity </h2>"));
        $fields->push(new TextField("Title","Title"));
        $fields->push($this->ID>0?new HtmlEditorField("Description","Description"):new HtmlEditorField("Description","Description",15));
        $fields->push(new TextField("Link","Link"));
        $fields->push($start_date = new DateField("StartDate","Start Date"));
        $fields->push($end_date = new DateField("EndDate","End Date"));
        if($this->ID==0){
            $start_date->setConfig('showcalendar', true);
            $end_date->setConfig('showcalendar', true);
        }
        return $fields;
    }


    function getCMSValidator()
    {
        return $this->getValidator();
    }

    function getValidator()
    {
        $validator= new RequiredFields(array('Title','Description','Link','StartDate','EndDate'));
        return $validator;
    }

    function canCreate($member=null) {
        if (!Permission::check(ITraining::MarketPlacePermissionSlug)) {
            return false;
        }
        return true;
    }
    function canEdit($member=null) {
        if (!Permission::check(ITraining::MarketPlacePermissionSlug)) {
            return false;
        }
        return true;
    }

    function canDelete($member=null) {
        if (!Permission::check(ITraining::MarketPlacePermissionSlug)) {
            return false;
        }
        return true;
    }
}