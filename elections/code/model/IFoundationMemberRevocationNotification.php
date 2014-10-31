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
 * Interface IFoundationMemberRevocationNotification
 */
interface IFoundationMemberRevocationNotification extends IEntity {

	const DaysBeforeRevocation = 30;
	/**
	 * @return string
	 */
	public function action();

	/**
	 * @return DateTime
	 */
	public function actionDate();

	/**
	 * @return string
	 */
	public function hash();

	/**
	 * @return DateTime
	 */
	public function sentDate();

	/**
	 * @return bool
	 */
	public function isExpired();

	/**
	 * @return bool
	 */
	public function isValid();

	/**
	 * @param IElection $latest_election
	 * @return void
	 */
	public function renew(IElection $latest_election);

	/**
	 * @return void
	 */
	public function revoke();

	/**
	 * @return void
	 */
	public function resign();

	/**
	 * @return IFoundationMember
	 */
	public function recipient();

	/**
	 * @return IElection
	 */
	public function latestElection();

	/**
	 * @return string
	 */
	public function generateHash();

	/**
	 * @return int
	 */
	public function remainingDays();

	/**
	 * @return DateTime
	 */
	public function expirationDate();

}