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
 * Class EventAlertEmail
 */
final class EventAlertEmail
	extends DataObject
	implements IEventAlertEmail
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
	);

	static $has_one = array(
		'LastEventRegistrationRequest' => 'EventRegistrationRequest',
	);

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @return IEventRegistrationRequest
	 */
	public function getLastEventRegistrationRequest()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'LastEventRegistrationRequest')->getTarget();
	}

	/**
	 * @param IEventRegistrationRequest $request
	 * @return void
	 */
	public function setLastEventRegistrationRequest(IEventRegistrationRequest $request)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'LastEventRegistrationRequest')->setTarget($request);
	}
}