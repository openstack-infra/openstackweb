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
 * Class PrivateCloudCrudApi
 */
final class PrivateCloudCrudApi extends CompanyServiceCrudApi {

	private $marketplace_type_repository;
	private $private_cloud_repository;

	public function __construct(){

		$this->private_cloud_repository   = new SapphirePrivateCloudRepository;
		$this->marketplace_type_repository = new SapphireMarketPlaceTypeRepository;

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

		$manager = new PrivateCloudManager (
			$this->private_cloud_repository,
			new SapphireMarketPlaceVideoTypeRepository,
			$this->marketplace_type_repository,
			new SapphireGuestOSTypeRepository,
			new SapphireHyperVisorTypeRepository,
			new SapphireOpenStackApiVersionRepository,
			new SapphireOpenStackComponentRepository,
			new SapphireOpenStackReleaseRepository,
			new SapphireRegionRepository,
			new SapphireSupportChannelTypeRepository,
			new SapphireOpenStackReleaseSupportedApiVersionRepository,
			new PrivateCloudAddPolicy($this->private_cloud_repository, $this->marketplace_type_repository),
			new CompanyServiceCanAddResourcePolicy,
			new CompanyServiceCanAddVideoPolicy,
			new PrivateCloudFactory,
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

		parent::__construct($manager,new PublicCloudFactory);

		// filters ...
		$this_var     = $this;
		$current_user = $this->current_user;
		$repository   = $this->private_cloud_repository;

		$this->addBeforeFilter('addCompanyService','check_add_company',function ($request) use($this_var, $current_user, $repository){
			$data = $this_var->getJsonRequest();
			if (!$data) return $this_var->serverError();
			$company_id = intval(@$data['company_id']);
			if(!$current_user->isMarketPlaceAdminOfCompany(IPrivateCloudService::MarketPlaceGroupSlug, $company_id))
				return $this_var->permissionFailure();
		});

		$this->addBeforeFilter('updateCompanyService','check_update_company',function ($request) use($this_var, $current_user){
			$data = $this_var->getJsonRequest();
			if (!$data) return $this_var->serverError();
			if(!$current_user->isMarketPlaceAdminOfCompany(IPrivateCloudService::MarketPlaceGroupSlug,intval(@$data['company_id'])))
				return $this_var->permissionFailure();
		});

		$this->addBeforeFilter('deleteCompanyService','check_delete_company',function ($request) use($this_var, $current_user,$repository){
			$company_service_id = intval($request->param('COMPANY_SERVICE_ID'));
			$company_service    = $repository->getById($company_service_id);
			if(!$current_user->isMarketPlaceAdminOfCompany(IPrivateCloudService::MarketPlaceGroupSlug, $company_service->getCompany()->getIdentifier()))
				return $this_var->permissionFailure();
		});

	}

	/**
	 * @var array
	 */
	static $url_handlers = array(
		'GET $COMPANY_SERVICE_ID!'    => 'getPrivateCloud',
		'DELETE $COMPANY_SERVICE_ID!' => 'deleteCompanyService',
		'POST '                       => 'addCompanyService',
		'PUT '                        => 'updateCompanyService',
	);

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'getPrivateCloud',
		'deleteCompanyService',
		'addCompanyService',
		'updateCompanyService'
	);

	public function getPrivateCloud(){
		$company_service_id  = intval($this->request->param('COMPANY_SERVICE_ID'));
		$private_cloud        = $this->private_cloud_repository->getById($company_service_id);
		if(!$private_cloud)
			return $this->notFound();
		return $this->ok(CloudAssembler::convertCloudToArray($private_cloud));
	}

	public function addCompanyService(){
		try {
			return parent::addCompanyService();
		}
		catch (NonSupportedApiVersion $ex1) {
			SS_Log::log($ex1,SS_Log::ERR);
			return $this->validationError($ex1->getMessage());
		}
		catch (NonSupportedComponent $ex2) {
			SS_Log::log($ex2,SS_Log::ERR);
			return $this->validationError($ex2->getMessage());
		}
		catch (Exception $ex) {
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	public function updateCompanyService(){
		try {
			return parent::updateCompanyService();
		}
		catch (NonSupportedApiVersion $ex1) {
			SS_Log::log($ex1,SS_Log::ERR);
			return $this->validationError($ex1->getMessage());
		}
		catch (NonSupportedComponent $ex2) {
			SS_Log::log($ex2,SS_Log::ERR);
			return $this->validationError($ex2->getMessage());
		}
		catch (Exception $ex) {
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}
} 