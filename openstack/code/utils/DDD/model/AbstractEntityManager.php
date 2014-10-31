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
 * Class AbstractEntityManager
 */
abstract class AbstractEntityManager {

	/**
	 * @var IEntityRepository
	 */
	protected  $repository;


	/**
	 * @var ITransactionManager
	 */
	protected $tx_manager;


	protected $entity_class;

	protected $allowed_update_params;

	public function __construct(
		$entity_class,
		array $allowed_update_params,
		IEntityRepository $repository,
		ITransactionManager $tx_manager){
		$this->entity_class          = $entity_class;
		$this->repository            = $repository;
		$this->tx_manager            = $tx_manager;
		$this->allowed_update_params = $allowed_update_params;
	}

	public function getById($id){
		$query = new QueryObject;
		$query->addAddCondition(QueryCriteria::equal('ID',(int)$id));
		return $this->repository->get($query);
	}

	/**
	 * @param QueryObject $query
	 * @param int         $offset
	 * @param int         $limit
	 * @return array
	 */
	public function getAll(QueryObject $query, $offset=1, $limit = 10){
		return $this->repository->getAll( $query, $offset , $limit);
	}

	/**
	 * @param  int $id
	 * @return bool
	 * @throws NotFoundEntityException
	 */
	public function delete($id){
		$repository   = $this->repository;
		$this_var     = $this;
		$entity_class = $this->entity_class;
		$this->tx_manager->transaction(function() use($entity_class,$repository,$this_var,$id) {
			$entity = $this_var->getById($id);
			if(!$entity)
				throw new NotFoundEntityException($entity_class,sprintf('id %s',$id));
			$repository->delete($entity);
		});
		return true;
	}

	/**
	 * @param int   $id
	 * @param array $params
	 * @return bool
	 * @throws NotFoundEntityException
	 */
	public function update($id, array $params) {
		$res          = false;
		$this_var     = $this;
		$repository   = $this->repository;
		$entity_class = $this->entity_class;
		$allowed_update_params = $this->allowed_update_params;
		$check_duplicated_entity_criteria = $this->checkDuplicatedEntityCriteria;
		$custom_update_logic = $this->customUpdateLogic;
		$this->tx_manager->transaction(function () use (&$res,
			$entity_class,
			$allowed_update_params,
			$id,
			$params,
			$repository,
			$this_var,
			$check_duplicated_entity_criteria,
			$custom_update_logic) {

			$entity  = $this_var->getById($id);
			if(!$entity)
				throw new NotFoundEntityException($entity_class,sprintf('id %s',$id));

			$check_duplicated_entity_criteria($id,$params);

			foreach($allowed_update_params as $param){
				if(array_key_exists($param,$params)){

					$setter = sprintf('set%s',ucwords(str_replace('_','',$param)));
					call_user_func(array($entity,$setter), $params[$param]);
				}
			}

			$custom_update_logic($entity,$params);
		});
		return $res;
	}

	/**
	 * @param IEntity $entity
	 * @param array   $params
	 * @return mixed
	 */
	abstract protected function customUpdateLogic(IEntity $entity, array $params);

	/**
	 * @param int   $id
	 * @param array $params
	 * @return mixed
	 */
	abstract protected function checkDuplicatedEntityCriteria($id,array $params);
}