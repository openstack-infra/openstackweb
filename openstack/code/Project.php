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
/**
 * Class Project
 */
class Project extends DataObject {
 
    private static $db = array(
        'Name'        => 'Varchar(255)',
        'Description' => 'HTMLText',
        'Codename'    => 'Text'
    );

    private static $belongs_many_many = array (
    	'OpenstackUser' => 'OpenstackUser',
    );
 
    public function getCMSFields() {
 
        $fields = new FieldList();
 
        $fields->push(new TextField('Name', 'Name of the project'));
        $fields->push(new TextField('Codename', 'CodeName'));
        $fields->push(new TextareaField('Description', 'Short description'));
 
        return $fields;
    }
}