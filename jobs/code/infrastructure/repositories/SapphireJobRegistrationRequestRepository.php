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
 * Class SapphireJobRegistrationRequestRepository
 */
final class SapphireJobRegistrationRequestRepository
extends SapphireRepository
implements IJobRegistrationRequestRepository
{

	public function __construct(){
		parent::__construct(new JobRegistrationRequest);
	}

	/**
	 * @param int $offset
	 * @param int $limit
	 * @return array
	 */
	public function getAllNotPostedAndNotRejected($offset = 0, $limit = 10)	{
		$query = new QueryObject();
		$query->addAddCondition(QueryCriteria::equal('isPosted', 0));
		$query->addAddCondition(QueryCriteria::equal('isRejected', 0));
		return  $this->getAll($query,$offset,$limit);
	}
}