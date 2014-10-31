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
class ConditionalAndValidationRule extends Validator {

    private $validators = array();

    public function __construct($validators=null){
        $this->validators = $validators;
    }

    function php($data) {
        $res = true;
        foreach($this->validators as $validator){
            $res &= $validator->php($data);
            $this->errors = array();
            if(!$res){
                $this->errors = array_merge($this->errors,$validator->getErrors());
                break;
            }
        }
        return $res;
    }



    function setForm($form) {
        $this->form = $form;
        foreach($this->validators as $validator){
            $validator->setForm($form);
        }
    }


    function javascript() {
        $js = "";
        foreach($this->validators as $validator){
            $js &= $validator->javascript();
        }
        return $js;
    }
}