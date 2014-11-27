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
 * Class EventRegistrationRequestManager
 */
final class EventRegistrationRequestManager {

	/**
	 * @var IEntityRepository
	 */
	private $event_registration_request_repository;
	/**
	 * @var ITransactionManager
	 */
	private $tx_manager;
	/**
	 * @var IEventRegistrationRequestFactory
	 */
	private $factory;
	/**
	 * @var IGeoCodingService
	 */
	private $geo_coding_service;

	/**
	 * @var IEntityRepository
	 */
	private $event_repository;
	/**
	 * @var IEventPublishingService
	 */
	private $event_publishing_service;

	/**
	 * @var IEventValidatorFactory
	 */
	private $validator_factory;

	/**
	 * @param IEntityRepository                $event_registration_request_repository
	 * @param IEntityRepository                $event_repository
	 * @param IEventRegistrationRequestFactory $factory
	 * @param IGeoCodingService                $geo_coding_service
	 * @param IEventPublishingService          $event_publishing_service
	 * @param IEventValidatorFactory           $validator_factory
	 * @param ITransactionManager              $tx_manager
	 */
	public function __construct(IEntityRepository $event_registration_request_repository,
	                            IEntityRepository $event_repository,
	                            IEventRegistrationRequestFactory $factory,
								IGeoCodingService $geo_coding_service,
								IEventPublishingService $event_publishing_service,
								IEventValidatorFactory $validator_factory,
	                            ITransactionManager $tx_manager){
		$this->event_registration_request_repository = $event_registration_request_repository;
		$this->event_repository                      = $event_repository;
		$this->tx_manager                            = $tx_manager;
		$this->factory                               = $factory;
		$this->geo_coding_service                    = $geo_coding_service;
		$this->event_publishing_service              = $event_publishing_service;
		$this->validator_factory                     = $validator_factory;
	}

	/**
	 * @param array $data
	 * @return IEventRegistrationRequest
	 */
	public function registerEventRegistrationRequest(array $data){
		$repository         = $this->event_registration_request_repository;
		$factory            = $this->factory;
		$geo_coding_service = $this->geo_coding_service;
		$validator_factory  = $this->validator_factory;

		return $this->tx_manager->transaction(function() use ($data, $geo_coding_service, $factory, $repository, $validator_factory){
			$validator = $validator_factory->buildValidatorForEventRegistration($data);
			if ($validator->fails()) {
				throw new EntityValidationException($validator->messages());
			}

			$info               = $factory->buildEventMainInfo($data);
			$point_of_contact   = $factory->buildPointOfContact($data);
			$location           = $factory->buildEventLocation($data);
			$duration           = $factory->buildEventDuration($data);

			list($lat,$lng) = $geo_coding_service->getCityCoordinates($location->getCity(),$location->getCountry(),$location->getState());
			$location->setCoordinates($lat,$lng);
			$current_user             = Member::currentUser();
			$new_registration_request = $factory->buildEventRegistrationRequest($info,$point_of_contact, $location, $duration,null);
			if($current_user){
				$new_registration_request->registerUser($current_user);
			}
			$repository->add($new_registration_request);
		});
	}

	/**
	 * @param $id
	 * @param string $event_link
	 * @return IEvent
	 */
	public function postEventRegistrationRequest($id, $event_link){

		$repository               = $this->event_registration_request_repository;
		$factory                  = $this->factory;
		$event_repository         = $this->event_repository;
		$event_publishing_service = $this->event_publishing_service;

		$event =  $this->tx_manager->transaction(function() use ($id, $repository, $event_repository, $factory, $event_publishing_service, $event_link){
			$request = $repository->getById($id);
			if(!$request) throw new NotFoundEntityException('EventRegistrationRequest',sprintf('id %s',$id ));
			$event = $factory->buildEvent($request);
			$event_repository->add($event);
			$request->markAsPosted();

			//send Accepted message
			$point_of_contact = $request->getPointOfContact();
			$name_to  = $point_of_contact->getName();
			$email_to = $point_of_contact->getEmail();
			if(empty($name_to)  || empty($email_to ))
				throw new EntityValidationException(array(array('message'=>'invalid point of contact')));
			$email = EmailFactory::getInstance()->buildEmail(EVENT_REGISTRATION_REQUEST_EMAIL_FROM, $email_to, "Your OpenStack Event is Now Live");
			$email->setTemplate('EventRegistrationRequestAcceptedEmail');
			$email->populateTemplate(array(
				'EventLink' => $event_link,
			));
			$email->send();

			return $event;
		});

		$event_publishing_service->publish($event);
		return $event;
	}

	public function updateEventRegistrationRequest(array $data){

		$this_var           = $this;
		$validator_factory  = $this->validator_factory;
		$repository         = $this->event_registration_request_repository;
		$factory            = $this->factory;
		$geo_coding_service = $this->geo_coding_service;

		return  $this->tx_manager->transaction(function() use ($this_var,$factory, $validator_factory, $data, $repository, $geo_coding_service){
			$validator = $validator_factory->buildValidatorForEventRegistration($data);
			if ($validator->fails()) {
				throw new EntityValidationException($validator->messages());
			}
			$request = $repository->getById(intval($data['id']));
			if(!$request)
				throw new NotFoundEntityException('EventRegistrationRequest',sprintf('id %s',$data['id'] ));
			$location = $factory->buildEventLocation($data);
			list($lat,$lng) = $geo_coding_service->getCityCoordinates($location->getCity(),$location->getCountry(),$location->getState());
			$location->setCoordinates($lat,$lng);
			$request->registerMainInfo($factory->buildEventMainInfo($data));
			$request->registerLocation($location);
			$request->registerDuration($factory->buildEventDuration($data));
			$request->registerPointOfContact($factory->buildPointOfContact($data));
		});
	}

	/**
	 * @param int    $id
	 * @param array  $data
	 * @param string $event_link
	 * @return void
	 */
	public function rejectEventRegistration($id, array $data, $event_link){

		$this_var           = $this;
		$validator_factory  = $this->validator_factory;
		$repository         = $this->event_registration_request_repository;

		return  $this->tx_manager->transaction(function() use ($this_var, $id, $data, $validator_factory, $repository, $event_link){

			$validator = $validator_factory->buildValidatorForEventRejection($data);
			if ($validator->fails()) {
				throw new EntityValidationException($validator->messages());
			}

			$request = $repository->getById(intval($id));
			if(!$request)
				throw new NotFoundEntityException('EventRegistrationRequest',sprintf('id %s',$id ));

			$request->markAsRejected();

			if(@$data['send_rejection_email']){
				//send rejection message
				$point_of_contact = $request->getPointOfContact();
				$name_to  = $point_of_contact->getName();
				$email_to = $point_of_contact->getEmail();
				if(empty($name_to)  || empty($email_to ))
					throw new EntityValidationException(array(array('message'=>'invalid point of contact')));

				$email = EmailFactory::getInstance()->buildEmail(EVENT_REGISTRATION_REQUEST_EMAIL_FROM, $email_to, "Your Recent OpenStack Event Submission");
				$email->setTemplate('EventRegistrationRequestRejectedEmail');
				$email->populateTemplate(array(
					'EventLink'         => $event_link,
					'EventsEmailFrom'   => EVENT_REGISTRATION_REQUEST_EMAIL_FROM,
					'AdditionalComment' => @$data['custom_reject_message']
				));
				$email->send();
			}
		});
	}
} 