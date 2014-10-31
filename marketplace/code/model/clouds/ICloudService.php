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
 * Interface ICloudService
 */
interface ICloudService extends IOpenStackImplementation {

	/**
	 * @return IDataCenterLocation[]
	 */
	public function getDataCentersLocations();

	/**
	 * @param IDataCenterLocation $data_center_location
	 * @throws EntityValidationException
	 * @return void
	 */
	public function addDataCenterLocation(IDataCenterLocation $data_center_location);

	/**
	 * @return void
	 */
	public function clearDataCentersLocations();

	/**
	 * @param string $region_slug
	 * @return IDataCenterRegion
	 */
	public function getDataCenterRegion($region_slug);


	/**
	 * @return IDataCenterRegion[]
	 */
	public function getDataCenterRegions();

	/**
	 * @param IDataCenterRegion $region
	 * @return void
	 */
	public function addDataCenterRegion	(IDataCenterRegion $region);

	/**
	 * @return void
	 */
	public function clearDataCenterRegions();
} 