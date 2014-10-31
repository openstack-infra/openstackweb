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
 * Class SapphireCourseRepository
 */
class SapphireCourseRepository
	extends SapphireRepository
	implements ICourseRepository {

	public function __construct(){
		parent::__construct(new TrainingCourse);
	}

	/**
	 * @param int    $training_id
	 * @param string $current_date
	 * @param string $topic
	 * @param string $location
	 * @param string $level
	 * @param bool   $limit
	 * @return CourseDTO[]
	 */
	public function get($training_id, $current_date, $topic = "", $location = "", $level = "", $limit = true)
	{
		$courses = array();

		$filter = "";
		if(!is_null($topic) && strlen($topic)>0){
			$topic = Convert::raw2sql($topic);
			$filter = " AND (
                                c.Name LIKE '%{$topic}%' COLLATE utf8_general_ci OR
                                c.Description LIKE '%{$topic}%' COLLATE utf8_general_ci OR
                                p.Name LIKE '%{$topic}%' COLLATE utf8_general_ci OR
                                p.Overview LIKE '%{$topic}%' COLLATE utf8_general_ci OR
                                cc.Name LIKE '%{$topic}%' COLLATE utf8_general_ci OR
                                tct.Type LIKE '%{$topic}%' COLLATE utf8_general_ci OR
                                pp.Name LIKE '%{$topic}%' COLLATE utf8_general_ci OR
                                pp.Codename LIKE '%{$topic}%' COLLATE utf8_general_ci
                             )";
		}
		if(!is_null($location) && strlen($location)>0){
			$location = Convert::raw2sql($location);
			$location_parts = explode(",",$location);
			$filter .= " AND ( ";
			$condition = "";

			$country_names = array_flip(CountryCodes::$iso_3166_countryCodes);
			$keys          = array_keys($country_names);
			$parts_count   = count($location_parts);

			if($parts_count>1){
				$conditions = $parts_count==2 ? array("l.City", "l.Country"):array("l.City","l.State","l.Country");
				for($i=0;$i<$parts_count;$i++){
					$l = trim($location_parts[$i]);
					if(empty($l)) continue;
					if(array_key_exists($l,$country_names)){
						$l =  $country_names[$l];
					}
					else{
						$result = preg_grep("/^{$l}/", $keys);
						if(count($result)>0){
							$l =  $country_names[reset($result)];
						}
					}
					$condition .=  $conditions[$i]." LIKE '%{$l}%' COLLATE utf8_general_ci AND ";
				}
				$condition= substr($condition,0,-4);
			}
			else{
				$l = trim($location_parts[0]);
				if(array_key_exists($l,$country_names)){
					$l =  $country_names[$l];
				}
				else{
					$result = preg_grep("/^{$l}/", $keys);
					if(count($result)>0){
						$l =  $country_names[reset($result)];
					}
				}
				$condition .= " ( l.City LIKE '%{$l}%' COLLATE utf8_general_ci OR l.State LIKE '%{$l}%' COLLATE utf8_general_ci OR l.Country LIKE '%{$l}%'  COLLATE utf8_general_ci )";
			}
			$filter .= $condition . " ) ";
		}

		if(!is_null($level) && strlen($level)>0){
			$level = Convert::raw2sql($level);
			$filter = " AND lv.Level LIKE '%{$level}%' COLLATE utf8_general_ci ";
		}

		$sql = <<< SQL
        SELECT
            c.ID,
            p.ID AS TrainingID,
            cc.URLSegment AS Company_URLSegment,
            c.Name,
            c.Online,
            c.Link,
            c.Description,
            lv.Level,
            MIN(t.StartDate) AS NEXT_START_DATE,
            MIN(t.EndDate) AS NEXT_END_DATE,
            l.City,
            l.State,
            l.Country
        FROM
        TrainingCourse c
        INNER JOIN TrainingCourseLevel lv ON lv.ID = c.LevelID
        INNER JOIN CompanyService  p ON p.ID  = c.TrainingServiceID AND p.ClassName='TrainingService' AND  p.ID = {$training_id}
        INNER JOIN Company cc        ON cc.ID = p.CompanyID
        LEFT JOIN TrainingCourseSchedule l ON l.CourseID = c.ID
        LEFT JOIN TrainingCourseScheduleTime t ON t.LocationID = l.ID
        LEFT JOIN TrainingCourseType tct on tct.ID = c.TypeID
        LEFT JOIN TrainingCourse_Projects tcp on tcp.TrainingCourseID = c.ID
        LEFT JOIN Project pp ON pp.ID = tcp.ProjectID
        WHERE
        p.Active=1 AND
        (
          ((DATE('{$current_date}') < t.StartDate AND DATE('{$current_date}') < t.EndDate) OR (c.Online=1 AND t.StartDate IS NULL AND t.EndDate IS NULL)) {$filter}
        )
        GROUP BY c.ID , c.Name , c.Link , lv.Level , l.City , l.State , l.Country
        ORDER BY lv.SortOrder ASC, t.StartDate ASC, t.EndDate ASC
SQL;

		$sql .= ($limit)?" LIMIT 3 ":";";

		$results = DB::query($sql);
		for ($i = 0; $i < $results->numRecords(); $i++) {
			$record = $results->nextRecord();
			array_push($courses, new CourseDTO(
				(int)$record['ID'],
				$record['Name'],
				$record['Description'],
				(int)$record['TrainingID'],
				$record['Company_URLSegment'],
				$record['Level'],
				(bool)$record['Online'],
				$record['NEXT_START_DATE'],
				$record['NEXT_END_DATE'],
				$record['City'],
				$record['State'],
				$record['Country'],
				$record['Link']
			));
		}
		return $courses;
	}

	/**
	 * @param string $current_date
	 * @param int    $limit
	 * @return CourseDTO[]
	 */
	public function getUpcoming($current_date ,$limit = 20){

		$courses      = array();
		$current_date = Convert::raw2sql($current_date);
		$limit        = Convert::raw2sql($limit);

		$sql = <<< SQL
        SELECT TC.ID,
        	   P.ID AS TrainingID,
        	   C.URLSegment AS Company_URLSegment,
        	   TC.Name,
        	   MIN(D.StartDate) StartDate ,
        	    L.City
        FROM TrainingCourse TC
        INNER JOIN TrainingCourseSchedule L on L.CourseID = TC.ID
        INNER JOIN TrainingCourseScheduleTime D on D.LocationID = L.ID
        INNER JOIN CompanyService P on P.ID = TC.TrainingServiceID
        INNER JOIN Company C on C.ID=P.CompanyID
        WHERE
        TC.Online = 0 AND
        DATE('{$current_date}') < D.StartDate AND DATE('{$current_date}') < D.EndDate
        GROUP BY TC.ID
        ORDER BY StartDate ASC LIMIT {$limit};
SQL;
		$results = DB::query($sql);
		for ($i = 0; $i < $results->numRecords(); $i++) {
			$record = $results->nextRecord();
			array_push($courses,new CourseDTO (
				(int)$record['ID'],
				$record['Name'],
				null,
				(int)$record['TrainingID'],
				$record['Company_URLSegment'],
				null,
				null,
				$record['StartDate'],
				null,
				$record['City'],
				null,
				null,
				null
			));
		}
		return $courses;
	}

	/**
	 * @param int $course_id
	 * @param string $current_date
	 * @return TrainingCourseLocationDTO[]
	 */
	public function getLocationsByDate($course_id, $current_date){

		$locations    =  array();
		$course_id    =  intval(Convert::raw2sql($course_id));
		$current_date =  Convert::raw2sql($current_date);

		$sql = <<< SQL
        SELECT L.City, L.State, L.Country,T.StartDate, T.EndDate, T.Link
        FROM TrainingCourseSchedule L
        INNER JOIN TrainingCourseScheduleTime T ON T.LocationID = L.ID
        WHERE DATE('{$current_date}') < T.StartDate AND DATE('{$current_date}') < T.EndDate AND L.CourseID = {$course_id}
        ORDER BY T.StartDate ASC, T.EndDate ASC;
SQL;

		$results = DB::query($sql);
		for ($i = 0; $i < $results->numRecords(); $i++) {
			$record = $results->nextRecord();
			array_push($locations, new TrainingCourseLocationDTO (
				0,
				$record['City'],
				$record['State'],
				$record['Country'],
				$record['StartDate'],
				$record['EndDate'],
				$record['Link']
			));
		}
		return $locations;
	}

	/**
	 * @param int $course_id
	 * @return TrainingCourseLocationDTO[]
	 */
	public function getLocations($course_id){

		$res       = array();
		$course_id = Convert::raw2sql($course_id);

		$sql = <<< SQL
        SELECT L.ID,
         	   L.City,
         	   L.State,
         	   L.Country,
         	   T.StartDate,
         	   T.EndDate,
         	   T.Link
        FROM TrainingCourseSchedule L
        LEFT JOIN TrainingCourseScheduleTime T ON T.LocationID = L.ID
        WHERE L.CourseID = {$course_id}
        ORDER BY L.City, L.State, L.Country,T.StartDate ASC, T.EndDate ASC;
SQL;

		$results = DB::query($sql);

		for ($i = 0; $i < $results->numRecords(); $i++) {

			$record = $results->nextRecord();
			array_push($res, new TrainingCourseLocationDTO (
				(int)$record['ID'],
				$record['City'],
				$record['State'],
				$record['Country'],
				$record['StartDate'],
				$record['EndDate'],
				$record['Link']
			));
		}
		return $res;
	}

	/**
	 * @param IEntity $entity
	 * @return void
	 */
	public function delete(IEntity $entity){
		$entity->clearLocations();
		$entity->clearCoursePreRequisites();
		$entity->clearRelatedProjects();
		parent::delete($entity);
	}

}