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
 * Interface IJobRegistrationRequest
 */
interface IJobRegistrationRequest extends IEntity {
	/**
	 * @param JobMainInfo $info
	 * @return void
	 */
	public function registerMainInfo(JobMainInfo $info);

	/**
	 * @param IJobLocation $location
	 * @return void
	 */
	public function registerLocation(IJobLocation $location);

	/**
	 * @param JobPointOfContact $point_of_contact
	 * @return void
	 */
	public function registerPointOfContact(JobPointOfContact $point_of_contact);

	/**
	 * @return void
	 */
	public function markAsPosted();

	/**
	 * @return void
	 */
	public function markAsRejected();
	/**
	 * @param Member $user
	 * @return void
	 */
	public function registerUser(Member $user);

	/**
	 * @return JobMainInfo
	 */
	function getMainInfo();

	/**
	 * @return JobPointOfContact
	 */
	function getPointOfContact();

	/**
	 * @return IJobLocation[]
	 */
	public function getLocations();

	/**
	 * @return void
	 */
	public function clearLocations();
}