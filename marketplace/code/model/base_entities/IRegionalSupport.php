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
 * Interface IRegionalSupport
 */
interface IRegionalSupport extends IEntity {

	/**
	 * @return int
	 */
	public function getOrder();

	/**
	 * @param int $order
	 * @return void
	 */
	public function setOrder($order);

	/**
	 * @return IRegion
	 */
	public function getRegion();

	/**
	 * @param IRegion $region
	 * @return void
	 */
	public function setRegion(IRegion $region);

	/**
	 * @return IRegionalSupportedCompanyService
	 */
	public function getCompanyService();

	/**
	 * @param IRegionalSupportedCompanyService $company_service
	 * @return void
	 */
	public function setCompanyService(IRegionalSupportedCompanyService $company_service);

	/**
	 * @return ISupportChannelType[]
	 */
	public function getSupportChannelTypes();

	/**
	 * @param ISupportChannelType $channel_type
	 * @param string                    $data
	 * @return void
	 */
	public function addSupportChannelType(ISupportChannelType $channel_type, $data);

	public function clearChannelTypes();
} 