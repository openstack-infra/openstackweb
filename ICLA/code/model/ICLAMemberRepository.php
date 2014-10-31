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
 * Interface ICLAMemberRepository
 */
interface ICLAMemberRepository extends IMemberRepository {

	/***
	 * @return int[]
	 */
	function getAllGerritIds();

	/**
	 * @param int $offset
	 * @param int $limit
	 * @return ICLAMember[]
	 */
	function getAllICLAMembers($offset, $limit);

	/**
	 * @param string $email
	 * @param int $offset
	 * @param int $limit
	 * @return array
	 */
	function getAllIClaMembersByEmail($email, $offset, $limit);

	/**
	 * @param string $first_name
	 * @param int $offset
	 * @param int $limit
	 * @return array
	 */
	function getAllIClaMembersByFirstName($first_name, $offset, $limit);

	/**
	 * @param string $last_name
	 * @param int $offset
	 * @param int $limit
	 * @return array
	 */
	function getAllIClaMembersByLastName($last_name, $offset, $limit);

} 