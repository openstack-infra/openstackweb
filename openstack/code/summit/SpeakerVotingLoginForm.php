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
class SpeakerVotingLoginForm extends MemberLoginForm {
    public function dologin($data) {
        if($this->performLogin($data)) {
	        Controller::curr()->redirectBack();
        } else {
            if($badLoginURL = Session::get("BadLoginURL")) {
	            Controller::curr()->redirect($badLoginURL);
            } else {
	            Controller::curr()->redirectBack();
            }
        }      
    }

    function __construct($controller, $name, $fields = null, $actions = null,
                                             $checkCurrentUser = true) {

            if(!$actions) {
                $actions = new FieldList(
                    new FormAction('dologin', _t('Member.BUTTONLOGIN', "Log in")),
                    new LiteralField(
                        'forgotPassword',
                        '<p id="ForgotPassword"><a href="/Security/lostpassword">' . _t('Member.BUTTONLOSTPASSWORD', "I've lost my password") . '</a></p>'
                    )
                );
            }

            parent::__construct($controller, $name, $fields, $actions);

    }    
}