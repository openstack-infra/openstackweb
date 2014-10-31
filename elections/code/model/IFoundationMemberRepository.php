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
 * Interface IFoundationMemberRepository
 */
interface IFoundationMemberRepository extends IEntityRepository {

	/**
	 * @param int                 $n
	 * @param int                 $limit
	 * @param int                 $offset
	 * @param IElectionRepository $election_repository
	 * @return int[]
	 */
	public function getMembersThatNotVotedOnLatestNElections($n, $limit, $offset, IElectionRepository $election_repository);


	/**
	 * @param string $first_name
	 * @param string $last_name
	 * @return IFoundationMember
	 */
	public function getByCompleteName($first_name, $last_name);
} 