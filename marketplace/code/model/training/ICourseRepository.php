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
 * Interface ICourseRepository
 */
interface ICourseRepository extends IEntityRepository {

	/**
	 * @param int    $training_id
	 * @param string $current_date
	 * @param string $topic
	 * @param string $location
	 * @param string $level
	 * @param bool   $limit
	 * @return CourseDTO[]
	 */
	public function get($training_id, $current_date,$topic="",$location="",$level="",$limit=true);

	/**
	 * @param string $current_date
	 * @param int    $limit
	 * @return CourseDTO[]
	 */
	public function getUpcoming($current_date,$limit=20);

	/**
	 * @param int $course_id
	 * @return TrainingCourseLocationDTO[]
	 */
	public function getLocations($course_id);

	/**
	 * @param int $course_id
	 * @param string $current_date
	 * @return TrainingCourseLocationDTO[]
	 */
	public function getLocationsByDate($course_id, $current_date);

} 