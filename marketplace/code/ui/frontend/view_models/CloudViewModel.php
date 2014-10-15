<?php

/**
 * Class CloudViewModel
 */
final class CloudViewModel {

	public static function getDataCenterLocationsJson(ICloudService $cloud){
		$locations = array();
		foreach($cloud->getDataCentersLocations() as $location){
			$json_data = array();
			$json_data['country']  = Geoip::countryCode2name($location->getCountry());
			$json_data['city']     = $location->getCity();
			$json_data['lat']      = $location->getLat();
			$json_data['lng']      = $location->getLng();
			$json_data['color']    = $location->getDataCenterRegion()->getColor();
			$json_data['endpoint'] = $location->getDataCenterRegion()->getEndpoint();
			$json_data['zone']     = $location->getDataCenterRegion()->getName();
			$json_data['availability_zones'] = array();
			$json_data['product_name'] = $cloud->getName();
			$json_data['owner']        = $cloud->getCompany()->getName();
			foreach($location->getAvailabilityZones() as $az ){
				$json_data_az = array();
				$json_data_az['name'] = $az->getName();
				array_push($json_data['availability_zones'],$json_data_az);
			}
			array_push($locations,$json_data);
		}
		return json_encode($locations);
	}

	public static function getPricingSchemas(){
		$pricing_schema_repository = new SapphirePricingSchemaRepository;
		list($list,$size ) = $pricing_schemas = $pricing_schema_repository->getAll(new QueryObject(),0,1000);
		return new ArrayList($list);
	}

	public static function getEnabledPricingSchemas(ICloudService $cloud){
	  $res = array();
	  if(count($cloud->getCapabilities())>0){
			$capabilities            = $cloud->getCapabilities();
			$enabled_pricing_schemas = reset($capabilities)->getPricingSchemas();
			if(count($enabled_pricing_schemas)>0){
				foreach($enabled_pricing_schemas as $ps){
					array_push($res,$ps->getIdentifier());
				}
			}
		}
		return json_encode($res);
	}

} 