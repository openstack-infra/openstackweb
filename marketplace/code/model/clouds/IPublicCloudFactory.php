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
 * Interface IPublicCloudFactory
 */
interface IPublicCloudFactory extends ICloudFactory {

	/**
	 * @param string $city
	 * @param string $state
	 * @param string $country
	 * @param float $lat
	 * @param float $lng
	 * @param IDataCenterRegion $region
	 * @return IDataCenterLocation
	 */
	public function buildDataCenterLocation($city,$state,$country,$lat,$lng,IDataCenterRegion $region);

	/**
	 * @param                     $name
	 * @param IDataCenterLocation $location
	 * @return IAvailabilityZone
	 */
	public function buildAZ($name,IDataCenterLocation $location);

	/**
	 * @param string $name
	 * @param string $color
	 * @param string $endpoint
	 * @return IDataCenterRegion
	 */
	public function buildDataCenterRegion($name,$color, $endpoint);
} 