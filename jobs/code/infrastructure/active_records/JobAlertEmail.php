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
 * Class JobAlertEmail
 */
final class JobAlertEmail extends DataObject
	implements IJobAlertEmail {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	private static $db = array();

	static $has_one = array(
		'LastJobRegistrationRequest' => 'JobRegistrationRequest',
	);
	/**
	 * @return int
	 */
	public function getIdentifier(){
		return (int)$this->getField('ID');
	}
	/**
	 * @return IJobRegistrationRequest
	 */
	public function getLastJobRegistrationRequest()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'LastJobRegistrationRequest')->getTarget();
	}

	/**
	 * @param IJobRegistrationRequest $request
	 * @return void
	 */
	public function setLastJobRegistrationRequest(IJobRegistrationRequest $request)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'LastJobRegistrationRequest')->setTarget($request);
	}
}