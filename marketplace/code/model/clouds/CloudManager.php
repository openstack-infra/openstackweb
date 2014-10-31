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
 * Class CloudManager
 */
abstract class CloudManager extends OpenStackImplementationManager {

	/**
	 * @var IGeoCodingService
	 */
	private $geo_coding_service;

	public function __construct(IEntityRepository                    $repository,
	                            IEntityRepository                    $video_type_repository,
	                            IMarketplaceTypeRepository           $marketplace_type_repository,
	                            IEntityRepository                    $guest_os_repository,
	                            IEntityRepository                    $hypervisor_type_repository,
	                            IOpenStackApiVersionRepository       $api_version_repository,
	                            IOpenStackComponentRepository        $component_repository,
	                            IOpenStackReleaseRepository          $release_repository,
	                            IEntityRepository                    $region_repository,
	                            IEntityRepository                    $support_channel_type_repository,
	                            IOpenStackReleaseSupportedApiVersionRepository $supported_version_repository,
		//policies
	                            IMarketPlaceTypeAddPolicy            $add_policy,
	                            ICompanyServiceCanAddResourcePolicy  $add_resource_policy,
	                            ICompanyServiceCanAddVideoPolicy     $add_video_policy,
		//factories
	                            ICloudFactory                         $factory,
	                            IMarketplaceFactory                   $marketplace_factory,
	                            IValidatorFactory                     $validator_factory,
	                            IOpenStackApiFactory                  $api_factory,
	                            IGeoCodingService                     $geo_coding_service,
	                            IMarketPlaceTypeCanShowInstancePolicy $show_policy,
	                            ICacheService                         $cache_service,
	                            ITransactionManager                   $tx_manager

	){
		parent::__construct($repository,
			$video_type_repository,
			$marketplace_type_repository,
			$guest_os_repository,
			$hypervisor_type_repository,
			$api_version_repository,
			$component_repository,
			$release_repository,
			$region_repository,
			$support_channel_type_repository,
			$supported_version_repository,
			$add_policy,
			$add_resource_policy,
			$add_video_policy,
			$factory,
			$marketplace_factory,
			$validator_factory,
			$api_factory,
			$show_policy,
			$cache_service,
			$tx_manager);
		$this->geo_coding_service = $geo_coding_service;
	}


	protected function clearCollections(ICompanyService &$cloud){
		parent::clearCollections($cloud);
		if($cloud instanceof ICloudService){
			$cloud->clearDataCenterRegions();
			$cloud->clearDataCentersLocations();
		}
	}

	protected function updateCollections(ICompanyService &$cloud, array $data){
		parent::updateCollections($cloud, $data);
		// add data centers locations
		if(array_key_exists('data_centers',$data) && is_array($data['data_centers'])){
			$data_centers_data = $data['data_centers'];
			if(!array_key_exists('regions',$data_centers_data) || !is_array($data_centers_data['regions']))
				return $this->validationError(array(array('message'=>'missing regions on data_centers!.')));
			if(!array_key_exists('locations',$data_centers_data) || !is_array($data_centers_data['locations']))
				return $this->validationError(array(array('message'=>'missing locations on data_centers!.')));

			$regions_data      = $data_centers_data['regions'];
			foreach($regions_data as $region_data){
				$this->addDataCenterRegion($region_data, $cloud);
			}

			$locations_data    = $data_centers_data['locations'];
			foreach($locations_data  as $location_data){
				$this->addDataCenterLocation($location_data, $cloud);
			}
		}
	}

	protected function addDataCenterRegion(array $region_data, ICloudService $cloud){
		$validator = $this->validator_factory->buildValidatorForDataCenterRegion($region_data);
		if ($validator->fails()) {
			return $this->validationError($validator->messages());
		}
		$region = $this->factory->buildDataCenterRegion($region_data['name'],$region_data['color'],$region_data['endpoint']);
		$cloud->addDataCenterRegion($region);
		$region->setCloud($cloud);
	}

	protected function addDataCenterLocation(array $location_data, ICloudService $cloud){
		//check location coordinates...

		$validator = $this->validator_factory->buildValidatorForDataCenterLocation($location_data);
		if ($validator->fails()) {
			return $this->validationError($validator->messages());
		}

		//list($lat,$lng) = $this->geo_coding_service->getCityCoordinates($location_data['city'],$location_data['country']);
		$location = $this->factory->buildDataCenterLocation(
			$location_data['city'],
			$location_data['state'],
			$location_data['country'],
			(float)$location_data['lat'],
			(float)$location_data['lng'],
			$cloud->getDataCenterRegion($location_data['region'])
		);

		if(array_key_exists('availability_zones',$location_data) && is_array($location_data['availability_zones'])){
			foreach($location_data['availability_zones'] as $az_data){
				$az = $this->factory->buildAZ($az_data['name'],$location);
				$location->addAvailabilityZone($az);
				$az->setLocation($location);
			}
		}
		$cloud->addDataCenterLocation($location);
		$location->setCloudService($cloud);
		return $cloud;
	}

	/**
	 * @param array           $capability_data
	 * @param IOpenStackImplementation $company_service
	 * @throws NotFoundEntityException
	 * @throws NonSupportedComponent
	 */
	protected function addCapability(array $capability_data, IOpenStackImplementation $company_service){
		$validator = $this->validator_factory->buildValidatorForCapability($capability_data);
		if ($validator->fails()) {
			return $this->validationError($validator->messages());
		}

		$component         = $this->component_repository->getById(intval($capability_data['component_id']));
		if(!$component) throw new NotFoundEntityException('OpenStackComponent',sprintf('id %',intval($capability_data['component_id'])));

		$release           = $this->release_repository->getById(intval($capability_data['release_id']));
		if(!$release) throw new NotFoundEntityException('OpenStackRelease',sprintf('id %',intval($capability_data['release_id'])));
		if(!$release->supportsComponent($component->getCodeName())) throw new NonSupportedComponent($release,$component);

		$supported_version = null;

		if($component->getSupportsVersioning()){
			$version           = $this->api_version_repository->getById(intval($capability_data['version_id']));
			if(!$version) throw new NotFoundEntityException('OpenStackApiVersion',sprintf('id %',intval($capability_data['version_id'])));
			$supported_version = $release->supportsApiVersion($version);
		}
		else{
			$supported_version = $this->supported_version_repository->getByReleaseAndComponentAndApiVersion(intval($capability_data['release_id']),intval($capability_data['component_id']),0);
		}

		if(!$supported_version) throw new NotFoundEntityException('OpenStackReleaseSupportedVersion','');

		$service           = $this->factory->buildCapability(intval($capability_data['coverage']),$supported_version, $company_service);
		if(array_key_exists('pricing_schemas',$capability_data) && is_array($capability_data['pricing_schemas'])){
			foreach($capability_data['pricing_schemas'] as $ps){
				$service->addPricingSchema($this->factory->buildPricingSchemaById(intval($ps)));
			}
		}
		$this->registerCapability($company_service, $service);
	}
} 