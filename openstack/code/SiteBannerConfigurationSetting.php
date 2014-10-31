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

class SiteBannerConfigurationSetting extends DataObject {

    public static $db = array(
        'SiteBannerMessage' => 'HTMLText',
        'SiteBannerButtonText' => 'Text',
        'SiteBannerButtonLink' => 'Text',
        'SiteBannerRank' => 'Int',
        'Language'=> "Enum('English, Spanish, Italian, German, Portuguese,Chinese,Japanese,French', 'English')",
    );

    public static $has_one=Array('SiteConfig'=>'SiteConfig');

    function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('SiteConfigID');
        return $fields;
    }
}