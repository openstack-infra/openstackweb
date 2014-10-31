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
 * Class TrainingFactory
 */
final class TrainingFactory implements ITrainingFactory {

	/**
	 * @param string           $name
	 * @param string           $overview
	 * @param ICompany         $company
	 * @param bool             $active
	 * @param IMarketPlaceType $marketplace_type
	 * @param null|string      $call_2_action_url
	 * @return ICompanyService
	 */
	public function buildCompanyService($name, $overview, ICompany $company, $active, IMarketPlaceType $marketplace_type, $call_2_action_url = null)
	{
		$training = new TrainingService;
		$training->setName($name);
		$training->setDescription($overview);
		if($active)
			$training->activate();
		else
			$training->deactivate();
		$training->setMarketplace($marketplace_type);
		$training->setCompany($company);
		return $training;
	}

	/**
	 * @param string  $name
	 * @param string $description
	 * @param bool $active
	 * @param ICompany $company
	 * @return ITraining|TrainingService
	 */
	public function buildTraining($name,$description,$active, ICompany $company){
		$training = new TrainingService;
		$training->setName($name);
		$training->setDescription($description);
		if($active)
			$training->activate();
		else
			$training->deactivate();
		$training->setCompany($company);
		return $training;
	}

	/**
	 * @param array $data
	 * @return ICourse
	 */
	public function buildTrainingCourse(array $data){

		$course      = new TrainingCourse;
		$course_id   = intval(Convert::raw2sql(@$data['ID']));
		$training_id = intval(Convert::raw2sql(@$data['TrainingServiceID']));
		$type_id     = intval(Convert::raw2sql(@$data['TypeID']));
		$level_id    = intval(Convert::raw2sql(@$data['LevelID']));

		if($course_id > 0){
			$course->setField('ID',$course_id);
		}

		if($training_id > 0){
			$course->setField('TrainingServiceID',$training_id);
		}

		if($type_id > 0){
			$course->setField('TypeID',$type_id);
		}

		if($level_id > 0){
			$course->setField('LevelID',$level_id);
		}

		$course->setName($data['Name']);
		$course->setDescription($data['Description']);

		if(@$data['Online']){
			$course->Online();
			$course->setOnlineLink($data['Link']);
		}
		else{
			$course->Offline();
			$course->setOnlineLink(null);
		}

		if(@$data['Paid']){
			$course->Paid();
		}
		else{
			$course->Free();
		}


		return $course;
	}

	/**
	 * @param string $city
	 * @param string $state
	 * @param string $country
	 * @return ICourseLocation
	 */
	public function buildCourseLocation($city, $state, $country)
	{
		$location = new TrainingCourseSchedule;
		$location->setCity($city);
		$location->setState($state);
		$location->setCountry($country);
		return $location;
	}

	/**
	 * @param string $start_date
	 * @param string $end_date
	 * @param string $link
	 * @return IScheduleTime
	 */
	public function buildCourseScheduleTime($start_date, $end_date, $link)
	{
		$time = new TrainingCourseScheduleTime;
		$time->setStartDate($start_date);
		$time->setEndDate($end_date);
		$time->setLink($link);
		return $time;
	}

	/**
	 * @param int $project_id
	 * @return ICourseRelatedProject
	 */
	public function buildCourseRelatedProject($project_id)
	{
		$project = new Project;
		$project->setField('ID',(int)$project_id);
		return $project;
	}

	/**
	 * @param $id
	 * @return ICompanyService
	 */
	public function buildCompanyServiceById($id)
	{
		$training = new TrainingService;
		$training->ID = $id;
		return $training;
	}

	/**
	 * @return ICourse
	 */
	public function buildCourse()
	{
		return new TrainingCourse;
	}
}