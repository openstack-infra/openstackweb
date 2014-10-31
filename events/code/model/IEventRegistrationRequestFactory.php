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

interface IEventRegistrationRequestFactory {
	/**
	 * @param EventMainInfo $info
	 * @param EventPointOfContact $point_of_contact
	 * @param EventLocation $location
	 * @param EventDuration $duration
	 * @param SponsorInfo   $sponsor
	 * @return IEventRegistrationRequest
	 */
	public function buildEventRegistrationRequest(EventMainInfo $info,
	                                              EventPointOfContact $point_of_contact,
	                                              EventLocation $location,
	                                              EventDuration $duration,
	                                              SponsorInfo $sponsor);

	/**
	 * @param array $data
	 * @return EventMainInfo
	 */
	public function buildEventMainInfo(array $data);

	/**
	 * @param array $data
	 * @return EventLocation
	 */
	public function buildEventLocation(array $data);

	/**
	 * @param array $data
	 * @return EventDuration
	 */
	public function buildEventDuration(array $data);

	/**
	 * @param array $data
	 * @return SponsorInfo
	 */
	public function buildSponsorInfo(array $data);

	/**
	 * @param array $data
	 * @return EventPointOfContact
	 */
	public function buildPointOfContact(array $data);

	public function buildEvent(IEventRegistrationRequest $request);

	public function buildEventAlertEmail(IEventRegistrationRequest $last);
} 