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
 * Class Office
 */
final class Office
	extends DataObject
	implements IOffice
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Address'  => 'Varchar',
		'Address2' => 'Varchar',
		'State'    => 'Varchar',
		'ZipCode'  => 'Varchar',
		'City'     => 'Varchar',
		'Country'  => 'Varchar',
		'Lat'      => 'Decimal',
		'Lng'      => 'Decimal',
		'Order'    => 'Int',
	);

	static $has_one = array(
		'Consultant'  => 'Consultant'
	);

	/**
	 * @return string
	 */
	public function getAddress()
	{
		return $this->getField('Address');
	}

	/**
	 * @param string $address
	 * @return void
	 */
	public function setAddress($address)
	{
		$this->setField('Address',$address);
	}

	/**
	 * @return string
	 */
	public function getAddress1()
	{
		return $this->getField('Address2');
	}

	/**
	 * @param string $address1
	 * @return void
	 */
	public function setAddress1($address1)
	{
		$this->setField('Address2',$address1);
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
	 * @return string
	 */
	public function getZipCode()
	{
		return $this->getField('ZipCode');
	}

	/**
	 * @param string $zip_code
	 * @return void
	 */
	public function setZipCode($zip_code)
	{
		$this->setField('ZipCode',$zip_code);
	}

	/**
	 * @param string $country
	 * @return void
	 */
	public function setCountry($country)
	{
		$this->setField('Country',$country);
	}

	/**
	 * @return string
	 */
	public function getCountry()
	{
		return $this->getField('Country');
	}


	/**
	 * @param string $city
	 * @return void
	 */
	public function setCity($city)
	{
		$this->setField('City',$city);
	}

	/**
	 * @return string
	 */
	public function getCity()
	{
		return $this->getField('City');
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
	 * @return IConsultant
	 */
	public function getConsultant()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Consultant','Offices')->getTarget();
	}

	/**
	 * @param IConsultant $consultant
	 * @return void
	 */
	public function setConsultant(IConsultant $consultant)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Consultant','Offices')->setTarget($consultant);
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @return void
	 */
	public function getOrder()
	{
		return (int)$this->getField('Order');
	}

	/**
	 * @param int $order
	 * @return void
	 */
	public function setOrder($order)
	{
		$this->setField('Order',$order);
	}

	public function getCountryFriendlyName(){
		return Geoip::countryCode2name($this->getCountry());
	}
}