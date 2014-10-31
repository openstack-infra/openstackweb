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
/***
 * Class SapphireTeamRepository
 */
final class SapphireTeamRepository extends SapphireRepository
implements ITeamRepository
{
	public function __construct(){
		parent::__construct(new Team);
	}

	/**
	 * @param int $company_id
	 * @return ITeam[]
	 */
	public function getByCompany($company_id) {
		$query = new QueryObject(new Team);
		$query->addAddCondition(QueryCriteria::equal('CompanyID', $company_id));
		list($list, $size) = $this->getAll($query, 0, 1000);
		return $list;
	}

	/**
	 * @param string $name
	 * @param int    $company_id
	 * @return ITeam
	 */
	public function getByNameAndCompany($name, $company_id)
	{
		$query = new QueryObject(new Team);

		$query->addAddCondition(QueryCriteria::equal('CompanyID', $company_id));
		$query->addAddCondition(QueryCriteria::equal('Name', $name));

		return $this->getBy($query);
	}
}