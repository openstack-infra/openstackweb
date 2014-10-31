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
 * Class ConsultantManager
 */
final class ConsultantManager extends RegionalSupportedCompanyServiceManager  {
	/**

	/**
	 * @var ISpokenLanguageRepository
	 */
	protected $language_repository;

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
	 * @var IEntityRepository
	 */
	protected $configuration_management_expertise_repository;

	/**
	 * @var IEntityRepository
	 */
	protected $service_offered_repository;

	/**
	 * @var IGeoCodingService
	 */
	protected $geo_coding_service;


	public function __construct(IEntityRepository                   $repository,
	                            IEntityRepository                   $video_type_repository,
	                            IMarketplaceTypeRepository          $marketplace_type_repository,
	                            IOpenStackApiVersionRepository      $api_version_repository,
	                            IOpenStackComponentRepository       $component_repository,
	                            IOpenStackReleaseRepository         $release_repository,
	                            IEntityRepository                   $region_repository,
	                            IEntityRepository                   $support_channel_type_repository,
	                            ISpokenLanguageRepository           $language_repository,
								IEntityRepository                   $configuration_management_expertise_repository,
								IEntityRepository                   $service_offered_repository,
								//policies
	                            IMarketPlaceTypeAddPolicy           $add_policy,
	                            ICompanyServiceCanAddResourcePolicy $add_resource_policy,
	                            ICompanyServiceCanAddVideoPolicy    $add_video_policy,
								//factories
							    IConsultantFactory                    $factory,
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

		$this->language_repository                           = $language_repository;
		$this->api_factory                                   = $api_factory;
		$this->api_version_repository                        = $api_version_repository;
		$this->component_repository                          = $component_repository;
		$this->release_repository                            = $release_repository;
		$this->configuration_management_expertise_repository = $configuration_management_expertise_repository;
		$this->service_offered_repository                    = $service_offered_repository;
		$this->geo_coding_service                            = $geo_coding_service;
	}

	/**
	 * @param ICompanyService $consultant
	 * @param array           $data
	 * @throws NotFoundEntityException
	 */
	protected function updateCollections(ICompanyService &$consultant, array $data){
		parent::updateCollections($consultant,$data);
		//languages
		if(array_key_exists('languages_spoken',$data) && is_array($data['languages_spoken'])){
			$languages_data = $data['languages_spoken'];
			$lang_order = 0;
			foreach($languages_data as $language_name){
				$language = $this->language_repository->getByName($language_name);
				if(!$language){
					$language = $this->factory->buildSpokenLanguage($language_name);
					$this->language_repository->add($language);
				}
				$consultant->addSpokenLanguages($language, $lang_order++);
			}
		}
		//config management
		if(array_key_exists('configuration_management',$data) && is_array($data['configuration_management'])){
			$configuration_management_expertise_data = $data['configuration_management'];
			foreach($configuration_management_expertise_data as $config_id){
				$config = $this->configuration_management_expertise_repository->getById($config_id);
				if(!$config) throw new NotFoundEntityException('ConfigurationManagementType',sprintf(' id %s',$config_id));
				$consultant->addConfigurationManagementExpertise($config);
			}
		}
		//services offered
		if(array_key_exists('services_offered',$data) && is_array($data['services_offered'])){
			$services_offered_data = $data['services_offered'];
			foreach($services_offered_data as $service_data){
				$service =  $this->service_offered_repository->getById(intval($service_data['id']));
				if(!$service) throw new NotFoundEntityException('ConsultantServiceOfferedType',sprintf(' id %s',intval($service['id'])));
				if(array_key_exists('regions',$service_data) && is_array($service_data['regions'])){
					$regions = $service_data['regions'];
					foreach($regions as $region_id){
						$region = $this->region_repository->getById(intval($region_id));
						if(!$region) throw new NotFoundEntityException('Region',sprintf(' id %s',intval($region_id)));
						$consultant->addServiceOffered($service,$region);
					}
				}
			}
		}
		//Areas of OpenStack Expertise
		if(array_key_exists('expertise_areas',$data) && is_array($data['expertise_areas'])){
			$expertise_areas_data = $data['expertise_areas'];
			foreach($expertise_areas_data as $expertise_area_id){
				$component = $this->component_repository->getById(intval($expertise_area_id));
				if(!$component)  throw new NotFoundEntityException('OpenStackComponent',sprintf(' id %s',intval($expertise_area_id)));
				$consultant->addExpertiseArea($component);
			}
		}
		//Reference Clients
		if(array_key_exists('reference_clients',$data) && is_array($data['reference_clients'])){
			$reference_clients_data = $data['reference_clients'];
			foreach($reference_clients_data as $client_name){
				$client = $this->factory->buildClient(trim($client_name));
				$consultant->addPreviousClients($client);
			}
		}
		//Offices
		if(array_key_exists('offices',$data) && is_array($data['offices'])){
			$reference_offices_data = $data['offices'];
			foreach($reference_offices_data as $office_data){
				$validator = $this->validator_factory->buildValidatorForOffice($office_data);
				if ($validator->fails()) {
					return $this->validationError($validator->messages());
				}
				$address_info = new AddressInfo(
					$office_data['address_1'],
					$office_data['address_2'],
					$office_data['zip_code'],
					$office_data['state'],
					$office_data['city'],
					$office_data['country']
				);
				//list($lat,$lng) = $this->geo_coding_service->getAddressCoordinates($address_info);
				$office = $this->factory->buildOffice($address_info);
				$office->setLat((float)$office_data['lat']);
				$office->setLng((float)$office_data['lng']);
				$consultant->addOffice($office);
			}
		}
	}

	/**
	 * @param ICompanyService $consultant
	 */
	protected function clearCollections(ICompanyService &$consultant){
		parent::clearCollections($consultant);
		$consultant->clearOffices();
		$consultant->clearClients();
		$consultant->clearSpokenLanguages();
		$consultant->clearConfigurationManagementExpertises();
		$consultant->clearExpertiseAreas();
		$consultant->clearServicesOffered();
	}
	/**
	 * @return IMarketPlaceType
	 * @throws NotFoundEntityException
	 */
	protected function getMarketPlaceType()
	{
		$marketplace_type =  $this->marketplace_type_repository->getByType(IConsultant::MarketPlaceType);
		if(!$marketplace_type)
			throw new NotFoundEntityException('MarketPlaceType',sprintf("type %s ",IConsultant::MarketPlaceType));
		return $marketplace_type;
	}

	protected function addRegionalSupport(array $regional_support_data, IRegionalSupportedCompanyService  $consultant){

		$region                = $this->marketplace_factory->buildRegionById(intval($regional_support_data['region_id']));
		$support_channels_data = $regional_support_data['support_channels'];
		$regional_support      = $this->factory->buildRegionalSupport($region, $consultant);


		$region = $this->region_repository->getById($regional_support->getRegion()->getIdentifier());
		if(!$region) throw new NotFoundEntityException('','');
		foreach($regional_support->getSupportChannelTypes() as $support_channel_type){
			if(!$this->support_channel_type_repository->getById($support_channel_type->getIdentifier()))
				throw new NotFoundEntityException('','');
		}
		$consultant->addRegionalSupport($regional_support);

		foreach($support_channels_data as $support_channel_data){
			$support_channel_type = $this->marketplace_factory->buildSupportChannelTypeById(intval($support_channel_data['type_id']));
			$regional_support->addSupportChannelType($support_channel_type,$support_channel_data['data']);
		}
	}
}