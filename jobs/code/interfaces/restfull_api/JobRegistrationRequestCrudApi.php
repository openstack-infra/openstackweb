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
 * Class JobRegistrationRequestCrudApi
 */
final class JobRegistrationRequestCrudApi
extends AbstractRestfulJsonApi {

	const ApiPrefix = 'api/v1/job-registration-requests';

	protected function isApiCall(){
		$request = $this->getRequest();
		if(is_null($request)) return false;
		return  strpos(strtolower($request->getURL()),self::ApiPrefix) !== false;
	}

	protected function authenticate() {
		//we dont need authentication
		return true;
	}

	/**
	 * @return bool
	 */
	protected function authorize(){
		return true;
	}

	/**
	 * @var JobRegistrationRequestManager
	 */
	private $manager;

	/**
	 * @var IJobRegistrationRequestRepository
	 */
	private $repository;

	/**
	 * @var IQueryHandler
	 */
	private $companies_names_query;

	/// FILTERS
	/**
	 * @param $request
	 * @return SS_HTTPResponse
	 */
	public function checkJobTasksAuthentication($request){
		$auth_response = $this->isHttpBasicAuthPresent();
		if(!$auth_response)
			return $this->unauthorizedHttpBasicAuth('Restricted area');

		list($user,$password) = $auth_response;
		if($user != JOB_TASKS_USER || $password != JOB_TASKS_PASS){
			return $this->unauthorizedHttpBasicAuth('Restricted area');
		}
	}

	/**
	 * @param $request
	 * @return SS_HTTPResponse
	 */
	public function checkSangriaAccess($request){
		if(!Permission::check("SANGRIA_ACCESS"))
			return $this->permissionFailure();
	}

	public function __construct(){
		parent::__construct();
		$this->companies_names_query = new CompaniesNamesQueryHandler;
		$this->repository            = new SapphireJobRegistrationRequestRepository;
		$this->manager               = new JobRegistrationRequestManager(
			$this->repository,
			new SapphireJobRepository,
			new SapphireJobAlertEmailRepository,
			new JobFactory,
			new JobsValidationFactory,
			new SapphireJobPublishingService,
			SapphireTransactionManager::getInstance()
		);

		//filters
		$this_var = $this;
		$this->addBeforeFilter('postJobRegistrationRequest','check_access_post',function ($request) use($this_var){
			return $this_var->checkSangriaAccess($request);
		});
		$this->addBeforeFilter('rejectJobRegistrationRequest','check_access_reject',function ($request) use($this_var){
			return $this_var->checkSangriaAccess($request);
		});
		$this->addBeforeFilter('getJobRegistrationRequest','check_access_get',function ($request) use($this_var){
			return $this_var->checkSangriaAccess($request);
		});
		$this->addBeforeFilter('updateJobRegistrationRequest','check_access_update',function ($request) use($this_var){
			return $this_var->checkSangriaAccess($request);
		});
	}

	/**
	 * @var array
	 */
	static $url_handlers = array(
		'GET companies'            => 'companies',
		'PUT $REQUEST_ID/posted'   => 'postJobRegistrationRequest',
		'PUT $REQUEST_ID/rejected' => 'rejectJobRegistrationRequest',
		'GET $REQUEST_ID'          => 'getJobRegistrationRequest',
		'POST '                    => 'createJobRegistrationRequest',
		'PUT '                     => 'updateJobRegistrationRequest',
	);

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'postJobRegistrationRequest',
		'rejectJobRegistrationRequest',
		'getJobRegistrationRequest',
		'createJobRegistrationRequest',
		'updateJobRegistrationRequest',
		'companies',
	);

	/**
	 * @return SS_HTTPResponse
	 */
	public function createJobRegistrationRequest(){
		$data = $this->getJsonRequest();
		if (!$data) return $this->serverError();
		try{
			$entity = $this->manager->registerJobRegistrationRequest($data);
			return $this->created($entity->getIdentifier());
		}
		catch (EntityValidationException $ex2) {
			SS_Log::log($ex2,SS_Log::ERR);
			return $this->validationError($ex2->getMessages());
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	/**
	 * @return SS_HTTPResponse
	 */
	public function getJobRegistrationRequest(){
		$request_id = (int)$this->request->param('REQUEST_ID');
		try{
			$request = $this->repository->getById($request_id);
			if(!$request) throw new NotFoundEntityException('JobRegistrationRequest',sprintf('id %s',$request_id));
			return $this->ok(JobsAssembler::convertJobRegistrationRequestToArray($request));
		}
		catch(NotFoundEntityException $ex1){
			SS_Log::log($ex1,SS_Log::ERR);
			return $this->notFound($ex1->getMessage());
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	/**
	 * @return SS_HTTPResponse
	 */
	public function updateJobRegistrationRequest(){
		try{
			$data = $this->getJsonRequest();
			if (!$data) return $this->serverError();
			$this->manager->updateJobRegistrationRequest($data);
			return $this->updated();
		}
		catch(NotFoundEntityException $ex1){
			SS_Log::log($ex1,SS_Log::ERR);
			return $this->notFound($ex1->getMessage());
		}
		catch (EntityValidationException $ex2) {
			SS_Log::log($ex2,SS_Log::ERR);
			return $this->validationError($ex2->getMessages());
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	/**
	 * @return SS_HTTPResponse
	 */
	public function postJobRegistrationRequest(){
		$request_id = (int)$this->request->param('REQUEST_ID');
		try{
			$this->manager->postJobRegistrationRequest($request_id, Director::absoluteURL('community/jobs/'));
			return $this->ok();
		}
		catch(NotFoundEntityException $ex1){
			SS_Log::log($ex1,SS_Log::ERR);
			return $this->notFound($ex1->getMessage());
		}
		catch(EntityValidationException $ex2){
			SS_Log::log($ex2,SS_Log::NOTICE);
			return $this->validationError($ex2->getMessages());
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	/**
	 * @return SS_HTTPResponse
	 */
	public function rejectJobRegistrationRequest(){
		try{
			$data = $this->getJsonRequest();
			if (!$data) return $this->serverError();
			$request_id = (int)$this->request->param('REQUEST_ID');
			$this->manager->rejectJobRegistration($request_id, $data, Director::absoluteURL('community/jobs/'));
			return $this->updated();
		}
		catch(NotFoundEntityException $ex1){
			SS_Log::log($ex1,SS_Log::ERR);
			return $this->notFound($ex1->getMessage());
		}
		catch(EntityValidationException $ex2){
			SS_Log::log($ex2,SS_Log::NOTICE);
			return $this->validationError($ex2->getMessages());
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	/**
	 * @return string
	 */
	public function companies(){
		$params = $this->request->getVars();
		$result = $this->companies_names_query->handle(new OpenStackImplementationNamesQuerySpecification($params["term"]));
		$res    = array();
		foreach($result->getResult() as $dto){
			array_push($res,array('label' => $dto->getLabel(),'value' => $dto->getValue()));
		}
		return json_encode($res);
	}
}