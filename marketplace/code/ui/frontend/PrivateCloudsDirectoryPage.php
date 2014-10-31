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
 * Class PrivateCloudsDirectoryPage
 */
final class PrivateCloudsDirectoryPage extends MarketPlaceDirectoryPage
{
	static $allowed_children = "none";
}

final class PrivateCloudsDirectoryPage_Controller extends CloudsDirectoryPage_Controller{

	/**
	 * @return string
	 */
	function getCloudTypeForJS(){
		return 'private-clouds';
	}
	/**
	 * @return IOpenStackImplementationRepository
	 */
	function buildCloudRepository(){
		return new SapphirePrivateCloudRepository;
	}

	/**
	 * @param IEntityRepository                              $repository
	 * @param IEntityRepository                              $video_type_repository
	 * @param IMarketplaceTypeRepository                     $marketplace_type_repository
	 * @param IEntityRepository                              $guest_os_repository
	 * @param IEntityRepository                              $hypervisor_type_repository
	 * @param IOpenStackApiVersionRepository                 $api_version_repository
	 * @param IOpenStackComponentRepository                  $component_repository
	 * @param IOpenStackReleaseRepository                    $release_repository
	 * @param IEntityRepository                              $region_repository
	 * @param IEntityRepository                              $support_channel_type_repository
	 * @param IOpenStackReleaseSupportedApiVersionRepository $supported_version_repository
	 * @param IMarketPlaceTypeAddPolicy                      $add_policy
	 * @param ICompanyServiceCanAddResourcePolicy            $add_resource_policy
	 * @param ICompanyServiceCanAddVideoPolicy               $add_video_policy
	 * @param ICloudFactory                                  $factory
	 * @param IMarketplaceFactory                            $marketplace_factory
	 * @param IValidatorFactory                              $validator_factory
	 * @param IOpenStackApiFactory                           $api_factory
	 * @param IGeoCodingService                              $geo_coding_service
	 * @param IMarketPlaceTypeCanShowInstancePolicy          $show_policy
	 * @param ICacheService                                  $cache_service
	 * @param ITransactionManager                            $tx_manager
	 * @return CloudManager
	 */
	function buildCloudManager(IEntityRepository $repository,
	                           IEntityRepository $video_type_repository,
	                           IMarketplaceTypeRepository $marketplace_type_repository,
	                           IEntityRepository $guest_os_repository,
	                           IEntityRepository $hypervisor_type_repository,
	                           IOpenStackApiVersionRepository $api_version_repository,
	                           IOpenStackComponentRepository $component_repository,
	                           IOpenStackReleaseRepository $release_repository,
	                           IEntityRepository $region_repository,
	                           IEntityRepository $support_channel_type_repository,
	                           IOpenStackReleaseSupportedApiVersionRepository $supported_version_repository,
		//policies
	                           IMarketPlaceTypeAddPolicy $add_policy,
	                           ICompanyServiceCanAddResourcePolicy $add_resource_policy,
	                           ICompanyServiceCanAddVideoPolicy $add_video_policy,
		//factories
	                           ICloudFactory $factory,
	                           IMarketplaceFactory $marketplace_factory,
	                           IValidatorFactory $validator_factory,
	                           IOpenStackApiFactory $api_factory,
	                           IGeoCodingService $geo_coding_service,
	                           IMarketPlaceTypeCanShowInstancePolicy $show_policy,
	                           ICacheService $cache_service,
	                           ITransactionManager $tx_manager)	{
		return new PrivateCloudManager (
			$repository,
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
			$geo_coding_service,
			$show_policy,
			$cache_service,
			$tx_manager
		);
	}

	/**
	 * @return ICloudFactory
	 */
	function buildCloudFactory(){
		return new PrivateCloudFactory;
	}

	/**
	 * @return IMarketPlaceTypeAddPolicy
	 */
	function buildCloudAddPolicy(){
		return new PrivateCloudAddPolicy(new SapphirePrivateCloudRepository, new SapphireMarketPlaceTypeRepository);
	}

	/**
	 * @return ICloudsDataCenterLocationsQueryHandler
	 */
	function buildCloudLocationsQuery(){
		return new PrivateCloudsDataCenterLocationsQueryHandler;
	}

	/**
	 * @return IQueryHandler
	 */
	function buildCloudServicesQuery(){
		return new PrivateCloudsServicesQueryHandler;
	}

	function renderCloud(){
		try{
			$params              = $this->request->allParams();
			$company_url_segment = Convert::raw2sql($params["Company"]);
			$slug                = Convert::raw2sql($params["Slug"]);
			$query               = new QueryObject();
			$query->addAddCondition(QueryCriteria::equal('Slug',$slug));
			$public_cloud       = $this->cloud_repository->getBy($query);
			if(!$public_cloud) throw new NotFoundEntityException('','');
			if($public_cloud->getCompany()->URLSegment != $company_url_segment) throw new NotFoundEntityException('','');
			Requirements::javascript("marketplace/code/ui/frontend/js/openstack.implementation.capabilities.js");
			Requirements::javascript("marketplace/code/ui/frontend/js/cloud.page.js");
			return $this->Customise($public_cloud)->renderWith(array('CloudsDirectoryPage_cloud','PrivateCloudsDirectoryPage','MarketPlacePage'));
		}
		catch (Exception $ex) {
			return $this->httpError(404, 'Sorry that Private Cloud  could not be found!.');
		}
	}
}