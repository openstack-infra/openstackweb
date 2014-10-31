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
 * Class RegionalSupportedCompanyServiceManager
 */
abstract class RegionalSupportedCompanyServiceManager extends CompanyServiceManager  {
	/**
	 * @var IEntityRepository
	 */
	protected $region_repository;
	/**
	 * @var IEntityRepository
	 */
	protected $support_channel_type_repository;

	/**
	 * @param IEntityRepository                     $repository
	 * @param IEntityRepository                     $video_type_repository
	 * @param IMarketplaceTypeRepository            $marketplace_type_repository
	 * @param IEntityRepository                     $region_repository
	 * @param IEntityRepository                     $support_channel_type_repository
	 * @param IMarketPlaceTypeAddPolicy             $add_policy
	 * @param ICompanyServiceCanAddResourcePolicy   $add_resource_policy
	 * @param ICompanyServiceCanAddVideoPolicy      $add_video_policy
	 * @param ICompanyServiceFactory                $factory
	 * @param IMarketplaceFactory                   $marketplace_factory
	 * @param IValidatorFactory                     $validator_factory
	 * @param IMarketPlaceTypeCanShowInstancePolicy $show_policy
	 * @param ICacheService                         $cache_service
	 * @param ITransactionManager                   $tx_manager
	 */
	public function __construct(IEntityRepository                     $repository,
	                            IEntityRepository                     $video_type_repository,
	                            IMarketplaceTypeRepository            $marketplace_type_repository,
	                            IEntityRepository                     $region_repository,
	                            IEntityRepository                     $support_channel_type_repository,
	                            IMarketPlaceTypeAddPolicy             $add_policy,
	                            ICompanyServiceCanAddResourcePolicy   $add_resource_policy,
	                            ICompanyServiceCanAddVideoPolicy      $add_video_policy,
	                            ICompanyServiceFactory                $factory,
	                            IMarketplaceFactory                   $marketplace_factory,
	                            IValidatorFactory                     $validator_factory,
	                            IMarketPlaceTypeCanShowInstancePolicy $show_policy,
	                            ICacheService                         $cache_service,
	                            ITransactionManager                   $tx_manager){

		parent::__construct($repository,
			$video_type_repository,
			$marketplace_type_repository,
			$add_policy,
			$add_resource_policy,
			$add_video_policy,
			$factory,
			$marketplace_factory,
			$validator_factory,
			$show_policy,
			$cache_service,
			$tx_manager);
		$this->region_repository               = $region_repository;
		$this->support_channel_type_repository = $support_channel_type_repository;
	}

	protected function clearCollections(ICompanyService &$regional_supported_company_service){
		parent::clearCollections($regional_supported_company_service);
		$regional_supported_company_service->clearRegionalSupports();
	}

	protected function updateCollections(ICompanyService &$regional_supported_company_service, array $data){
		parent::updateCollections($regional_supported_company_service, $data);
		//add regional support
		if(array_key_exists('regional_support',$data) && is_array($data['regional_support'])){
			$regional_supports_data = $data['regional_support'];
			foreach($regional_supports_data  as $regional_support_data){
				$this->addRegionalSupport($regional_support_data,$regional_supported_company_service);
			}
		}
	}


	protected function addRegionalSupport(array $regional_support_data, IRegionalSupportedCompanyService  $regional_supported_company_service){

		$region                = $this->marketplace_factory->buildRegionById(intval($regional_support_data['region_id']));
		$support_channels_data = $regional_support_data['support_channels'];
		$regional_support      = $this->factory->buildRegionalSupport($region,$regional_supported_company_service);


		$region = $this->region_repository->getById($regional_support->getRegion()->getIdentifier());
		if(!$region) throw new NotFoundEntityException('','');
		foreach($regional_support->getSupportChannelTypes() as $support_channel_type){
			if(!$this->support_channel_type_repository->getById($support_channel_type->getIdentifier()))
				throw new NotFoundEntityException('','');
		}
		$regional_supported_company_service->addRegionalSupport($regional_support);

		foreach($support_channels_data as $support_channel_data){
			$support_channel_type = $this->marketplace_factory->buildSupportChannelTypeById(intval($support_channel_data['type_id']));
			$regional_support->addSupportChannelType($support_channel_type,$support_channel_data['data']);
		}
	}
} 