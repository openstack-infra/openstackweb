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
 * Class EmailFactory
 * Utility class
 */
final class EmailFactory {

	/**
	 * @var EmailFactory
	 */
	private static $instance;

	private function __construct(){}

	private function __clone(){}

	/**
	 * @return EmailFactory
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance = new EmailFactory();
		}
		return self::$instance;
	}

	/**
	 * @param null $from
	 * @param null $to
	 * @param null $subject
	 * @param null $body
	 * @param null $bounceHandlerURL
	 * @param null $cc
	 * @param null $bcc
	 * @return DevelopmentEmail|Email
	 */
	public function buildEmail($from = null, $to = null, $subject = null, $body = null, $bounceHandlerURL = null, $cc = null, $bcc = null){
		$env = 'dev';
		if(defined('SS_ENVIRONMENT_TYPE'))
			$env = SS_ENVIRONMENT_TYPE;
		return $env == 'dev'? new DevelopmentEmail($from, $to, $subject, $body, $bounceHandlerURL, $cc, $bcc) : new Email($from, $to, $subject, $body, $bounceHandlerURL, $cc, $bcc);
	}
} 