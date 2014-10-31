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
 * Class CourseManager
 */
final class CourseManager {
	/**
	 * @var ICourseRepository
	 */
	private $course_repository;
	/**
	 * @var ITransactionManager
	 */
	private $tx_manager;

	/**
	 * @var ITrainingFactory
	 */
	private $factory;
	/**
	 * @var ITrainingRepository
	 */
	private $training_repository;

	/***
	 * @var IEntityRepository
	 */
	private $course_type_repository;

	/**
	 * @var IEntityRepository
	 */
	private $course_level_repository;

	/**
	 * @var IEntityRepository
	 */
	private $course_related_project_repository;

	public function __construct(ITrainingRepository $training_repository,
								IEntityRepository   $course_type_repository,
								IEntityRepository   $course_level_repository,
								IEntityRepository   $course_related_project_repository,
								ICourseRepository   $course_repository,
	                            ITrainingFactory    $factory,
	                            ITransactionManager $tx_manager){
		$this->course_repository       = $course_repository;
		$this->training_repository     = $training_repository;
		$this->course_type_repository  = $course_type_repository;
		$this->course_level_repository = $course_level_repository;
		$this->course_related_project_repository = $course_related_project_repository;
		$this->tx_manager              = $tx_manager;
		$this->factory                 = $factory;
	}

	/**
	 * @param array $data
	 * @return ICourse
	 */
	public function register(array $data){

		$factory                           = $this->factory;
		$course_repository                 = $this->course_repository;
		$training_repository               = $this->training_repository;
		$course_type_repository            = $this->course_type_repository;
		$course_level_repository           = $this->course_level_repository;
		$course_related_project_repository = $this->course_related_project_repository;

		$res = $this->tx_manager->transaction(function() use($data,
													  $factory,
			                                          $course_repository,
			                                          $training_repository,
												      $course_type_repository,
												      $course_level_repository,
													  $course_related_project_repository){

			$course_id   = intval(Convert::raw2sql(@$data['ID']));
			$training_id = intval(Convert::raw2sql(@$data['TrainingServiceID']));
			$type_id     = intval(Convert::raw2sql(@$data['TypeID']));
			$level_id    = intval(Convert::raw2sql(@$data['LevelID']));

			if($course_id > 0){
				$course = $course_repository->getById($course_id);
				$course->clearLocations();
				$course->clearCoursePreRequisites();
				$course->clearRelatedProjects();
			}
			else{
				$course = $factory->buildCourse();
				$course_repository->add($course);
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

			if($training_id > 0){
				$training = $training_repository->getById($training_id);
				if(!$training) throw new NotFoundEntityException('Training',sprintf('id %s',$training_id));
				$course->setTraining($training);
				$training->addAssociatedCourse($course);
			}

			if($type_id > 0){
				$course_type = $course_type_repository->getById($type_id);
				if(!$course_type) throw new NotFoundEntityException('CourseType',sprintf('id %s',$type_id));
				$course->setCourseType($course_type);
			}

			if($level_id > 0){
				$course_level = $course_level_repository->getById($level_id);
				if(!$course_level) throw new NotFoundEntityException('CourseLevel',sprintf('id %s',$level_id));
				$course->setCourseLevel($course_level);
			}

			// Projects
			if(isset($data['Projects'])){
				foreach($data['Projects'] as $project_id){
					$project = $course_related_project_repository->getById(intval($project_id));
					if(!$project) throw new NotFoundEntityException('CourseRelatedProject',sprintf('id %s',$project_id));
					$course->addRelatedProject($project);
				}
			}

			$locations = array();
			if(!$course->isOnline()){
				//save locations only if course is not online type
				if(isset($data['StartDate'])){
					foreach($data['StartDate'] as $K=>$C){
						$city     = $data['City'][$K];
						$state    = $data['State'][$K];
						$country  = $data['Country'][$K];
						$key      = $city.'.'.$state.'.'.$country;
						if(!array_key_exists($key,$locations)){
							$locations[$key] = $factory->buildCourseLocation($city,$state,$country);
						}
						$location = $locations[$key];
						$location->addDate($factory->buildCourseScheduleTime($data['StartDate'][$K],$data['EndDate'][$K],$data['LinkS'][$K]));
					}

					foreach($locations as $key => $location)
						$course->addLocation($location);
				}
			}
			return $course;
		});
		return $res;
	}

	/**
	 * @param int $course_id
	 */
	public function unRegister($course_id){
		$repository = $this->course_repository;
		$this->tx_manager->transaction(function() use($course_id, $repository){
			$course = $repository->getBydId($course_id);
			$repository->delete($course);
		});
	}
} 