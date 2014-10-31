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
 * Class EventLocation
 */
final class EventLocation {
	/**
	 * @var string
	 */
	private $city;
	/**
	 * @var string
	 */
	private $state;
	/**
	 * @var string
	 */
	private $country;

	private $lat;

	private $lng;

	/**
	 * @param string $city
	 * @param string $state
	 * @param string $country
	 */
	public function __construct($city,$state,$country){
		$this->city    = $city;
		$this->state   = $state;
		$this->country = $country;
	}

	/**
	 * @return string
	 */
	public function getCity(){
		return $this->city;
	}

	/**
	 * @return string
	 */
	public function getState(){
		return $this->state;
	}

	/**
	 * @return string
	 */
	public function getCountry(){
		return $this->country;
	}

	/**
	 * @param float $lat
	 * @param float $lng
	 */
	public function setCoordinates($lat, $lng){
		$this->lat = $lat;
		$this->lng = $lng;
	}

	/**
	 * @return array
	 */
	public function getCoordinates(){
		return array($this->lat,$this->lng);
	}
} 