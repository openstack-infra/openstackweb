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
abstract class Cloud_Controller extends AbstractController {
	/**
	 * @var array
	 */
	static $url_handlers = array(
		'POST search'   => 'search',
		'GET names'     => 'names',
	);

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'names',
		'search',
	);
	/**
	 * @var ICompanyServiceRepository
	 */
	protected $cloud_repository;

	/**
	 * @var CloudsNamesQueryHandler
	 */
	protected $clouds_names_query;

	function init()	{
		parent::init();
	}

	/**
	 * @return string
	 */
	abstract function getDirectoryPageClass();
	/**
	 * @param string $action
	 * @return string
	 */
	public function Link($action = null){
		$class = $this->getDirectoryPageClass();
		$page  = $class::get()->first();
		if(is_null($page)) return '';
		$controller = ModelAsController::controller_for($page);
		if(is_null($controller)) return '';
		return $controller->Link($action);
	}

	public function names(){
		$params = $this->request->getVars();
		$result = $this->clouds_names_query->handle(new OpenStackImplementationNamesQuerySpecification($params["term"]));
		$res    = array();
		foreach($result->getResult() as $dto){
			array_push($res,array('label' => $dto->getLabel(),'value' => $dto->getValue()));
		}
		return json_encode($res);
	}

	/**
	 * @return CloudService
	 */
	abstract function  getCloudTypeClass();

	public function search(){
		$output = '';
		if(!$this->isJson()){
			return $this->httpError(500,'Content Type not allowed');
		}
		try{
			$search_params = json_decode($this->request->getBody(),true);
			$query = new QueryObject($this->getCloudTypeClass());
			$query->addAlias(QueryAlias::create('Company'));
			$query->addAddCondition(QueryCriteria::equal("Active",true));

			$location = @explode(',',@$search_params['location_term']);
			$name     =  @$search_params['name_term'];
			$service  =  @$search_params['service_term'];

			if(!empty($name)){
				$query->addOrCompound(
					QueryCriteria::like('CompanyService.Name',$name),
					QueryCriteria::like('CompanyService.Overview',$name),
					QueryCriteria::like('Company.Name',$name) );
			}

			if(!empty($service)){
				$service = explode('-',$service);
				$query->addAlias(QueryAlias::create('CloudServiceOffered')->addAlias(QueryAlias::create('OpenStackReleaseSupportedApiVersion')->addAlias(QueryAlias::create('OpenStackComponent'))));
				$query->addOrCompound(QueryCriteria::like('OpenStackComponent.Name',trim($service[0])),QueryCriteria::like('OpenStackComponent.CodeName',trim($service[1])));
			}
			$query->addAlias(QueryAlias::create('DataCenterLocation'));
			if(is_array($location) && !empty($location[0]))
				$query->addAddCondition(QueryCriteria::like("DataCenterLocation.City",trim($location[0])));

			$countries = array_flip(Geoip::getCountryDropDown());
			if(is_array($location) && count($location)==2 ){
				$country = trim($location[1]);
				if(!empty($country) && array_key_exists($country, $countries))
					$query->addAddCondition(QueryCriteria::like("DataCenterLocation.Country",$countries[$country]));
			}
			else if(is_array($location) && count($location)==3) {
				$state   = trim($location[1]);
				$country = trim($location[2]);
				if(!empty($country) && array_key_exists($country, $countries))
					$query->addAddCondition(QueryCriteria::like("DataCenterLocation.Country", $countries[$country]));
				if(!empty($state))
					$query->addAddCondition(QueryCriteria::like("DataCenterLocation.State", $state));
			}


			list($list,$size) = $this->cloud_repository->getAll($query,0,1000);

			foreach ($list as $public_cloud) {
				$output .= $public_cloud->renderWith('CloudsDirectoryPage_CloudBox', array('CloudLink'=>$this->Link()));
			}
		}
		catch(Exception $ex){
			return $this->httpError(500,'Server Error');
		}
		return empty($output) ? $this->httpError(404,'') : $output;
	}
}
