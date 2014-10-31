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
/***
 * Class CCLACompanyService
 */
final class CCLACompanyService {

	/**
	 * @var ICLACompanyRepository
	 */
	private $company_repository;

	/**
	 * @var ITransactionManager
	 */
	private $tx_manager;

	public function __construct(ICLACompanyRepository $company_repository, ITransactionManager $tx_manager){
		$this->company_repository = $company_repository;
		$this->tx_manager         = $tx_manager;
	}

	/**
	 * @param int $company_id
	 * @return DateTime
	 */
	public function signCCLA($company_id){

		$company_repository = $this->company_repository;

		return $this->tx_manager->transaction(function() use($company_id, $company_repository){
			$company = $company_repository->getById($company_id);

			if(!$company)
				throw new NotFoundEntityException('Company',sprintf(' id %s',$company_id));

			if(!$company->isICLASigned())
				$company->signICLA();

			return $company->ICLASignedDate();
		});
	}

	/**
	 * @param int $company_id
	 * @return void
	 */
	public function unsignCCLA($company_id){

		$company_repository = $this->company_repository;

		return $this->tx_manager->transaction(function() use($company_id, $company_repository){
			$company = $company_repository->getById($company_id);

			if(!$company)
				throw new NotFoundEntityException('Company',sprintf(' id %s',$company_id));

			if($company->isICLASigned())
				$company->unsignICLA();

		});
	}
} 