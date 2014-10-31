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
 * Class ConsultantsCrudApi
 */
final class ConsultantsCrudApi extends CompanyServiceCrudApi {


	/**
	 * @var array
	 */
	private static $url_handlers = array(
		'GET languages'               => 'getLanguages',
		'GET $COMPANY_SERVICE_ID!'    => 'getConsultant',
		'DELETE $COMPANY_SERVICE_ID!' => 'deleteCompanyService',
		'POST '                       => 'addCompanyService',
		'PUT '                        => 'updateCompanyService',
	);

	/**
	 * @var array
	 */
	private static $allowed_actions = array(
		'getConsultant',
		'deleteCompanyService',
		'addCompanyService',
		'updateCompanyService',
		'getLanguages'
	);

	/**
	 * @var IEntityRepository
	 */
	private $consultant_repository;
	/**
	 * @var IEntityRepository
	 */
	private $languages_repository;

	public function __construct(){

		$this->consultant_repository       = new SapphireConsultantRepository;
		$this->marketplace_type_repository = new SapphireMarketPlaceTypeRepository;
		$this->languages_repository        = new SapphireSpokenLanguageRepository;
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

		$manager = new ConsultantManager (
			$this->consultant_repository,
			new SapphireMarketPlaceVideoTypeRepository,
			$this->marketplace_type_repository,
			new SapphireOpenStackApiVersionRepository,
			new SapphireOpenStackComponentRepository,
			new SapphireOpenStackReleaseRepository,
			new SapphireRegionRepository,
			new SapphireSupportChannelTypeRepository,
			$this->languages_repository,
			new SapphireConfigurationManagementTypeRepository,
			new SapphireConsultantServiceOfferedTypeRepository,
			new ConsultantAddPolicy($this->consultant_repository, $this->marketplace_type_repository),
			new CompanyServiceCanAddResourcePolicy,
			new CompanyServiceCanAddVideoPolicy,
			new ConsultantFactory,
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

		parent::__construct($manager,new ConsultantFactory);

		// filters ...
		$this_var     = $this;
		$current_user = $this->current_user;
		$repository   = $this->consultant_repository;

		$this->addBeforeFilter('addCompanyService','check_add_company',function ($request) use($this_var, $current_user, $repository){
			$data = $this_var->getJsonRequest();
			if (!$data) return $this->serverError();
			$company_id = intval(@$data['company_id']);
			if(!$current_user->isMarketPlaceAdminOfCompany(IConsultant::MarketPlaceGroupSlug, $company_id))
				return $this_var->permissionFailure();
		});

		$this->addBeforeFilter('updateCompanyService','check_update_company',function ($request) use($this_var, $current_user){
			$data = $this_var->getJsonRequest();
			if (!$data) return $this->serverError();
			if(!$current_user->isMarketPlaceAdminOfCompany(IConsultant::MarketPlaceGroupSlug,intval(@$data['company_id'])))
				return $this_var->permissionFailure();
		});

		$this->addBeforeFilter('deleteCompanyService','check_delete_company',function ($request) use($this_var, $current_user,$repository){
			$company_service_id = intval($request->param('COMPANY_SERVICE_ID'));
			$company_service    = $repository->getById($company_service_id);
			if(!$current_user->isMarketPlaceAdminOfCompany(IConsultant::MarketPlaceGroupSlug, $company_service->getCompany()->getIdentifier()))
				return $this_var->permissionFailure();
		});
	}

	public function getConsultant(){
		$company_service_id  = intval($this->request->param('COMPANY_SERVICE_ID'));
		$consultant = $this->consultant_repository->getById($company_service_id);
		if(!$consultant)
			return $this->notFound();
		return $this->ok(ConsultantAssembler::convertConsultantToArray($consultant));
	}

	public function getLanguages(){
		$term  = Convert::raw2sql ($this->request->getVar('term'));
		$query = new QueryObject;
		$query->addAddCondition(QueryCriteria::like('Name',$term));
		list($list, $size) = $this->languages_repository->getAll($query,0,20);
		$res = array();
		foreach($list as $lang){
			array_push($res,array('label' => $lang->getName(),'value' => $lang->getName()));
		}
		return $this->ok($res);
	}
}