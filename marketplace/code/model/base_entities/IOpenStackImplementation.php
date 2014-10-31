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
 * Interface IOpenStackImplementation
 */
interface IOpenStackImplementation extends IRegionalSupportedCompanyService {

	const AbstractMarketPlaceType = 'Implementation';
	/**
	 * @return IHyperVisorType[]
	 */
	public function getHyperVisors();

	/**
	 * @param IHyperVisorType $hypervisor
	 * @return void
	 */
	public function addHyperVisor(IHyperVisorType $hypervisor);

	/**
	 * @return IGuestOSType[]
	 */
	public function getGuests();

	/**
	 * @param IGuestOSType $guest
	 * @return void
	 */
	public function addGuest(IGuestOSType $guest);



	/**
	 * @return IOpenStackImplementationApiCoverage[]
	 */
	public function getCapabilities();

	/**
	 * @param IOpenStackImplementationApiCoverage $capability
	 * @return void
	 */
	public function addCapability(IOpenStackImplementationApiCoverage $capability);

	public function clearCapabilities();
	public function clearHypervisors();
	public function clearGuests();
}