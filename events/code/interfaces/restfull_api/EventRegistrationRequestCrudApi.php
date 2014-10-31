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
 * Class EventRegistrationRequestCrudApi
 */
final class EventRegistrationRequestCrudApi
	extends AbstractRestfulJsonApi {

	const ApiPrefix = 'api/v1/event-registration-requests';

	protected function isApiCall(){
		$request = $this->getRequest();
		if(is_null($request)) return false;
		return  strpos(strtolower($request->getURL()),self::ApiPrefix) !== false;
	}

	/**
	 * @var EventRegistrationRequestManager
	 */
	private $event_registration_request_manager;
	/**
	 * @var IEventRegistrationRequestRepository
	 */
	private $repository;

	public function __construct(){
		parent::__construct();
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
		$this->repository = new SapphireEventRegistrationRequestRepository;
		$this->event_registration_request_manager = new EventRegistrationRequestManager(
			$this->repository,
			new SapphireEventRepository,
			new EventRegistrationRequestFactory,
			new GoogleGeoCodingService(
				new SapphireGeoCodingQueryRepository,
				new UtilFactory,
				SapphireTransactionManager::getInstance(),
				$google_geo_coding_api_key,
				$google_geo_coding_client_id,
				$google_geo_coding_private_key),
			new SapphireEventPublishingService,
			new EventValidatorFactory,
			SapphireTransactionManager::getInstance()
		);
	}

	/**
	 * @return bool
	 */
	protected function authorize()
	{
		return Permission::check("SANGRIA_ACCESS");
	}

	/**
	 * @var array
	 */
	static $url_handlers = array(
		'PUT $REQUEST_ID/posted'   => 'postEventRegistrationRequest',
		'PUT $REQUEST_ID/rejected' => 'rejectEventRegistrationRequest',
		'GET $REQUEST_ID'          => 'getEventRegistrationRequest',
		'POST '                    => 'createEventRegistrationRequest',
		'PUT '                     => 'updateEventRegistrationRequest',
	);

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'postEventRegistrationRequest',
		'getEventRegistrationRequest',
		'rejectEventRegistrationRequest',
		'updateEventRegistrationRequest',
		'createEventRegistrationRequest',
	);

	public function postEventRegistrationRequest(){
		$request_id = (int)$this->request->param('REQUEST_ID');
		try{
			$this->event_registration_request_manager->postEventRegistrationRequest($request_id, Director::absoluteURL('community/events/'));
			return $this->ok();
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

	public function getEventRegistrationRequest(){
		$request_id = (int)$this->request->param('REQUEST_ID');
		try{
			$request = $this->repository->getById($request_id);
			if(!$request) throw new NotFoundEntityException('EventRegistrationRequest',sprintf('id %s',$request_id));
			return $this->ok(EventsAssembler::convertEventRegistrationRequestToArray($request));
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

	public function updateEventRegistrationRequest(){
		try{
			$data = $this->getJsonRequest();
			if (!$data) return $this->serverError();
			$this->event_registration_request_manager->updateEventRegistrationRequest($data);
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

	public function rejectEventRegistrationRequest(){
		try{
			$data = $this->getJsonRequest();
			if (!$data) return $this->serverError();
			$request_id = (int)$this->request->param('REQUEST_ID');
			$this->event_registration_request_manager->rejectEventRegistration($request_id, $data, Director::absoluteURL('community/events/'));
			return $this->updated();
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

	public function createEventRegistrationRequest(){
		$data = $this->getJsonRequest();
		if (!$data) return $this->serverError();
		return $this->created(0);
	}
}