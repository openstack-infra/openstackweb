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
 * Class CourseViewModel
 */
class CourseViewModel extends ViewableData {

	/**
	 * @var CourseDTO
	 */
	private $dto;
	/**
	 * @var ArrayList
	 */
	private $locations;
	/**
	 * @var ArrayList
	 */
	private $related_projects;

	/**
	 * @param CourseDTO     $dto
	 * @param ArrayList $locations
	 * @param ArrayList $related_projects
	 */
	public function __construct(CourseDTO $dto, ArrayList $locations = null,ArrayList $related_projects = null){
		$this->dto              = $dto;
		$this->locations        = $locations;
		$this->related_projects = $related_projects;
	}

	/**
	 * @return string
	 */
	public function getDescription(){
		return $this->dto->getDescription();
	}

	/**
	 * @return int
	 */
	public function getCourseID()
	{
		return $this->dto->getCourseID();
	}

	/**
	 * @return string
	 */
	public function getCourseName()
	{
		return $this->dto->getCourseName();
	}

	/**
	 * @return int
	 */
	public function getTrainingID()
	{
		return $this->dto->getTrainingID();
	}

	/**
	 * @return string
	 */
	public function getCompanyURL()
	{
		return $this->dto->getCompanyURL();
	}

	/**
	 * @return string
	 */
	public function getBookMark(){
		return  $this->getCompanyURL() ."/".$this->getTrainingID()."#course_".$this->getCourseID();
	}

	/**
	 * @return string
	 */
	public function getLevel()
	{
		return $this->dto->getLevel();
	}

	/**
	 * @return string
	 */
	public function getLwrLevel()
	{
		return strtolower($this->dto->getLevel());
	}

	/**
	 * @return bool
	 */
	public function getIsOnline()
	{
		return $this->dto->getIsOnline();
	}

	/**
	 * @return string
	 */
	public function getStartDate()
	{
		return $this->dto->getStartDate();
	}

	/**
	 * @return string
	 */
	public function getEndDate()
	{
		return $this->dto->getEndDate();
	}

	/**
	 * @return string
	 */
	public function getCity()
	{
		return $this->dto->getCity();
	}

	/**
	 * @return string
	 */
	public function getCountry()
	{
		return $this->dto->getCountry();
	}

	/**
	 * @return string
	 */
	public function getState()
	{
		return $this->dto->getState();
	}

	/**
	 * @return string
	 */
	public function getStartDateMonth(){
		return !is_null($this->getStartDate())?DateTimeUtils::getMonthShortName($this->getStartDate()):'';
	}

	/**
	 * @return bool|string
	 */
	public function getStartDateDay(){
		return !is_null($this->getStartDate())?DateTimeUtils::getDay($this->getStartDate()):'';
	}

	/**
	 * @return string
	 */
	public function getEndDateMonth(){
		return !is_null($this->getEndDate())?DateTimeUtils::getMonthShortName($this->getEndDate()):'';
	}

	/**
	 * @return bool|string
	 */
	public function getEndDateDay(){
		return !is_null($this->getEndDate())?DateTimeUtils::getDay($this->getEndDate()):'';
	}

	/**
	 * @return ArrayList
	 */
	public function getCurrentLocations(){
		return $this->locations;
	}

	/**
	 * @return ArrayList
	 */
	public function getProjects(){
		return $this->related_projects;
	}

	/**
	 * @return string
	 */
	public function getLink(){
		return $this->dto->getLink();
	}
}