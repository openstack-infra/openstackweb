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
 * Class PasswordManager
 */
final class PasswordManager {
	/**
	 * @param int $member_id
	 * @param string $token
	 * @throws InvalidPasswordResetLinkException
	 * @return bool
	 */
	public function verifyToken($member_id, $token){
		if (is_null($member_id) || $member_id == 0 || empty($token)){
			throw new InvalidPasswordResetLinkException;
		}
		//get member
		$member = Member::get()->byId($member_id);
		//check if token already was used...
		if(!$member || !$member->validateAutoLoginToken($token)){
			throw new InvalidPasswordResetLinkException;
		}
		$current_member = Member::currentUser();
		if($current_member && $member->ID !== $current_member->ID){
			throw new InvalidPasswordResetLinkException;
		}
		return $member->encryptWithUserSettings($token);
	}

	/**
	 * @param string $token
	 * @param string $password
	 * @param string $password_confirmation
	 * @throws InvalidResetPasswordTokenException
	 * @throws EmptyPasswordException
	 * @throws InvalidPasswordException
	 * @throws PasswordMismatchException
	 */
	public function changePassword($token, $password, $password_confirmation){
		if(empty($token)) throw new InvalidResetPasswordTokenException;
		$member = Member::member_from_autologinhash($token);
		if(!$member) throw new InvalidResetPasswordTokenException;
		if(empty($password)) throw new EmptyPasswordException;
		if($password !== $password_confirmation) throw new PasswordMismatchException;
		$isValid = $member->changePassword($password);
		if(!$isValid->valid()) throw new InvalidPasswordException($isValid->starredList());

		$member->logIn();
		//invalidate former auto login token
		$member->generateAutologinTokenAndStoreHash();

		//send confirmation email
		$email = EmailFactory::getInstance()->buildEmail(CHANGE_PASSWORD_EMAIL_FROM, $member->Email, CHANGE_PASSWORD_EMAIL_SUBJECT);
		$email->setTemplate('ChangedPasswordEmail');
		$email->populateTemplate(array('MemberName' => $member->getFullName()));
		$email->send();
	}
} 