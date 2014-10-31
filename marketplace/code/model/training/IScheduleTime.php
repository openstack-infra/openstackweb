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
 * Interface IScheduleTime
 */
interface IScheduleTime extends IEntity {

	/**
	 * @return string
	 */
	public function getStartDate();

	/**
	 * @param string $start_date
	 * @return void
	 */
	public function setStartDate($start_date);

	/**
	 * @return string
	 */
	public function getEndDate();

	/**
	 * @param string $end_date
	 * @return void
	 */
	public function setEndDate($end_date);

	/**
	 * @return string
	 */
	public function getLink();

	/**
	 * @param string $link
	 * @return void
	 */
	public function setLink($link);

	/**
	 * @return ICourseLocation
	 */
	public function getAssociatedLocation();

	/**
	 * @param ICourseLocation $location
	 * @return void
	 */
	public function setAssociatedLocation(ICourseLocation $location);

} 