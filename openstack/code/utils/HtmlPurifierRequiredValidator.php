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

class HtmlPurifierRequiredValidator  extends Validator {


    protected $required;

    /**
     * Pass each field to be validated as a seperate argument
     * to the constructor of this object. (an array of elements are ok)
     */
    function __construct() {
        $Required = func_get_args();
        if( isset($Required[0]) && is_array( $Required[0] ) )
            $Required = $Required[0];
        $this->required = $Required;

        parent::__construct();
    }

    function javascript()
    {
        return '';
    }

    function php($data)
    {
        $valid = true;


        $fields = $this->form->Fields();

        if($this->required) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('CSS.AllowedProperties', array());
            $purifier = new HTMLPurifier($config);

            foreach($this->required as $fieldName) {
                $formField = $fields->dataFieldByName($fieldName);

                // submitted data for file upload fields come back as an array
                $value = isset($data[$fieldName]) ? $data[$fieldName] : null;

                if(is_array($value)) {
                    if ($formField instanceof FileField && isset($value['error']) && $value['error']) {
                        $error = true;
                    }
                    else {
                        $error = (count($value)) ? false : true;
                    }
                } else {
                    // assume a string or integer
                    $error = (strlen($value)) ? false : true;
                }

                if($formField && $error) {
                    $errorMessage = sprintf( '%s is not valid'.'.', strip_tags('"' . ($formField->Title() ? $formField->Title() : $fieldName) . '"'));
                    if($msg = $formField->getCustomValidationMessage()) {
                        $errorMessage = $msg;
                    }
                    $this->validationError(
                        $fieldName,
                        $errorMessage,
                        "required"
                    );
                    $valid = false;
                }
                else{
                    $cleaned_value = $purifier->purify($value);
                    if(is_null($cleaned_value) || empty($cleaned_value))
                    {
                        $errorMessage = sprintf( '%s is invalid'.'.', strip_tags('"' . ($formField->Title() ? $formField->Title() : $fieldName) . '"'));
                        $this->validationError(
                            $fieldName,
                            $errorMessage,
                            "required"
                        );
                        $valid = false;
                    }
                }

            }
        }
        return $valid;
    }
}