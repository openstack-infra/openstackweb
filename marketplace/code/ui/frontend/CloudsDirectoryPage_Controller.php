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
 * Class CloudsDirectoryPage_Controller
 */
abstract class CloudsDirectoryPage_Controller extends MarketPlaceDirectoryPage_Controller {

	private static $allowed_actions = array('handleIndex');

	/**
	 * @var IOpenStackImplementationRepository
	 */
	protected $cloud_repository;

	/**
	 * @var IEntityRepository
	 */
	protected $pricing_schema_repository;

	/**
	 * @var PublicCloudManager
	 */
	protected $manager;

	static $url_handlers = array(
		'$Company!/$Slug!' => 'handleIndex',
	);

	/**
	 * @var IQueryHandler
	 */
	protected $clouds_services_query;

	/**
	 * @var ICloudsDataCenterLocationsQueryHandler
	 */
	protected $clouds_locations_query;

	/**
	 * @return string
	 */
	abstract function getCloudTypeForJS();

	/**
	 * @return IOpenStackImplementationRepository
	 */
	abstract function buildCloudRepository();

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
	abstract function buildCloudManager(IEntityRepository                    $repository,
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
	                                    ITransactionManager                   $tx_manager);


	/**
	 * @return ICloudFactory
	 */
	abstract function buildCloudFactory();

	/**
	 * @return IMarketPlaceTypeAddPolicy
	 */
	abstract function buildCloudAddPolicy();

	/**
	 * @return ICloudsDataCenterLocationsQueryHandler
	 */
	abstract function buildCloudLocationsQuery();

	/**
	 * @return IQueryHandler
	 */
	abstract function buildCloudServicesQuery();

