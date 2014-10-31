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
 * Class CustomMember_ForgotPasswordEmail
 */
final class CustomMember_ForgotPasswordEmail extends Member_ForgotPasswordEmail {
	protected $from = '';  // setting a blank from address uses the site's default administrator email
	protected $subject = '';
	protected $ss_template = 'CustomForgotPasswordEmail';

	function __construct() {
		parent::__construct();
		$this->subject = _t('Member.SUBJECTPASSWORDRESET', "Your password reset link", PR_MEDIUM, 'Email subject');
	}
} 