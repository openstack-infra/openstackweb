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
 * Interface IJobFactory
 */
interface IJobFactory {

	/**
	 * @param JobMainInfo       $info
	 * @param IJobLocation[]       $locations
	 * @param JobPointOfContact $point_of_contact
	 * @return IJobRegistrationRequest
	 */
	public function buildJobRegistrationRequest(JobMainInfo $info,
	                                            array $locations,
	                                            JobPointOfContact $point_of_contact);

	/**
	 * @param array $data
	 * @return JobMainInfo
	 */
	public function buildJobMainInfo(array $data);

	/**
	 * @param array $data
	 * @return IJobLocation[]
	 */
	public function buildJobLocations(array $data);

	/**
	 * @param array $data
	 * @return JobPointOfContact
	 */
	public function buildJobPointOfContact(array $data);

	/**
	 * @param IJobRegistrationRequest $request
	 * @return IJob
	 */
	public function buildJob(IJobRegistrationRequest $request);

	/**
	 * @param IJobRegistrationRequest $request
	 * @return IJobAlertEmail
	 */
	public function buildJobAlertEmail(IJobRegistrationRequest $request);
} 