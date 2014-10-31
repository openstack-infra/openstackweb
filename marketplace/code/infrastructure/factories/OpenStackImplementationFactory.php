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
abstract class OpenStackImplementationFactory
	extends RegionalSupportedCompanyServiceFactory
	implements IOpenStackImplementationFactory {
	/**
	 * @param int                      $coverage_percent
	 * @param IReleaseSupportedApiVersion $release_supported_api_version
	 * @param IOpenStackImplementation $implementation
	 * @return IOpenStackImplementationApiCoverage
	 */
	public function buildCapability($coverage_percent, IReleaseSupportedApiVersion $release_supported_api_version, IOpenStackImplementation $implementation)
	{
		$capability = new OpenStackImplementationApiCoverage;
		$capability->setCoveragePercent($coverage_percent);
		$capability->setReleaseSupportedApiVersion($release_supported_api_version);
		$capability->setImplementation($implementation);
		return $capability;
	}
}