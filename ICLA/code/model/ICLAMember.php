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
 * Interface ICLAMember
 */
interface ICLAMember extends IEntity {

	const CCLAGroupSlug      = 'ccla-admin';
	const CCLAPermissionSlug = 'CCLA_ADMIN';

	/**
	 * @return string
	 */
	public function getGerritId();

	/**
	 * @return DateTime
	 */
	public function getLastCommitedDate();


	/**
	 * @param int $gerrit_id
	 * @return void
	 */
	public function signICLA($gerrit_id);

	/**
	 * @param DateTime $date
	 * @return void
	 */
	public function updateLastCommitedDate(DateTime $date);

	/**
	 * @return bool
	 */
	public function isCCLAAdmin();

	/**
	 * @return ICLACompany
	 */
	public function getManagedCCLACompany();

	/**
	 * @return bool
	 */
	public function hasSignedCLA();
}