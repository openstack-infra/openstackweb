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

final class CustomChangePasswordForm extends ChangePasswordForm {

	/**
	 * @var PasswordManager
	 */
	private $password_manager;

	function __construct($controller, $name, $fields = null, $actions = null){
		parent::__construct($controller, $name, $fields, $actions);
		$this->fields->removeByName('OldPassword');
		$this->password_manager = new PasswordManager;
	}
	/**
	 * Change the password
	 *
	 * @param array $data The user submitted data
	 */
	function doChangePassword(array $data) {

		try{
			$token = Session::get('AutoLoginHash');
			$this->password_manager->changePassword($token,@$data['NewPassword1'],@$data['NewPassword2']);
			Session::clear('AutoLoginHash');
			if (isset($_REQUEST['BackURL']) && $_REQUEST['BackURL'] // absolute redirection URLs may cause spoofing
				&& Director::is_site_url($_REQUEST['BackURL'])) {
				Controller::curr()->redirect($_REQUEST['BackURL']);
			}
			else {
				// Redirect to default location - the login form saying "You are logged in as..."
				// $redirectURL = HTTP::setGetVar('BackURL', Director::absoluteBaseURL(), Security::Link('login'));
				$redirectURL = '/direct-after-login/';
				Controller::curr()->redirect($redirectURL);
			}
		}
		catch(InvalidResetPasswordTokenException $ex1){
			Session::clear('AutoLoginHash');
			Controller::curr()->redirect('login');
		}
		catch(EmptyPasswordException $ex2){
			$this->clearMessage();
			$this->sessionMessage(_t('Member.EMPTYNEWPASSWORD', "The new password can't be empty, please try again"),"bad");
			Controller::curr()->redirectBack();
		}
		catch(PasswordMismatchException $ex3){
			$this->clearMessage();
			$this->sessionMessage(_t('Member.ERRORNEWPASSWORD', "You have entered your new password differently, try again"),"bad");
			Controller::curr()->redirectBack();
		}
		catch(InvalidPasswordException $ex4){
			$this->clearMessage();
			$this->sessionMessage(sprintf(_t('Member.INVALIDNEWPASSWORD', "We couldn't accept that password: %s"), nl2br("\n".$ex4->getMessage())),"bad");
			Controller::curr()->redirectBack();
		}
	}
} 