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
 * Interface ICourseLocation+
 */
interface ICourseLocation extends IEntity {

	/**
	 * @return string
	 */
	public function getCountry();

	/**
	 * @param string $country
	 * @return void
	 */
	public function setCountry($country);

	/**
	 * @return string
	 */
	public function getState();

	/**
	 * @param string $state
	 * @return void
	 */
	public function setState($state);

	/**
	 * @return string
	 */
	public function getCity();

	/**
	 * @param string $city
	 * @return void
	 */
	public function setCity($city);

	/**
	 * @return ICourse
	 */
	public function getAssociatedCourse();

	/**
	 * @param ICourse $course
	 * @return void
	 */
	public function setAssociatedCourse(ICourse $course);

	/**
	 * @return IScheduleTime[]
	 */
	public function getDates();

	/**
	 * @param IScheduleTime $date
	 * @return void
	 */
	public function addDate(IScheduleTime $date);

	/**
	 * @return void
	 */
	public function clearDates();

} 