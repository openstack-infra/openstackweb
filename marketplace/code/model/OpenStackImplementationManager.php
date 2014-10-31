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
 * Class OpenStackImplementationManager
 */
abstract class OpenStackImplementationManager
	extends RegionalSupportedCompanyServiceManager {
	/**
	 * @var IEntityRepository
	 */
	protected $guest_os_repository;
	/**
	 * @var IEntityRepository
	 */
	protected $hypervisor_type_repository;
	/**
	 * @var IOpenStackApiVersionRepository
	 */
	protected $api_version_repository;
	/**
	 * @var IOpenStackComponentRepository
	 */
	protected $component_repository;
	/**
	 * @var IOpenStackReleaseRepository
	 */
	protected $release_repository;
	/**
	 * @var IOpenStackApiFactory
	 */
	protected $api_factory;

	/**
	 * @var IOpenStackReleaseSupportedApiVersionRepository
	 */
	protected $supported_version_repository;

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
                                IMarketPlaceTypeAddPolicy             $add_policy,
		                        ICompanyServiceCanAddResourcePolicy   $add_resource_policy,
	                            ICompanyServiceCanAddVideoPolicy      $add_video_policy,
								//factories
	                            IOpenStackImplementationFactory       $factory,
	                            IMarketplaceFactory                   $marketplace_factory,
	                            IValidatorFactory                     $validator_factory,
	                            IOpenStackApiFactory                  $api_factory,
	                            IMarketPlaceTypeCanShowInstancePolicy $show_policy,
	                            ICacheService                         $cache_service,
	                            ITransactionManager                   $tx_manager
							){

		parent::__construct($repository,
			                $video_type_repository,
							$marketplace_type_repository,
							$region_repository,
							$support_channel_type_repository,
			                $add_policy,
			                $add_resource_policy,
			                $add_video_policy,
							$factory,
							$marketplace_factory,
							$validator_factory,
							$show_policy,
							$cache_service,
							$tx_manager);

		$this->api_factory                     = $api_factory;
		$this->guest_os_repository             = $guest_os_repository;
		$this->hypervisor_type_repository      = $hypervisor_type_repository;
		$this->api_version_repository          = $api_version_repository;
		$this->component_repository            = $component_repository;
		$this->release_repository              = $release_repository;
		$this->supported_version_repository    = $supported_version_repository;
	}


	protected function clearCollections(ICompanyService &$implementation){
		parent::clearCollections($implementation);
		$implementation->clearCapabilities();
		$implementation->clearHypervisors();
		$implementation->clearGuests();
	}

	protected function updateCollections(ICompanyService &$implementation, array $data){
		parent::updateCollections($implementation, $data);
		if(array_key_exists('guest_os',$data) && is_array($data['guest_os'])){
			$data_guests = $data['guest_os'];
			foreach($data_guests as $guest_id){
				$this->registerGuest($implementation, $guest_id);
			}
		}
		//add hyper visors
		if(array_key_exists('hypervisors',$data) && is_array($data['hypervisors'])){
			$data_hypervisors = $data['hypervisors'];
			foreach($data_hypervisors  as $hypervisor_id){
				$this->registerHyperVisor($implementation, $hypervisor_id);
			}
		}
		//add capabilities
		if(array_key_exists('capabilities',$data) && is_array($data['capabilities'])){
			$data_capabilities = $data['capabilities'];
			foreach($data_capabilities  as $capability_data){
				$this->addCapability($capability_data,$implementation);
			}
		}
	}

	protected  function registerCapability(IOpenStackImplementation $implementation, IOpenStackImplementationApiCoverage $capability){
		//check release
		$release = $this->release_repository->getById($capability->getReleaseSupportedApiVersion()->getRelease()->getIdentifier());
		if(!$release) throw new NotFoundEntityException('','');
		//check component
		$component = $this->component_repository->getById($capability->getReleaseSupportedApiVersion()->getOpenStackComponent()->getIdentifier());
		if(!$component) throw new NotFoundEntityException('','');
		if(!$release->supportsComponent($component->getCodeName()))
			throw new InvalidAggregateRootException('','','','');
		if($component->getSupportsVersioning()){
			//check api version
			$api_version = $this->api_version_repository->getByIdAndComponent($capability->getReleaseSupportedApiVersion()->getApiVersion()->getIdentifier(),$component->getIdentifier());
			if(!$api_version) throw new NotFoundEntityException('','');
		}
		else{
			$api_version = $capability->getReleaseSupportedApiVersion()->getApiVersion();
			$api_version->setReleaseComponent($component);
		}

		$release_supported_api = $release->supportsApiVersion($api_version);
		if(!$release_supported_api) throw new InvalidAggregateRootException('','','','');
		$capability->setReleaseSupportedApiVersion($release_supported_api);

		$implementation->addCapability($capability);
	}

	protected function addCapability(array $capability_data, IOpenStackImplementation $implementation){

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

		$capability  = $this->factory->buildCapability(intval($capability_data['coverage']),$supported_version, $implementation);
		$this->registerCapability($implementation,$capability);
	}

	protected function registerGuest(IOpenStackImplementation $implementation, $guest_type_id){
		$guest = $this->guest_os_repository->getById($guest_type_id);
		if(!$guest) throw new NotFoundEntityException('','');
		$implementation->addGuest($guest);
	}

	protected function registerHyperVisor(IOpenStackImplementation $implementation, $hypervisor_type_id){
		$hypervisor = $this->hypervisor_type_repository->getById($hypervisor_type_id);
		if(!$hypervisor) throw new NotFoundEntityException('','');
		$implementation->addHyperVisor($hypervisor);
	}

}