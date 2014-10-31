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

class CustomTimeField extends TimeField{
    function __construct($name, $title = null, $value = ""){
        parent::__construct($name, $title, $value);
    }

    protected function FieldDriver($html) {
        if($this->getConfig('showdropdown')) {
            Requirements::javascript(SAPPHIRE_DIR . '/thirdparty/prototype/prototype.js');
            Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
            Requirements::javascript(SAPPHIRE_DIR . '/javascript/jquery_improvements.js');
            Requirements::javascript(THIRDPARTY_DIR . '/behaviour/behaviour.js');
            Requirements::javascript(SAPPHIRE_DIR . '/javascript/TimeField_dropdown.js');
            Requirements::css(SAPPHIRE_DIR . '/css/TimeField_dropdown.css');
            Requirements::css(THEMES_DIR . '/openstack/css/custom.timefield.css');

            $html .= sprintf('<img class="timeIcon" src="sapphire/images/clock-icon.gif" id="%s-icon"/>', $this->id());
            $html .= sprintf('<div class="dropdownpopup" id="%s-dropdowntime"></div>', $this->id());
            $html = '<div class="dropdowntime">' . $html . '</div>';
        }

        return $html;
    }
}