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
 * Interface IDataCenterRegion
 */
interface IDataCenterRegion extends IEntity {
	/**
	 * @return string
	 */
	public function getName();
	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name);

	/**
	 * @return string
	 */
	public function getEndpoint();

	/**
	 * @param string $endpoint
	 * @return void
	 */
	public function setEndpoint($endpoint);

	/**
	 * @return ICloudService
	 */
	public function getCloud();

	/**
	 * @param ICloudService $cloud
	 * @return void
	 */
	public function setCloud(ICloudService $cloud);

	/**
	 * @return IDataCenterLocation[]
	 */
	public function getLocations();

	/**
	 * @param IDataCenterLocation $location
	 * @return void
	 */
	public function addLocation(IDataCenterLocation $location);

	/**
	 * @return void
	 */
	public function clearLocations();

	/**
	 * @return string
	 */
	public function getColor();

	/**
	 * @param string $color
	 * @return void
	 */
	public function setColor($color);
} 