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
 * Class SapphireRepository
 */
class SapphireRepository extends AbstractEntityRepository
{

	/**
	 * @param IEntity $entity
	 */
	public function __construct(IEntity $entity)
	{
		parent::__construct($entity);
	}

	/**
	 * @param IEntity $entity
	 * @return int|void
	 */
	public function add(IEntity $entity)
	{
		UnitOfWork::getInstance()->scheduleForInsert($entity);
	}

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function delete(IEntity $entity)
	{
		UnitOfWork::getInstance()->scheduleForDelete($entity);
	}

	/**
	 * @param int $id
	 * @return IEntity
	 */
	public function getById($id)
	{

		$class = $this->entity_class;
		$entity = $class::get()->byId($id);
		if ($entity) {
			UnitOfWork::getInstance()->setToCache($entity);
			UnitOfWork::getInstance()->scheduleForUpdate($entity);
		}
		return $entity;
	}

	public function getAll(QueryObject $query, $offset = 0, $limit = 10)
	{
		$filter = (string)$query;
		$class = $this->entity_class;
		$do = $class::get()->where($filter);

		if (count($query->getAlias()))
			$do = $do->innerJoin($query->getAlias());
		if (count($query->getOrder()))
			$do = $do->sort($query->getOrder());

		$do = $do->limit($limit, $offset);

		if (is_null($do)) return array(array(), 0);
		$res = $do->toArray();
		foreach ($res as $entity) {
			UnitOfWork::getInstance()->setToCache($entity);
			UnitOfWork::getInstance()->scheduleForUpdate($entity);
		}
		return array($res, (int)$do->count());
	}

	public function getBy(QueryObject $query)
	{
		$filter = (string)$query;
		$class = $this->entity_class;
		$do = $class::get()->where($filter);
		if (count($query->getAlias()))
			$do = $do->innerJoin($query->getAlias());
		if (count($query->getOrder()))
			$do = $do->sort($query->getOrder());
		if (is_null($do)) return false;
		$entity = $do->first();
		if ($entity) {
			UnitOfWork::getInstance()->setToCache($entity);
			UnitOfWork::getInstance()->scheduleForUpdate($entity);
		}
		return $entity;
	}
}