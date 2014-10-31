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
 * Class EventAlertEmailManager
 */
final class EventAlertEmailManager {
	/**
	 * @var IEntityRepository
	 */
	private $repository;
	/**
	 * @var IEventAlertEmailRepository
	 */
	private $email_repository;
	/**
	 * @var IEventRegistrationRequestFactory
	 */
	private $factory;

	/**
	 * @var ITransactionManager
	 */
	private $tx_manager;

	public function __construct(IEntityRepository $repository,
	                            IEventAlertEmailRepository $email_repository,
	                            IEventRegistrationRequestFactory $factory,
								ITransactionManager $tx_manager) {
		$this->repository       = $repository;
		$this->email_repository = $email_repository;
		$this->factory          = $factory;
		$this->tx_manager       = $tx_manager;
	}

	/**
	 * @param int $batch_size
	 * @param string $email_alert_to
	 * @param string $details_url
	 */
	public function makeDigest($batch_size=15, $email_alert_to, $details_url){
		$email_repository = $this->email_repository;
		$repository       = $this->repository;
		$factory          = $this->factory;
		$this->tx_manager->transaction(function() use($batch_size, $email_alert_to, $details_url, $repository , $email_repository, $factory){
			$last_email =  $email_repository->getLastOne();
			$query      = new QueryObject();
			$query->addAddCondition(QueryCriteria::equal('isPosted', 0));
			$query->addAddCondition(QueryCriteria::equal('isRejected', 0));
			if($last_email){
				$query->addAddCondition(QueryCriteria::greater('ID',$last_email->getLastEventRegistrationRequest()->getIdentifier() ));
			}
			$query->addOrder(QueryOrder::asc('PostDate'));
			list($list,$size) = $repository->getAll($query,0,$batch_size);
			if($list && count($list)>0) {
				$last_one = end($list);
				reset($list);
				$email = EmailFactory::getInstance()->buildEmail(EVENT_REGISTRATION_REQUEST_EMAIL_FROM, $email_alert_to, "New Event Registration Requests");
				$email->setTemplate('EventAlertEmail');
				$email->populateTemplate(array(
					'RegistrationRequests' => new ArrayList($list),
					'Details'              => $details_url,
				));
				$email->send();
				$email_repository->add($factory->buildEventAlertEmail($last_one));
			}
		});
	}
} 