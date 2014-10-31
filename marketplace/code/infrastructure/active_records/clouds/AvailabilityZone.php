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
 * Class AvailabilityZone
 */
class AvailabilityZone
	extends DataObject
	implements IAvailabilityZone
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Name' => 'Varchar',
	);

	static $has_one = array(
		'Location' => 'DataCenterLocation',
	);

	static $indexes = array(
		'Location_Name' => array('type'=>'unique', 'value'=>'LocationID,Name'),
	);

	/**
	 * @return mixed|string
	 */
	public function getName()
	{
		return $this->getField('Name');
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->setField('Name',$name);
	}

	/**
	 * @return DataObject|IDataCenterLocation
	 */
	public function getLocation()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Location','AvailabilityZones')->getTarget();
	}

	/**
	 * @param IDataCenterLocation $location
	 */
	public function setLocation(IDataCenterLocation $location)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Location','AvailabilityZones')->setTarget($location);
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}
}