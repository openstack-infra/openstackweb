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
 * Class TrainingCoursesLocationQueryHandler
 */
final class TrainingCoursesLocationQueryHandler implements IQueryHandler {

	/**
	 * @param IQuerySpecification $specification
	 * @return IQueryResult
	 */
	public function handle(IQuerySpecification $specification){
		$params = $specification->getSpecificationParams();
		$current_date   = @$params['name_pattern'];
		$current_date   = Convert::raw2sql($current_date);
		$sql       = <<< SQL
        SELECT TrainingCourseSchedule.City, TrainingCourseSchedule.State, TrainingCourseSchedule.Country
        FROM TrainingCourse
        INNER JOIN CompanyService  ON CompanyService.ID  = TrainingCourse.TrainingServiceID AND CompanyService.ClassName='TrainingService'
        INNER JOIN TrainingCourseSchedule ON TrainingCourseSchedule.CourseID = TrainingCourse.ID
        LEFT JOIN TrainingCourseScheduleTime ON TrainingCourseScheduleTime.LocationID = TrainingCourseSchedule.ID
        WHERE CompanyService.Active = 1
        AND
        (
          ((DATE('{$current_date}') < TrainingCourseScheduleTime.StartDate AND DATE('{$current_date}') < TrainingCourseScheduleTime.EndDate) OR (TrainingCourse.Online=1 AND TrainingCourseScheduleTime.StartDate IS NULL AND TrainingCourseScheduleTime.EndDate IS NULL))
        )
        GROUP BY TrainingCourseSchedule.City, TrainingCourseSchedule.State, TrainingCourseSchedule.Country
        ORDER BY TrainingCourseSchedule.City, TrainingCourseSchedule.State, TrainingCourseSchedule.Country ASC;
SQL;
		$results   = DB::query($sql);
		$locations = array();

		for ($i = 0; $i < $results->numRecords(); $i++) {
			$record = $results->nextRecord();
			$city    = $record['City'];
			$state   = $record['State'];
			$country = Geoip::countryCode2name($record['Country']);

			if(!empty($state))
				$value   = sprintf('%s, %s, %s',$city, $state, $country);
			else
				$value   = sprintf('%s, %s',$city, $country);
			array_push($locations, new SearchDTO($value,$value));
		}
		return new OpenStackImplementationNamesQueryResult($locations);
	}
}