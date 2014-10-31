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
 * Class TrainingFacade
 */
final class TrainingFacade {

	/**
	 * @var TrainingManager
	 */
	private $training_manager;
	/**
	 * @var ICourseRepository
	 */
	private $course_repository;


	/**
	 * @var Controller
	 */
	private $controller;

	/**
	 * @param Controller        $controller
	 * @param TrainingManager   $training_manager
	 * @param ICourseRepository $course_repository
	 */
	public function __construct(
		Controller        $controller,
		TrainingManager   $training_manager,
		ICourseRepository $course_repository){

		$this->controller        = $controller;
		$this->training_manager  = $training_manager;
		$this->course_repository = $course_repository;
	}

	/**
	 * @param string $topic
	 * @param string $location
	 * @param string $level
	 * @param bool   $limit
	 * @return ArrayList|TrainingViewModel
	 */
	public function getTrainings($topic="",$location="",$level="",$limit=true) {
		$res = new ArrayList();
		$active_training   =  $this->training_manager->getActives(DateTimeUtils::getCurrentDate());
		foreach($active_training as $t){

			$vm = new TrainingViewModel(
				$t->getIdentifier(),
				$t->getName(),
				$t->getDescription(),
				$t->getCompany());

			$courses_dto  = $this->course_repository->get($t->getIdentifier() , DateTimeUtils::getCurrentDate(),$topic,$location,$level,$limit);
			if(!count($courses_dto)) continue;
			$courses      =  new ArrayList;
			foreach($courses_dto as $dto){
				$courses->push(new CourseViewModel($dto));
			}
			$vm->setCourses($courses);
			$res->push($vm);
		}
		return $res;
	}

	/**
	 * @param int $limit
	 * @return ArrayList
	 */
	public function getUpcomingCourses($limit=20){
		$res = new ArrayList();
		$courses_dto       = $this->course_repository->getUpcoming(DateTimeUtils::getCurrentDate(),$limit);
		foreach($courses_dto as $dto){
			if($this->training_manager->isActive($dto->getTrainingID()))
				$res->push(new CourseViewModel($dto));
		}
		return $res;
	}

	/**
	 * @param int $training_id
	 * @param string $company_url_segment
	 * @return array
	 * @throws Exception
	 */
	public function getCompanyTraining($training_id, $company_url_segment){

		if (empty($company_url_segment)) {
			throw new Exception("Invalid Company");
		}

		//@todo: remove dataobjects dependencies

		$company   = Company::get()->filter('URLSegment',$company_url_segment)->first();
		$training  = empty($training_id)? null: TrainingService::get()->byID($training_id);

		if (!$company) throw new Exception("Invalid Company");

		if(!$training){
			//get default program
			$training = $company->getDefaultTraining();
		}

		//check if program belongs to selected company

		$training_company = $training->Company();

		if ($training_company->getIdentifier() != $company->getIdentifier()) {
			//if not , get default program
			$training = $company->getDefaultTraining();
		}

		if(!$this->training_manager->isActive($training->getIdentifier()))
			 return Security::permissionFailure($this->controller,"non active training!.");

		$courses = $this->training_manager->getCoursesByDate($training->getIdentifier(), DateTimeUtils::getCurrentDate());

		$courses_vm = new ArrayList;

 		foreach($courses as $course){
			$course_dto    = new CourseDTO(
				$course->getIdentifier(),
				$course->getName(),
				$course->getDescription(),
				$course->getTraining()->getIdentifier(),
				null,
				$course->level()->Level,
				$course->isOnline(),
				null,
				null,
				null,
				null,
				null,
				$course->getOnlineLink()
			);
			$locations_dto = $this->course_repository->getLocationsByDate($course->getIdentifier(),DateTimeUtils::getCurrentDate());
			$locations_vm  = new ArrayList;
			foreach($locations_dto as $location_dto){
				$locations_vm->push(new CourseLocationViewModel($location_dto));
			}

			$courses_vm->push(new CourseViewModel($course_dto,$locations_vm, $course->projects()));
		}

		$res =  array(
			'Company'  => $company,
			'Training' => $training,
			'Courses'  => $courses_vm
		);
		return $res;
	}

} 