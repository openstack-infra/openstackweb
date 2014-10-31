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
 * Class OpenStackComponentsCrudApi
 */
class OpenStackComponentsCrudApi extends  MarketPlaceRestfulApi {

	/**
	 * @var IOpenStackApiFactory
	 */
	private $factory;

	/**
	 * @var IEntityRepository
	 */
	private $release_repository;
	/**
	 * @var IOpenStackComponentRepository
	 */
	private $component_repository;
	/**
	 * @var IOpenStackApiVersionRepository
	 */
	private $version_repository;


	/**
	 * @var
	 */
	private $manager;

	public function __construct() {
		parent::__construct();
		$this->factory              = new OpenStackApiFactory;
		$this->release_repository   = new SapphireOpenStackReleaseRepository;
		$this->component_repository = new SapphireOpenStackComponentRepository;
		$this->version_repository   = new SapphireOpenStackApiVersionRepository;


		$this->manager    = new OpenStackApiManager(
								$this->release_repository,
								$this->component_repository,
								$this->version_repository,
								SapphireTransactionManager::getInstance());
	}
	/**
	 * @var array
	 */
	static $url_handlers = array(
		//versions
		'GET $COMPONENT_ID/versions/$VERSION_ID!'    => 'getVersion',
		'DELETE $COMPONENT_ID/versions/$VERSION_ID!' => 'deleteVersion',
		'GET $COMPONENT_ID/versions'                => 'getVersionList',
		'POST $COMPONENT_ID/versions'                => 'addVersion',
		'PUT  $COMPONENT_ID!/versions'                => 'updateVersion',
		//components
		'GET $COMPONENT_ID!'    => 'getComponent',
		'DELETE $COMPONENT_ID!' => 'deleteComponent',
		'GET '                  => 'getComponentList',
		'POST '                 => 'addComponent',
		'PUT '                  => 'updateComponent',
	);


	/**
	 * @var array
	 */
	static $allowed_actions = array(
		//component
		'getComponent',
		'deleteComponent',
		'getComponentList',
		'addComponent',
		'updateComponent',
		//versions
		'getVersion',
		'deleteVersion',
		'getVersionList',
		'addVersion',
		'updateVersion',
	);

	// components
	public function getComponent(){
		$component_id  = intval($this->request->param('COMPONENT_ID'));
		$res = $this->component_repository->getById($component_id);
		if(!$res)
			return $this->notFound();
		return $this->ok(self::convertComponentToArray($res));
	}


	public function getComponentList(){
		return $this->getAll(new QueryObject,array($this->component_repository,'getAll'),array('self', 'convertComponentToArray'));
	}

	public function addComponent(){
		try{

			$json = $this->getJsonRequest();
			if(!$json) return $this->serverError();

			$rules = array(
				'name'            => 'required|text',
				'code_name'       => 'required|text',
				'description'     => 'required|text',
			);

			$messages = array(
				'name.required'           => ':attribute is required',
				'name.text'               => ':attribute should be valid text.',
				'code_name.required'      => ':attribute is required',
				'code_name.text'          => ':attribute should be valid text.',
				'description.required'    => ':attribute is required',
				'description.text'        => ':attribute should be valid text.',
			);

			$validator = ValidatorService::make($json,$rules,$messages);

			if($validator->fails()){
				return $this->validationError($validator->messages());
			}
			return $this->created($this->manager->registerComponent($this->factory->buildOpenStackComponent($json['name'] , $json['code_name'], $json['description'])));
		}
		catch(EntityAlreadyExistsException $ex1){
			return $this->addingDuplicate($ex1->getMessage());
		}
		catch(Exception $ex){
			return $this->serverError();
		}
	}

	public function updateComponent(){

	}

	public function deleteComponent(){

	}

	public static function convertComponentToArray(IOpenStackComponent $component){
		$res                = array();
		$res['id']          = $component->getIdentifier();
		$res['name']        = $component->getName();
		$res['code_name']   = $component->getCodeName();
		$res['description'] = $component->getDescription();
		return $res;
	}
	//versions
	public function getVersion(){
		$component_id  = (int)$this->request->param('COMPONENT_ID');
		$version_id    = (int)$this->request->param('VERSION_ID');

		$version = $this->version_repository->getByIdAndComponent($version_id,$component_id);

		if(!$version)
			return $this->notFound();

		return $this->ok(self::convertApiVersionToArray($version));
	}

	public function deleteVersion(){

	}

	public function getVersionList(){
		$component_id  = (int)$this->request->param('COMPONENT_ID');
		$query = new QueryObject;
		//$query->addAddCondition(QueryCriteria::equal());
		$query->addAddCondition(QueryCriteria::equal('Component.ID',$component_id));
		return $this->getAll($query,array( $this->version_repository, 'getAll'),array('self', 'convertApiVersionToArray'));
	}

	public function addVersion(){
		try{
			$json   = $this->getJsonRequest();
			if(!$json) return $this->serverError();

			$component_id = (int)$this->request->param('COMPONENT_ID');

			$rules = array(
				'version'   => 'required|text',
				'status'    => 'required|versionstatus',
			);

			$messages = array(
				'version.required'  => ':attribute is required',
				'version.text'      => ':attribute should be valid text.',
				'status.required'  => ':attribute is required',
				'status.versionstatus'    => ':attribute should be valid api version status.',
			);

			$validator = ValidatorService::make($json,$rules,$messages);

			if($validator->fails()){
				return $this->validationError($validator->messages());
			}

			$component = $this->factory->buildOpenStackComponentById($component_id);
			$version   = $this->factory->buildOpenStackApiVersion($json['version'], $json['status'], $component);
			return $this->created($this->manager->registerVersion($version));
		}
		catch(EntityAlreadyExistsException $ex1){
			return $this->addingDuplicate($ex1->getMessage());
		}
		catch(NotFoundEntityException $ex2){
			return $this->notFound($ex2->getMessage());
		}
		catch(Exception $ex){
			return $this->serverError();
		}
	}

	public function updateVersion(){

	}

	public static function convertApiVersionToArray(IOpenStackApiVersion $version){
		$res                 = array();
		$res['id']           = $version->getIdentifier();
		$res['version']      = $version->getVersion();
		$res['status']       = $version->getStatus();
		$res['component_id'] = $version->getReleaseComponent()->getIdentifier();
		return $res;
	}

}