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
 * Class ICLACompanyDecorator
 */
class ICLACompanyDecorator
	extends DataExtension
{
	//Add extra database fields

	private static $db = array(
		'CCLASigned' => 'Boolean',
		'CCLADate'   => 'SS_Datetime',
	);

	private static $defaults = array(
		'CCLASigned' => FALSE,
	);


	private static $has_many = array(
		'Teams' => 'Team'
	);


	/**
	 * @return void
	 */
	public function signICLA()
	{
		$this->owner->setField('CCLASigned',true);
		$this->owner->setField('CCLADate',  SS_Datetime::now()->Rfc2822());
	}

	/**
	 * @return void
	 */
	public function unsignICLA()
	{
		$this->owner->setField('CCLASigned',false);
		$this->owner->setField('CCLADate', null);
	}

	/**
	 * @return bool
	 */
	public function isICLASigned()
	{
		return (bool)$this->owner->getField('CCLASigned');
	}

	/**
	 * @return Datetime
	 */
	public function ICLASignedDate()
	{
		$ss_datetime = $this->owner->getField('CCLADate');
		return new DateTime($ss_datetime);
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->owner->getField('ID');
	}


	/**
	 * @return ITeam[]
	 */
	public function Teams()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this->owner , 'Teams', new QueryObject)->toArray();
	}

	public function addTeam(ITeam $team)
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this->owner , 'Teams', new QueryObject)->add($team);
	}

}