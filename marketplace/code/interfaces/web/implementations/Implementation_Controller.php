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
 * Class Implementation_Controller
 */
class Implementation_Controller extends AbstractController {

	/**
	 * @var IOpenStackImplementationNamesQueryHandler
	 */
	private $implementations_names_query;
	/**
	 * @var ICompanyServiceRepository
	 */
	private $distribution_repository;

	/**
	 * @var ICompanyServiceRepository
	 */
	private $appliance_repository;

	private $implementations_services_query;
	/**
	 * @var array
	 */
	static $url_handlers = array(
		'POST search'  => 'search',
		'GET names'    => 'names',
	);

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'search',
		'names',
	);

	function init()	{
		parent::init();
		$this->implementations_names_query = new OpenStackImplementationNamesQueryHandler;
		$this->distribution_repository     = new SapphireDistributionRepository;
		$this->appliance_repository        = new SapphireApplianceRepository;
	}

	function names(){
		$params = $this->request->getVars();
		$result = $this->implementations_names_query->handle(new OpenStackImplementationNamesQuerySpecification($params["term"]));
		$res    = array();
		foreach($result->getResult() as $dto){
			array_push($res,array('label' => $dto->getLabel(),'value' => $dto->getValue()));
		}
		return json_encode($res);
	}

	/**
	 * @param string $action
	 * @return string
	 */
	public function Link($action = null){
		$page       = DistributionsDirectoryPage::get()->first();
		if(is_null($page)) return '';
		$controller = ModelAsController::controller_for($page);
		if(is_null($controller)) return '';
		return $controller->Link($action);
	}

	function search(){
		$output = '';
		if(!$this->isJson()){
			return $this->httpError(500,'Content Type not allowed');
		}
		try{
			$search_params = json_decode($this->request->getBody(),true);
			$query1 = new QueryObject(new Distribution);
			$query2 = new QueryObject(new Appliance);
			$query1->addAlias(QueryAlias::create('Company'));
			$query2->addAlias(QueryAlias::create('Company'));
			$name     =  @$search_params['name_term'];
			$service  =  @$search_params['service_term'];

			if(!empty($name)){
				$query1->addOrCompound(
					QueryCriteria::like('CompanyService.Name',$name),
					QueryCriteria::like('CompanyService.Overview',$name),
					QueryCriteria::like('Company.Name',$name) );
				$query2->addOrCompound(
					QueryCriteria::like('CompanyService.Name',$name),
					QueryCriteria::like('CompanyService.Overview',$name),
					QueryCriteria::like('Company.Name',$name) );
			}

			if(!empty($service)){
				$service = explode('-',$service);
				$query1->addAlias(QueryAlias::create('OpenStackImplementationApiCoverage')->addAlias(QueryAlias::create('OpenStackReleaseSupportedApiVersion')->addAlias(QueryAlias::create('OpenStackComponent'))));
				$query1->addOrCompound(QueryCriteria::like('OpenStackComponent.Name',trim($service[0])),QueryCriteria::like('OpenStackComponent.CodeName',trim($service[1])));

				$query2->addAlias(QueryAlias::create('OpenStackImplementationApiCoverage')->addAlias(QueryAlias::create('OpenStackReleaseSupportedApiVersion')->addAlias(QueryAlias::create('OpenStackComponent'))));
				$query2->addOrCompound(QueryCriteria::like('OpenStackComponent.Name',trim($service[0])),QueryCriteria::like('OpenStackComponent.CodeName',trim($service[1])));
			}

			$query1->addAddCondition(QueryCriteria::equal("Active",true));
			$query2->addAddCondition(QueryCriteria::equal("Active",true));

			list($list1,$size1) = $this->distribution_repository->getAll($query1,0,1000);
			list($list2,$size2) = $this->appliance_repository->getAll($query2,0,1000);
			$implementations = array_merge($list1,$list2);
			foreach ($implementations as $implementation) {
				$type = $implementation->getMarketPlace()->getName()==IDistribution::MarketPlaceType?'distribution':'appliance';
				$output .= $implementation->renderWith('DistributionsDirectoryPage_ImplementationBox',array(
					'DistroLink' =>     $this->Link("distribution"),
					'$ApplianceLink' => $this->Link("appliance")));
			}
		}
		catch(Exception $ex){
			return $this->httpError(500,'Server Error');
		}
		return empty($output) ? $this->httpError(404,'') : $output;
	}
} 