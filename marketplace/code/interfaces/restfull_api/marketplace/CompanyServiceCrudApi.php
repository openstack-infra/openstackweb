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
 * Class CompanyServiceCrudApi
 */
abstract class CompanyServiceCrudApi
	extends MarketPlaceRestfulApi {

	/**
	 * @var array
	 */
	private static $allowed_actions = array(
		'deleteCompanyService',
		'addCompanyService',
		'updateCompanyService',
	);

	/**
	 * @var CompanyServiceManager
	 */
	protected $manager;

    /**
     * @var CompanyServiceManager
     */
    protected $draft_manager;

	/**
	 * @var ICompanyServiceFactory
	 */
	protected $factory;

    /**
     * @var ICompanyServiceFactory
     */
    protected $draft_factory;

	/**
	 * @param CompanyServiceManager  $manager
	 * @param ICompanyServiceFactory $factory
	 */
	public function __construct(CompanyServiceManager $manager, CompanyServiceManager $draft_manager, ICompanyServiceFactory $factory, ICompanyServiceFactory $draft_factory) {
		$this->manager = $manager;
        $this->draft_manager = $draft_manager;
		$this->factory = $factory;
        $this->draft_factory = $draft_factory;
		parent::__construct();
	}

	/**
	 * @return SS_HTTPResponse
	 */
	public function addCompanyService(){
		try {
			$data = $this->getJsonRequest();
			if (!$data) return $this->serverError();
			return $this->created($this->manager->addCompanyService($data)->getIdentifier());
		}
		catch (EntityAlreadyExistsException $ex1) {
			SS_Log::log($ex1,SS_Log::ERR);
			return $this->addingDuplicate($ex1->getMessage());
		}
		catch (PolicyException $ex2) {
			SS_Log::log($ex2,SS_Log::ERR);
			return $this->validationError($ex2->getMessage());
		}
		catch (EntityValidationException $ex3) {
			SS_Log::log($ex3,SS_Log::ERR);
			return $this->validationError($ex3->getMessages());
		}
	}

	/**
	 * @return SS_HTTPResponse
	 */
	public function updateCompanyService(){
		try {
			$data = $this->getJsonRequest();
			if (!$data) return $this->serverError();
			$this->manager->updateCompanyService($data);
			return $this->updated();
		}
		catch (EntityAlreadyExistsException $ex1) {
			SS_Log::log($ex1,SS_Log::ERR);
			return $this->addingDuplicate($ex1->getMessage());
		}
		catch (PolicyException $ex2) {
			SS_Log::log($ex2,SS_Log::ERR);
			return $this->validationError($ex2->getMessage());
		}
		catch (EntityValidationException $ex3) {
			SS_Log::log($ex3,SS_Log::ERR);
			return $this->validationError($ex3->getMessages());
		}
	}

	/**
	 * @return SS_HTTPResponse
	 */
	public function deleteCompanyService(){
		try {
			$company_service_id = intval($this->request->param('COMPANY_SERVICE_ID'));
			$this->manager->unRegister($this->factory->buildCompanyServiceById($company_service_id));
			return $this->deleted();
		}
		catch (NotFoundEntityException $ex1) {
			SS_Log::log($ex1,SS_Log::ERR);
			return $this->notFound($ex1->getMessage());
		}
		catch (Exception $ex) {
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

    /**
     * @return SS_HTTPResponse
     */
    public function addCompanyServiceDraft(){
        try {
            $data = $this->getJsonRequest();
            if (!$data) return $this->serverError();
            return $this->created($this->draft_manager->addCompanyService($data)->getIdentifier());
        }
        catch (EntityAlreadyExistsException $ex1) {
            SS_Log::log($ex1,SS_Log::ERR);
            return $this->addingDuplicate($ex1->getMessage());
        }
        catch (PolicyException $ex2) {
            SS_Log::log($ex2,SS_Log::ERR);
            return $this->validationError($ex2->getMessage());
        }
        catch (EntityValidationException $ex3) {
            SS_Log::log($ex3,SS_Log::ERR);
            return $this->validationError($ex3->getMessages());
        }
    }

    /**
     * @return SS_HTTPResponse
     */
    public function updateCompanyServiceDraft(){
        try {
            $data = $this->getJsonRequest();
            if (!$data) return $this->serverError();
            $this->draft_manager->updateCompanyService($data);
            return $this->updated();
        }
        catch (EntityAlreadyExistsException $ex1) {
            SS_Log::log($ex1,SS_Log::ERR);
            return $this->addingDuplicate($ex1->getMessage());
        }
        catch (PolicyException $ex2) {
            SS_Log::log($ex2,SS_Log::ERR);
            return $this->validationError($ex2->getMessage());
        }
        catch (EntityValidationException $ex3) {
            SS_Log::log($ex3,SS_Log::ERR);
            return $this->validationError($ex3->getMessages());
        }
    }

    /**
     * @return SS_HTTPResponse
     */
    public function publishCompanyService(){
        try {
            $company_service_live_id = intval($this->request->param('COMPANY_SERVICE_ID'));
            $data = $this->getJsonRequest();
            if (!$data) return $this->serverError();
            //save the live version
            if ($company_service_live_id == 0) { // this means is a draft without a live version yet
                $data['live_service_id'] = $this->manager->addCompanyService($data)->getIdentifier();
                //save the draft
                if ($data['id']) { //it could be that the draft was never saved yet, in that case id should be 0
                    $this->draft_manager->updateCompanyService($data);
                } else {
                    $this->draft_manager->addCompanyService($data);
                }

            } else { //if there is a live version of this draft, update it
                $this->draft_manager->updateCompanyService($data);
                $data['id'] = $data['live_service_id'];
                $this->manager->updateCompanyService($data);
            }

            return $this->published();
        }
        catch (EntityAlreadyExistsException $ex1) {
            SS_Log::log($ex1,SS_Log::ERR);
            return $this->addingDuplicate($ex1->getMessage());
        }
        catch (PolicyException $ex2) {
            SS_Log::log($ex2,SS_Log::ERR);
            return $this->validationError($ex2->getMessage());
        }
        catch (EntityValidationException $ex3) {
            SS_Log::log($ex3,SS_Log::ERR);
            return $this->validationError($ex3->getMessages());
        }
    }
}