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
interface IOffice extends IEntity {
	/**
	 * @return string
	 */
	public function getAddress();

	/**
	 * @param string $address
	 * @return void
	 */
	public function setAddress($address);

	/**
	 * @return string
	 */
	public function getAddress1();

	/**
	 * @param string $address1
	 * @return void
	 */
	public function setAddress1($address1);

	/**
	 * @return string
	 */
	public function getState();

	/**
	 * @param string $state
	 * @return void
	 */
	public function setState($state);

	/**
	 * @return string
	 */
	public function getZipCode();

	/**
	 * @param string $country
	 * @return void
	 */
	public function setCountry($country);

	/**
	 * @param string $zip_code
	 * @return void
	 */
	public function setZipCode($zip_code);

	/**
	 * @return string
	 */
	public function getCountry();

	/**
	 * @param string $city
	 * @return void
	 */
	public function setCity($city);

	/**
	 * @return string
	 */
	public function getCity();

	/**
	 * @param float $lng
	 * @return void
	 */
	public function setLng($lng);

	/**
	 * @return float
	 */
	public function getLng();

	/**
	 * @param float $lat
	 * @return void
	 */
	public function setLat($lat);

	/**
	 * @return float
	 */
	public function getLat();

	/**
	 * @return IConsultant
	 */
	public function getConsultant();

	/**
	 * @return void
	 */
	public function getOrder();

	/**
	 * @param int $order
	 * @return void
	 */
	public function setOrder($order);

	/**
	 * @param IConsultant $consultant
	 * @return void
	 */
	public function setConsultant(IConsultant $consultant);

	/**
	 * @return string
	 */
	public function getCountryFriendlyName();
} 