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
 * Class DataCenterLocation
 */
class DataCenterLocation
	extends DataObject
	implements IDataCenterLocation
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'City'    => 'Varchar(125)',
		'State'   => 'Varchar(50)',
		'Country' => 'Varchar(5)',
		'Lat'     => 'Decimal',
		'Lng'     => 'Decimal',
	);

	static $has_one = array(
		'CloudService'     => 'CloudService',
		'DataCenterRegion' => 'DataCenterRegion',
	);

	static $has_many = array(
		'AvailabilityZones' => 'AvailabilityZone',
	);

	static $indexes = array(
		'City_State_Country_Service_Region' => array('type'=>'unique', 'value'=>'CloudServiceID,DataCenterRegionID,City,Country,State'),
	);

	public function setCountry($country)
	{
		$this->setField('Country',$country);
	}

	public function getCountry()
	{
		return $this->getField('Country');
	}

	public function setCity($city)
	{
		$this->setField('City',$city);
	}

	public function getCity()
	{
		return $this->getField('City');
	}

	public function setCloudService(ICloudService $cloud_service)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'CloudService','DataCenters')->setTarget($cloud_service);
	}

	public function getCloudService()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'CloudService','DataCenters')->getTarget();
	}

	public function getAvailabilityZones()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'AvailabilityZones')->toArray();
	}

	public function clearAvailabilityZones()
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'AvailabilityZones')->removeAll();
	}

	public function addAvailabilityZone(IAvailabilityZone $az)
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'AvailabilityZones')->add($az);
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @return string
	 */
	public function getState()
	{
		return $this->getField('State');
	}

	/**
	 * @param string $state
	 * @return void
	 */
	public function setState($state)
	{
		$this->setField('State',$state);
	}

	/**
	 * @param float $lng
	 * @return void
	 */
	public function setLng($lng)
	{
		$this->setField('Lng',$lng);
	}

	/**
	 * @return float
	 */
	public function getLng()
	{
		return $this->getField('Lng');
	}

	/**
	 * @param float $lat
	 * @return void
	 */
	public function setLat($lat)
	{
		$this->setField('Lat',$lat);
	}

	/**
	 * @return float
	 */
	public function getLat()
	{
		return $this->getField('Lat');
	}

	/**
	 * @return IDataCenterRegion
	 */
	public function getDataCenterRegion()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'DataCenterRegion','Locations')->getTarget();
	}

	/**
	 * @param IDataCenterRegion $region
	 * @return void
	 */
	public function setDataCenterRegion(IDataCenterRegion $region)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'DataCenterRegion','Locations')->setTarget($region);
	}
}