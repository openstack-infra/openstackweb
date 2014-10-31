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
 * Interface IEventRegistrationRequest
 */
interface IEventRegistrationRequest extends IEntity {
	/**
	 * @param EventPointOfContact $point_of_contact
	 * @return void
	 */
	function registerPointOfContact(EventPointOfContact $point_of_contact);

	/**
	 * @return EventPointOfContact
	 */
	function getPointOfContact();

	/**
	 * @param EventMainInfo $info
	 * @return void
	 */
	function registerMainInfo(EventMainInfo $info);

	/**
	 * @return EventMainInfo
	 */
	function getMainInfo();

	/**
	 * @param EventLocation $location
	 * @return void
	 */
	public function registerLocation(EventLocation $location);

	/**
	 * @return EventLocation
	 */
	public function getLocation();
	/**
	 * @param EventDuration $duration
	 * @return void
	 */
	public function registerDuration(EventDuration $duration);

	/**
	 * @return EventDuration
	 */
	public function getDuration();

	/**
	 * @param Member $user
	 * @return void
	 */
	public function registerUser(Member $user);

	/**
	 * @return boolean
	 */
	public function hasRegisteredUser();

	/**
	 * @return Member
	 */
	public function getRegisteredUser();


	/**
	 * @param SponsorInfo $sponsor_info
	 * @return void
	 */
	public function registerSponsor(SponsorInfo $sponsor_info);

	/**
	 * @throws EntityValidationException
	 */
	public function markAsRejected();

	/**
	 * @throws EntityValidationException
	 */
	public function markAsPosted();
}