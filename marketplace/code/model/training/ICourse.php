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
 * Interface ICourse
 */
interface ICourse extends IEntity {

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name);


	/**
	 * @return string
	 */
	public function getOnlineLink();

	/**
	 * @param string $link
	 * @return void
	 */
	public function setOnlineLink($link);
	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description);

	/**
	 * @return bool
	 */
	public function isPaid();

	/**
	 * @return void
	 */
	public function Paid();

	/**
	 * @return void
	 */
	public function Free();

	/**
	 * @return bool
	 */
	public function isOnline();

	/**
	 * @return void
	 */
	public function Online();

	/**
	 * @return void
	 */
	public function Offline();

	/**
	 * @return ICourseRelatedProject[]
	 */
	public function getRelatedProjects();

	/**
	 * @param ICourseRelatedProject $new_project
	 * @return void
	 */
	public function addRelatedProject(ICourseRelatedProject $new_project);

	/**
	 * @return void
	 */
	public function clearRelatedProjects();

	/**
	 * @return ITrainingCoursePrerequisite[]
	 */
	public function getCoursePreRequisites();

	/**
	 * @param ITrainingCoursePrerequisite $pre_requisite
	 * @return void
	 */
	public function addCoursePreRequisite(ITrainingCoursePrerequisite $pre_requisite);

	/**
	 * @return void
	 */
	public function clearCoursePreRequisites();

	/**
	 * @return ICourseLocation[]
	 */
	public function getLocations();

	/**
	 * @param ICourseLocation $location
	 * @return void
	 */
	public function addLocation(ICourseLocation $location);

	/**
	 * @return mixed
	 */
	public function clearLocations();

	/**
	 * @return ITrainingCourseType
	 */
	public function getCourseType();

	/**
	 * @param ITrainingCourseType $type
	 * @return void
	 */
	public function setCourseType(ITrainingCourseType $type);

	/**
	 * @return ITrainingCourseLevel
	 */
	public function getCourseLevel();

	/**
	 * @param ITrainingCourseLevel $level
	 * @return void
	 */
	public function setCourseLevel(ITrainingCourseLevel $level);

	/**
	 * @return ITraining
	 */
	public function getTraining();

	/**
	 * @param ITraining $training
	 * @return void
	 */
	public function setTraining(ITraining $training);

}