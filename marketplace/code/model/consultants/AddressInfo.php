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
 * Class AddressInfo
 * @value_object
 */
final class AddressInfo {

	private $address;
	private $address1;
	private $zip_code;
	private $state;
	private $city;
	private $country;

	/**
	 * @param string $address
	 * @param string $address1
	 * @param string $zip_code
	 * @param string $state
	 * @param string $city
	 * @param string $country
	 */
	public function __construct($address,$address1,$zip_code,$state,$city,$country){
		$this->address  = $address;
		$this->address1 = $address1;
		$this->zip_code = $zip_code;
		$this->state    = $state;
		$this->city     = $city;
		$this->country  = $country;
	}

	public function getAddress(){
		return array($this->address,$this->address1);
	}

	public function getZipCode(){
		return $this->zip_code;
	}

	public function getState(){
		return $this->state;
	}

	public function getCity(){
		return $this->city;
	}

	public function getCountry(){
		return $this->country;
	}
} 