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
 * Class FoundationMembersSubscribedToNewsLetterCountQuery
 */
final class FoundationMembersSubscribedToNewsLetterCountQuery implements IQueryHandler {

	/**
	 * @param IQuerySpecification $specification
	 * @return IQueryResult
	 */
	public function handle(IQuerySpecification $specification)
	{
		$res = 0;

		if($specification instanceof FoundationMembersSubscribedToNewsLetterCountQuerySpecification){

			$params    = $specification->getSpecificationParams();
			$filter    = '';

			if(!is_null($params[0])){
				$filter = " AND M.Country != '". $params[0]."' ";
			}

			$sql = <<< SQL
			SELECT COUNT(M.ID) From Member M
			LEFT  JOIN Group_Members GM ON GM.MemberID = M.ID
			INNER JOIN `Group` G on G.ID = GM.GroupID
			WHERE G.Code = 'foundation-members' AND M.SubscribedToNewsletter = 1 {$filter};
SQL;

			$res = (int)DB::query($sql)->value();

		}

		return new AbstractQueryResult(array($res));
	}
}