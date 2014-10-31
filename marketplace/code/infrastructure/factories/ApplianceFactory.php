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
 * Class ApplianceFactory
 */
final class ApplianceFactory extends OpenStackImplementationFactory {

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
		$appliance = new Appliance;
		$appliance->setName($name);
		$appliance->setOverview($overview);
		$appliance->setCompany($company);
		if($active)
			$appliance->activate();
		else
			$appliance->deactivate();
		$appliance->setMarketplace($marketplace_type);
		$appliance->setCall2ActionUri($call_2_action_url);
		return $appliance;
	}

	/**
	 * @param $id
	 * @return ICompanyService
	 */
	public function buildCompanyServiceById($id)
	{
		$appliance     = new Appliance;
		$appliance->ID = $id;
		return $appliance;
	}

}