	function init()	{
		parent::init();
		$cloud_type = $this->getCloudTypeForJS();

		Requirements::customScript("
		var cloud_type = '{$cloud_type}'
		jQuery(document).ready(function($) {
            $('#{$cloud_type}','.marketplace-nav').addClass('current');
        });");

		Requirements::css("themes/openstack/javascript/jquery-ui-1.10.3.custom/css/smoothness/jquery-ui-1.10.3.custom.min.css");
		Requirements::css("themes/openstack/css/chosen.css", "screen,projection");
		Requirements::javascript("themes/openstack/javascript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js");
		Requirements::javascript(Director::protocol()."maps.googleapis.com/maps/api/js?sensor=false");
		Requirements::javascript("marketplace/code/ui/admin/js/utils.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/markerclusterer.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/oms.min.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/infobubble-compiled.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/google.maps.jquery.js");
		Requirements::javascript("themes/openstack/javascript/chosen.jquery.min.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/clouds.directory.page.js");
		Requirements::customScript($this->GATrackingCode());

		$this->cloud_repository          = $this->buildCloudRepository();
		$this->pricing_schema_repository = new SapphirePricingSchemaRepository;

		//google geo coding settings
		$google_geo_coding_api_key     = null;
		$google_geo_coding_client_id   = null;
		$google_geo_coding_private_key = null;
		if(defined('GOOGLE_GEO_CODING_API_KEY')){
			$google_geo_coding_api_key = GOOGLE_GEO_CODING_API_KEY;
		}
		else if (defined('GOOGLE_GEO_CODING_CLIENT_ID') && defined('GOOGLE_GEO_CODING_PRIVATE_KEY')){
			$google_geo_coding_client_id   = GOOGLE_GEO_CODING_CLIENT_ID;
			$google_geo_coding_private_key = GOOGLE_GEO_CODING_PRIVATE_KEY;
		}

		$this->manager = $this->buildCloudManager(
			$this->cloud_repository,
			new SapphireMarketPlaceVideoTypeRepository,
			new SapphireMarketPlaceTypeRepository,
			new SapphireGuestOSTypeRepository,
			new SapphireHyperVisorTypeRepository,
			new SapphireOpenStackApiVersionRepository,
			new SapphireOpenStackComponentRepository,
			new SapphireOpenStackReleaseRepository,
			new SapphireRegionRepository,
			new SapphireSupportChannelTypeRepository,
			new SapphireOpenStackReleaseSupportedApiVersionRepository,
			$this->buildCloudAddPolicy(),
			new CompanyServiceCanAddResourcePolicy,
			new CompanyServiceCanAddVideoPolicy,
			$this->buildCloudFactory(),
			new MarketplaceFactory,
			new ValidatorFactory,
			new OpenStackApiFactory,
			new GoogleGeoCodingService(
				new SapphireGeoCodingQueryRepository,
				new UtilFactory,
				SapphireTransactionManager::getInstance(),
				$google_geo_coding_api_key,
				$google_geo_coding_client_id,
				$google_geo_coding_private_key),
			null,
			new SessionCacheService,
			SapphireTransactionManager::getInstance()
		);

		$this->clouds_locations_query = $this->buildCloudLocationsQuery();
		$this->clouds_services_query  = $this->buildCloudServicesQuery();
	}

	public function handleIndex() {
		$params = $this->request->allParams();
		if(isset($params["Company"]) && isset($params["Slug"])){
			//render instance ...
			return $this->renderCloud();
		}
	}

	abstract function renderCloud();

	public function getClouds(){
		return new ArrayList($this->manager->getActives());
	}

	public function getDataCenterLocationsJson(){
		$locations = array();
		$query = new QueryObject;
		$query->addAddCondition(QueryCriteria::equal("Active",true));
		list($list,$size) = $this->cloud_repository->getAll($query,0,1000);

		foreach($list as $cloud){
			foreach($cloud->getDataCentersLocations() as $location){
				$json_data = array();
				$json_data['color']        = $location->getDataCenterRegion()->getColor();
				$json_data['country']      = Geoip::countryCode2name($location->getCountry());
				$json_data['city']         = $location->getCity();
				$json_data['lat']          = $location->getLat();
				$json_data['lng']          = $location->getLng();
				$json_data['product_name'] = $cloud->getName();
				$json_data['product_url']  = $this->Link().$cloud->getCompany()->URLSegment.'/'. strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cloud->getName())));
				$json_data['owner']        = $cloud->getCompany()->getName();
				array_push($locations,$json_data);
			}
		}
		return json_encode($locations);
	}

	public function getCurrentDataCenterLocationsJson(){
		$params              = $this->request->allParams();
		$company_url_segment = Convert::raw2sql($params["Company"]);
		$slug                = Convert::raw2sql($params["Slug"]);
		$query               = new QueryObject();
		$query->addAddCondition(QueryCriteria::equal('Slug',$slug));
		$cloud       = $this->cloud_repository->getBy($query);
		if(!$cloud) throw new NotFoundEntityException('','');
		if($cloud->getCompany()->URLSegment != $company_url_segment) throw new NotFoundEntityException('','');
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

	public function getPricingSchemas(){
		list($list,$size ) = $pricing_schemas = $this->pricing_schema_repository->getAll(new QueryObject(),0,1000);
		return new ArrayList($list);
	}

	public function getEnabledPricingSchemas(){
		$params              = $this->request->allParams();
		$company_url_segment = Convert::raw2sql($params["Company"]);
		$slug                = Convert::raw2sql($params["Slug"]);
		$query               = new QueryObject();
		$res                 = array();
		$query->addAddCondition(QueryCriteria::equal('Slug',$slug));
		$cloud       = $this->cloud_repository->getBy($query);
		if(!$cloud) throw new NotFoundEntityException('','');
		if($cloud->getCompany()->URLSegment != $company_url_segment) throw new NotFoundEntityException('','');
		if(count($cloud->getCapabilities())>0){
			$capabilities = $cloud->getCapabilities();
			$enabled_pricing_schemas = reset($capabilities)->getPricingSchemas();
			if(count($enabled_pricing_schemas)>0){

				foreach($enabled_pricing_schemas as $ps){
					array_push($res,$ps->getIdentifier());
				}
			}
		}
		return json_encode($res);
	}

	public function ServicesCombo(){
		$source = array();
		$result = $this->clouds_services_query->handle(new OpenStackImplementationNamesQuerySpecification(''));
		foreach($result->getResult() as $dto){
			$source[$dto->getValue()] =  $dto->getValue();
		}
		$ddl = new DropdownField('service-term"',$title=null,$source,$value='');
		$ddl->setEmptyString('-- Show All --');
		return $ddl;
	}

	public function LocationCombo(){
		$source = array();
		$result = $this->clouds_locations_query->handle(new OpenStackImplementationNamesQuerySpecification(''));
		foreach($result->getResult() as $dto){
			$source[$dto->getValue()] =  $dto->getValue();
		}
		$ddl = new DropdownField('location-term"',$title = null,$source,$value='');
		$ddl->setEmptyString('-- Show All --');
		return $ddl;
	}
}
