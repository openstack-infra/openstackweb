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
 * Interface IDataCenterLocation
 */
interface IDataCenterLocation extends IEntity {

	/**
	 * @param string $country
	 * @return void
	 */
	public function setCountry($country);

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
	 * @return string
	 */
	public function getState();

	/**
	 * @param string $state
	 * @return void
	 */
	public function setState($state);

	/**
	 * @param ICloudService $cloud_service
	 * @return void
	 */
	public function setCloudService(ICloudService $cloud_service);

	/**
	 * @return ICloudService
	 */
	public function getCloudService();

	/**
	 * @return IAvailabilityZone[]
	 */
	public function getAvailabilityZones();

	/**
	 * @return void
	 */
	public function clearAvailabilityZones();

	/**
	 * @param IAvailabilityZone $az
	 * @return void
	 */
	public function addAvailabilityZone(IAvailabilityZone $az);

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
	 * @return IDataCenterRegion
	 */
	public function getDataCenterRegion();

	/**
	 * @param IDataCenterRegion $region
	 * @return void
	 */
	public function setDataCenterRegion(IDataCenterRegion $region);
} 