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
 * Class CloudAssembler
 */
final class CloudAssembler {

	public static function convertCloudToArray(ICloudService $cloud){
		$res = OpenStackImplementationAssembler::convertOpenStackImplementationToArray($cloud);
		//override capabilities
		$res['capabilities'] = array();
		foreach($cloud->getCapabilities() as $service){
			$service_res =  OpenStackImplementationAssembler::convertCapabilityToArray($service);
			$service_res['pricing_schemas'] = array();
			foreach($service->getPricingSchemas() as $ps){
				array_push($service_res['pricing_schemas'], $ps->getIdentifier());
			}
			array_push($res['capabilities'],$service_res);
		}

		$data_centers = array();
		$locations    = array();
		$regions      = array();

		foreach($cloud->getDataCenterRegions() as $region){
			array_push($regions, CloudAssembler::convertDataCenterRegionToArray($region));
		}

		foreach($cloud->getDataCentersLocations() as $location){
			array_push($locations,CloudAssembler::convertDataCenterLocationToArray($location));
		}

		$data_centers['regions']   = $regions;
		$data_centers['locations'] = $locations;
		$res['data_centers']       = $data_centers;
		return $res;
	}

	public static function convertDataCenterRegionToArray(IDataCenterRegion $region){
		$res = array();
		$res['name']     = $region->getName();
		$res['color']    = $region->getColor();
		$res['endpoint'] = $region->getEndpoint();
		return $res;
	}

	public static function convertDataCenterLocationToArray(IDataCenterLocation $location){
		$res = array();
		$res['city']    = $location->getCity();
		$res['state']   = $location->getState();
		$res['country'] = $location->getCountry();
		$res['region']  = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $location->getDataCenterRegion()->getName()));
		$res['availability_zones'] = array();
		foreach($location->getAvailabilityZones() as $az){
			array_push($res['availability_zones'], CloudAssembler::convertAZtoArray($az));
		}
		return $res;
	}

	public static function convertAZtoArray(IAvailabilityZone $az){
		$res = array();
		$res['name'] = $az->getName();
		return $res;
	}
}