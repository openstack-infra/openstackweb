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
 * Class CompanyCountQuery
 */
final class CompanyCountQuery implements IQueryHandler {

	/**
	 * @param IQuerySpecification $specification
	 * @return IQueryResult
	 */
	public function handle(IQuerySpecification $specification)
	{
		$res = 0;
		if($specification instanceof CompanyCountQuerySpecification){
			$params  = $specification->getSpecificationParams();
			$filter = '';

			if(!is_null($params[0]))
				$filter .= " AND MemberLevel = '".$params[0]."' ";
			if(!is_null($params[1]))
				$filter .= " AND Country != 'NULL' and Country != '".$params[1]."' ";

			$sql = <<< SQL
			SELECT COUNT(C.ID) FROM Company C WHERE DisplayOnSite = 1 {$filter}

SQL;
			$res = (int)DB::query($sql)->value();
		}
		return new AbstractQueryResult(array($res));
	}
}