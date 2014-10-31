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
class SigninPage extends Page
{

    public static $db = array();
    public static $has_one = array();
    public static $has_many = array(
        'EventSignIns' => 'EventSignIn'
    );

    function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $signInsTable = new GridField('EventSignIns', 'Events SignIn',$this->EventSignIns());
        $fields->addFieldToTab('Root.SignIns', $signInsTable);
        return $fields;
    }

}

class SigninPage_Controller extends Page_Controller
{

    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    public static $allowed_actions = array(
        'SigninForm',
        'doSigninForm'
    );

    public function init()
    {
        parent::init();

        Requirements::css('themes/openstack/css/signin.page.css');

        Requirements::javascript("themes/openstack/javascript/chosen.jquery.min.js");
        Requirements::javascript("themes/openstack/javascript/jquery.autocomplete.min.js");

        Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
        Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");

        Requirements::javascript('themes/openstack/javascript/signin.page.js');
    }

    function SigninForm()
    {
        return new SigninForm($this, 'SigninForm');
    }

    public function doSigninForm($data, $SigninForm)
    {
        $submission = new EventSignIn();
        $SigninForm->saveInto($submission);
        //Giving the submission a page ID establishes the relationship required for it to work in the DOM view in the CMS.
        if ($submission->EmailAddress != "") {
            $submission->SigninPageID = $this->ID;
            $submission->write();
            $this->setMessage('Success', 'Thanks for signing up and thanks for visiting our booth!');
        } else {
            $this->setMessage('Error', 'Oops... it looks like you might not have provided an email address.');
        }

        $this->redirectBack();
    }

}