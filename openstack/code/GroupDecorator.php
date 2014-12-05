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
class SecurityAdminExtension extends Extension{
    public function updateEditForm(Form &$form){
        $actions = $form->Actions();
        $actions->removeByName("action_addmember");
    }
}

class GroupDecorator extends DataExtension {

    function updateCMSFields(FieldList $fields) {

        $fields->removeFieldFromTab('Root.Members','Members');

        $fieldList= array(
            'FirstName' => 'Name',
            'Surname' => 'Last Name'
        );

        $detailFormFields = new FieldList(
            new TabSet("Root",
                new Tab('Main', 'Main',
                    new HeaderField('MemberDetailsHeader', "Personal Details"),
                    new TextField("FirstName","First Name"),
                    new TextField("Surname","Last Name"),
                    new HeaderField('MemberUserDetailsHeader',"User Details"),
                    new ConfirmedPasswordField(
                        'Password',
                        null,
                        null,
                        null,
                        false // showOnClick
                    ),
                    new TextField("Email","Email"),
                    new TextField("SecondEmail","Second Email"),
                    new TextField("Third Email","Third Email"),
                    new TextField("FoodPreference","Food Preference"),
                    new TextField("OtherFood","Other Food"),
                    new TextField("IRCHandle","IRC Handle"),
                    new TextField("TwitterName","Twitter Name"),
                    new TextField("LinkedInProfile","LinkedIn Profile"),
                    new TextField("JobTitle","Job Title"),
                    new TextField("Role","Role"),
                    new TextareaField("StatementOfInterest","Statement Of Interest"),
                    new HtmlEditorField("Bio","Bio")
                )
            )
        );

	    $config = GridFieldConfig_RelationEditor::create(10);
	    $config->getComponentByType('GridFieldDetailForm')->setFields($detailFormFields);
	    $manager = new GridField('Members','Members',$this->owner->Members(),$config);

        $fields->addFieldToTab('Root.Members',$manager);
        $fields->push(new HiddenField("GroupEdtion","GroupEdtion","1"));
    }


    /**
     * Override to avoid Dup groups titles and slugs
     */
    function onAfterWrite(){
        parent::onAfterWrite();
        $exits_group = false;
        $suffix = 1;
        //get original values
        $original_code  = $this->owner->Code;
        $original_title = $this->owner->Title;
        //iterate until we get an unique slug and title
        while(!$exits_group){
            $new_code    = $this->owner->Code;
            $new_title   = $this->owner->Title;
            $id          = $this->owner->ID;
            //check if group already exists...
            $count = DB::query(" SELECT COUNT(*) FROM \"Group\" WHERE Code ='${new_code}' AND ID <> ${id}")->value();
            if($count) {
                //if exists , rename it
                $this->owner->Code  = $original_code .'-' .$suffix;
                $this->owner->Title = $original_title.' '.$suffix;
            }
            else{
                DB::query("UPDATE \"Group\" SET Code= '${new_code}', Title = '${new_title}' WHERE ID = ${id} ");
                $exits_group = true;
            }
            ++$suffix;
        }
    }

}