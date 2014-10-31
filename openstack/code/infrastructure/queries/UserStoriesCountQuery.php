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
 * Class UserStoriesCountQuery
 */
final class UserStoriesCountQuery implements IQueryHandler {

	/**
	 * @param IQuerySpecification $specification
	 * @return IQueryResult
	 */
	public function handle(IQuerySpecification $specification)
	{
		$res = 0;
		if($specification instanceof UserStoriesCountQuerySpecification){
			$params  = $specification->getSpecificationParams();
			$filter = '';
			if($params[0]== true){
				$filter = ' AND FeaturedOnSite = 1 ';
			}
			$sql = <<< SQL
			SELECT COUNT(U.ID) FROM OpenstackUser U
			inner join Page P ON P.ID = U.ID
			inner join SiteTree ST on ST.ID = U.ID
			WHERE ListedOnSite = 1 AND Status in ('Published','Saved (update) ') {$filter}

SQL;
			$res = (int)DB::query($sql)->value();
		}
		return new AbstractQueryResult(array($res));
	}
}