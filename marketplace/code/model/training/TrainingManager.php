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
 * Class TrainingManager
 */
final class TrainingManager extends CompanyServiceManager {


	/**
	 * @param ITrainingRepository                   $repository
	 * @param IMarketplaceTypeRepository            $marketplace_type_repository
	 * @param IMarketPlaceTypeAddPolicy             $add_policy
	 * @param IMarketPlaceTypeCanShowInstancePolicy $show_policy
	 * @param ICacheService                         $cache_service
	 * @param IMarketplaceFactory                   $marketplace_factory
	 * @param ITransactionManager                   $tx_manager
	 */
	public function __construct(ITrainingRepository                   $repository,
	                            IMarketplaceTypeRepository            $marketplace_type_repository,
	                            IMarketPlaceTypeAddPolicy             $add_policy,
	                            IMarketPlaceTypeCanShowInstancePolicy $show_policy,
	                            ICacheService                         $cache_service,
	                            IMarketplaceFactory                   $marketplace_factory,
	                            ITransactionManager                   $tx_manager){



		parent::__construct($repository,
						    null,
					        $marketplace_type_repository,
							$add_policy,
			                null,
			                null,
			                null,
			                $marketplace_factory,
			                null,
							$show_policy,
							$cache_service,
			                $tx_manager);
	}

	/**
	 * @param $training_id
	 * @param $date
	 * @return bool|ICourse[]
	 */
	public function getCoursesByDate($training_id , $date){
		$training = $this->repository->getById($training_id);
		if(!is_null($training))
			return $this->repository->getCoursesByDate($training,$date);
		return false;
	}

	public function register(ICompanyService  &$training){
		$training->setMarketplace($this->getMarketPlaceType());
		return parent::register($training);
	}

	/**
	 * @param $current_date
	 * @return null
	 */
	public function getActives($current_date=null){

		$trainings    = null;
		$ordering_set = false;

		if($order = $this->cache_service->getSingleValue("Trainings.Ordering")){
			$str_order       = implode(', ',$order);
			$trainings_count = $this->repository->countActives($current_date);
			if(intval($trainings_count)!= count($order)){
				//select random order
				$trainings = $this->repository->getActivesRandom($current_date);
			}
			else{
				$ordering_set = true;
				$trainings = $this->repository->getActivesByList($current_date, $str_order);
			}
		}
		else{
			$trainings = $this->repository->getActivesRandom($current_date);
		}

		if (!is_null( $trainings)) {

			$ordering  = array();
			$to_remove = array();
			foreach ($trainings as $t){
				if(!$this->show_policy->canShow($t->getIdentifier())){
					array_push($to_remove,$t);
				}
				array_push($ordering,$t->getIdentifier());
			}

			$trainings = array_diff($trainings,$to_remove);

			if(!$ordering_set)//store random order for next time to maintain consistency
			{
				$this->cache_service->setSingleValue("Trainings.Ordering",$ordering);
			}
		}
		return $trainings;
	}

	/**
	 * @param int $training_id
	 * @return bool
	 */
	public function isActive($training_id){
		$training = $this->repository->getById($training_id);
		if(!$training->isActive()) return false;
		if(!$this->show_policy->canShow($training->getIdentifier())) return false;
		return true;
	}

	/**
	 * @param ITrainingAdministrator $user
	 * @return ITraining[]
	 */
	public function getAllowedTrainings(ITrainingAdministrator $user){
		$res = array();
		//get all companies on where member is training admin
		$companies = $user->getAdministeredTrainingCompanies();
		foreach($companies as $company){
			$trainings = $company->getTrainings();
			foreach($trainings as $training){
				if($training->isActive())
					array_push($res,$training);
			}
		}
		//then on each company get all active trainings
		return $res;
	}

	/**
	 * @return IMarketPlaceType
	 * @throws NotFoundEntityException
	 */
	protected function getMarketPlaceType()
	{
		$marketplace_type =  $this->marketplace_type_repository->getByType(ITraining::MarketPlaceType);
		if(!$marketplace_type)
			throw new NotFoundEntityException('MarketPlaceType',sprintf("type %s ",ITraining::MarketPlaceType));
		return $marketplace_type;
	}
}