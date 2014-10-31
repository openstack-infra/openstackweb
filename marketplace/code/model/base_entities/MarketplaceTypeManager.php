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
 * Class MarketplaceTypeManager
 */
final class MarketplaceTypeManager  extends AbstractEntityManager {

	/**
	 * @var ISecurityGroupRepository
	 */
	private $group_repository;
	/**
	 * @param IMarketplaceTypeRepository $repository
	 * @param ISecurityGroupRepository   $group_repository
	 * @param ITransactionManager        $tx_manager
	 */
	public function __construct(IMarketplaceTypeRepository $repository,
	                            ISecurityGroupRepository $group_repository,
								ITransactionManager $tx_manager){

		parent::__construct('MarketPlaceType',
			array('name','active'),
			$repository,
			$tx_manager);

		$this->group_repository = $group_repository;
	}

	/**
	 * @param IMarketPlaceType $marketplace_type
	 * @return int|bool
	 */
	public function store(IMarketPlaceType $marketplace_type){
		$repository                  = $this->repository;
		$group_repository            = $this->group_repository;
		$res                         = false;
		$this->tx_manager->transaction(function() use(&$res,&$marketplace_type,$repository,$group_repository){

			$query = new QueryObject;
			$query->addAddCondition(QueryCriteria::equal('Name',$marketplace_type->getName()));
			$query->addAddCondition(QueryCriteria::equal('Slug',$marketplace_type->getSlug()));
			$query->addAddCondition(QueryCriteria::notEqual('ID',$marketplace_type->getIdentifier()));

			$old = $repository->getBy($query);

			if($old){
				throw new EntityAlreadyExistsException('MarketPlaceType',sprintf('Name  %s',$marketplace_type->getName()));
			}

			$repository->add($marketplace_type);
		});
		//reload from db...
		$id = $marketplace_type->getIdentifier();
		$marketplace_type = $this->repository->getById($id);
		$g   = $marketplace_type->getAdminGroup();
		$permission_code = sprintf('MANAGE_MARKETPLACE_%s',str_replace(' ', '_', strtoupper($marketplace_type->getName())));
		$groups = Permission::get_groups_by_permission($permission_code);
		if(count($groups)==0)
			Permission::grant($g->getIdentifier(),$permission_code);

		return $res;
	}


	/**
	 * @param IEntity $entity
	 * @param array   $params
	 * @return mixed|void
	 */
	protected function customUpdateLogic(IEntity $entity, array $params)
	{
		// TODO: Implement customUpdateLogic() method.
	}

	/**
	 * @param int   $id
	 * @param array $params
	 * @return void
	 * @throws EntityAlreadyExistsException
	 */
	protected function checkDuplicatedEntityCriteria($id, array $params)
	{
		if(@$params['name']){
			$query = new QueryObject;
			$query->addAddCondition(QueryCriteria::equal('Name',$params['name']));
			$query->addAddCondition(QueryCriteria::notEqual('ID',$id));
			$old_one = $this->repository->get($query);
			if($old_one)
				throw new EntityAlreadyExistsException($this->entity_class, sprintf('%s  %s','name',  $params['name']));
		}
	}
}