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
 * Class PublicCloudFactory
 */
final class PublicCloudFactory
	extends CloudFactory {

	/**
	 * @param string           $name
	 * @param string           $overview
	 * @param ICompany         $company
	 * @param bool             $active
	 * @param IMarketPlaceType $marketplace_type
	 * @param null|string      $call_2_action_url
	 * @return ICompanyService
	 */
	public function buildCompanyService($name, $overview, ICompany $company, $active, IMarketPlaceType $marketplace_type, $call_2_action_url = null)
	{
		$public_cloud = new PublicCloudService;
		$public_cloud->setName($name);
		$public_cloud->setOverview($overview);
		$public_cloud->setCompany($company);
		if($active)
			$public_cloud->activate();
		else
			$public_cloud->deactivate();
		$public_cloud->setMarketplace($marketplace_type);
		$public_cloud->setCall2ActionUri($call_2_action_url);
		return $public_cloud;
	}

	/**
	 * @param $id
	 * @return ICompanyService
	 */
	public function buildCompanyServiceById($id)
	{
		$public_cloud     = new PublicCloudService;
		$public_cloud->ID = $id;
		return $public_cloud;
	}

}