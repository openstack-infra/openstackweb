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
 * Class DataCenterRegion
 */
final class DataCenterRegion
	extends DataObject implements IDataCenterRegion {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Name'     => 'Varchar(100)',
		'Endpoint' => 'Varchar(512)',
		'Color'    => 'Varchar(6)',
	);

	static $has_one = array(
		'CloudService' => 'CloudService',
	);

	static $has_many = array(
		'Locations' => 'DataCenterLocation',
	);

	/*static $indexes = array(
		'Name_CloudService' => array('type'=>'unique', 'value'=>'Name,CloudServiceID'),
	);*/

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getField('Name');
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->setField('Name',$name);
	}

	/**
	 * @return string
	 */
	public function getEndpoint()
	{
		return $this->getField('Endpoint');
	}

	/**
	 * @param string $endpoint
	 * @return void
	 */
	public function setEndpoint($endpoint)
	{
		$this->setField('Endpoint',$endpoint);
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @return ICloudService
	 */
	public function getCloud()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'CloudService','DataCenterRegions')->getTarget();
	}

	/**
	 * @param ICloudService $cloud
	 * @return void
	 */
	public function setCloud(ICloudService $cloud)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'CloudService','DataCenterRegions')->setTarget($cloud);
	}

	/**
	 * @return IDataCenterLocation[]
	 */
	public function getLocations()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Locations')->toArray();
	}

	/**
	 * @param IDataCenterLocation $location
	 * @return void
	 */
	public function addLocation(IDataCenterLocation $location)
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Locations')->add($location);
	}

	/**
	 * @return void
	 */
	public function clearLocations()
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Locations')->removeAll();
	}

	/**
	 * @return string
	 */
	public function getColor()
	{
		return $this->getField('Color');
	}

	/**
	 * @param string $color
	 * @return void
	 */
	public function setColor($color)
	{
		$this->setField('Color',$color);
	}
}