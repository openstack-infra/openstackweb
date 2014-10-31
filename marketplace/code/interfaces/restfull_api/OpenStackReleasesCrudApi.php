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
 * Class OpenStackReleasesCrudApi
 */
class OpenStackReleasesCrudApi extends  MarketPlaceRestfulApi {
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
		'PUT $RELEASE_ID!/components/$COMPONENT_ID/versions/$VERSION_ID!'    => 'addSupportedVersionToRelease',
		'DELETE $RELEASE_ID!/components/$COMPONENT_ID/versions/$VERSION_ID!' => 'deleteSupportedVersionFromRelease',
		'GET  $RELEASE_ID!/components/$COMPONENT_ID/versions'                => 'getVersionListByReleaseAndComponent',
		//supported components
		'DELETE $RELEASE_ID!/components/$COMPONENT_ID!' => 'deleteComponentFromRelease',
		'PUT $RELEASE_ID!/components/$COMPONENT_ID!'    => 'addComponentToRelease',
		'GET $RELEASE_ID!/components'                   => 'getComponentListByRelease',
		//releases
		'GET $RELEASE_ID!'     => 'getRelease',
		'DELETE $RELEASE_ID!'  => 'deleteRelease',
		'GET '                 => 'getReleaseList',
		'POST '                => 'addRelease',
		'PUT '                 => 'updateRelease',
	);

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'getRelease',
		'deleteRelease',
		'getReleaseList',
		'addRelease',
		'updateRelease',
		'addSupportedVersionToRelease',
		'deleteSupportedVersionFromRelease',
		'getVersionListByReleaseAndComponent',
		'deleteComponentFromRelease',
		'addComponentToRelease',
		'getComponentListByRelease'
	);


	public function getRelease(){
		$release_id    = (int)$this->request->param('RELEASE_ID');
		$res = $this->release_repository->getById($release_id);
		if(is_null($res))
			return $this->notFound();
		return $this->ok(self::convertReleaseToArray($res));
	}

	public function deleteRelease(){
		$release_id    = (int)$this->request->param('RELEASE_ID');
	}

	public function getReleaseList(){
		return $this->getAll(new QueryObject ,array($this->release_repository,'getAll'),array('self', 'convertReleaseToArray'));
	}

	public function addRelease(){
		$json = $this->getJsonRequest();
		if(!$json) return $this->serverError();

		$rules = array(
			'name'              => 'required|text',
			'release_number'    => 'required|text',
			'release_date'      => 'required|date',
			'release_notes_url' => 'required|url',
		);

		$messages = array(
			'name.required'                   => ':attribute is required',
			'name.text'                       => ':attribute should be valid text.',
			'release_number.required'         => ':attribute is required',
			'release_number.text'             => ':attribute should be valid text.',
			'release_date.required'           => ':attribute is required',
			'release_date.date'               => ':attribute should be valid date (y-m-d).',
			'release_notes_url.required'      => ':attribute is required',
			'release_notes_url.url'           => ':attribute should be valid url.',
		);

		$validator = ValidatorService::make($json,$rules,$messages);

		if($validator->fails()){
			return $this->validationError($validator->messages());
		}
		$date = new DateTime($json['release_date']);
		$id =  $this->manager->registerRelease(
			$this->factory->buildOpenStackRelease(
				$json['name'] , $json['release_number'],$date ,$json['release_notes_url']));
		return $this->created($id);
	}

	public function updateRelease(){

	}

	public static function convertReleaseToArray(IOpenStackRelease $release){
		$res                      = array();
		$res['id']                = $release->getIdentifier();
		$res['name']              = $release->getName();
		$res['release_number']    = $release->getReleaseNumber();
		$res['release_date']      = $release->getReleaseDate();
		$res['release_notes_url'] = $release->getReleaseNotesUrl();
		return $res;
	}

	public function addComponentToRelease(){
		try{
			$release_id   = intval($this->request->param('RELEASE_ID'));
			$component_id = intval($this->request->param('COMPONENT_ID'));
			$this->manager->registerComponentOnRelease($release_id,$component_id);
			return $this->ok();
		}
		catch(NotFoundEntityException $ex1){
			return $this->notFound($ex1->getMessage());
		}
		catch(Exception $ex){
			return $this->serverError();
		}
	}

	public function getComponentListByRelease(){
		try{
			$release_id   = intval($this->request->param('RELEASE_ID'));
			$release      = $this->release_repository->getById($release_id );
			if(!$release)
				throw new NotFoundEntityException('OpenStackRelease',sprintf('id %s',$release_id));
			$list         = array();
			foreach($release->getOpenStackComponents() as $component){
				array_push($list,OpenStackComponentsCrudApi::convertComponentToArray($component));
			}
			return $this->ok($list);
		}
		catch(NotFoundEntityException $ex1){
			return $this->notFound($ex1->getMessage());
		}
		catch(Exception $ex){
			return $this->serverError();
		}
	}

	public function getVersionListByReleaseAndComponent(){
		try{
			$release_id   = intval($this->request->param('RELEASE_ID'));
			$component_id = intval($this->request->param('COMPONENT_ID'));

			$list = array();
			foreach($this->manager->getReleaseSupportedVersionsByComponent($release_id,$component_id) as $version)
			{
				array_push($list,OpenStackComponentsCrudApi::convertApiVersionToArray($version));
			}
			return $this->ok($list);
		}
		catch(NotFoundEntityException $ex1){
			return $this->notFound($ex1->getMessage());
		}
		catch(InvalidAggregateRootException $ex2){
			return $this->validationError($ex2->getMessage());
		}
		catch(Exception $ex){
			return $this->serverError();
		}
	}

	public function addSupportedVersionToRelease(){
		try{
			$release_id   = intval($this->request->param('RELEASE_ID'));
			$component_id = intval($this->request->param('COMPONENT_ID'));
			$version_id   = intval($this->request->param('VERSION_ID'));

		}
		catch(NotFoundEntityException $ex1){
			return $this->notFound($ex1->getMessage());
		}
		catch(Exception $ex){
			return $this->serverError();
		}
	}
} 