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
 * Class SapphireCompanyServiceRepository
 */
abstract class SapphireCompanyServiceRepository
	extends SapphireRepository
	implements ICompanyServiceRepository {

	/**
	 * @param IEntity $entity
	 */
	public function __construct(IEntity $entity){
		parent::__construct($entity);
	}

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function delete(IEntity $entity)
	{
		$entity->clearVideos();
		$entity->clearResources();
		parent::delete($entity);
	}

	/**
	 * @param int $company_id
	 * @return int
	 */
	public function countByCompany($company_id){
		$count = DB::query("SELECT COUNT(*) FROM CompanyService WHERE ClassName='{$this->entity_class}' AND CompanyID = {$company_id} ")->value();
		return intval($count);
	}

	/**
	 * @return int
	 */
	public function countActives()
	{
		return (int)DB::query("SELECT COUNT(*) FROM CompanyService WHERE ClassName = '{$this->entity_class}' AND Active = 1 ; ")->value();
	}

	/**
	 * @return ICompanyService[]
	 */
	public function getActivesRandom()
	{
		$class = $this->entity_class;
		$ds =  $class::get()->filter('Active',1)->sort('RAND()');
		return is_null($ds)?array():$ds->toArray();
	}

	/**
	 * @param string $list
	 * @return ICompanyService[]
	 */
	public function getActivesByList($list)	{
		$order = "'".implode( "' , '", explode(', ',$list)). "'";
		$class = $this->entity_class;
		$ds           = $class::get()->filter('Active',1)->where("ID IN ({$list})")->sort("FIELD(ID, {$order})");
		$res          = is_null($ds)?array():$ds->toArray();
		return $res;
	}
}