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
 * Class CourseLocationViewModel
 */
class CourseLocationViewModel extends ViewableData {

	/**
	 * @var TrainingCourseLocationDTO
	 */
	private $dto;

	/**
	 * @param TrainingCourseLocationDTO $dto
	 */
	public function __construct(TrainingCourseLocationDTO $dto){
		$this->dto = $dto;
	}

	/**
	 * @return string
	 */
	public function getCity(){
		return $this->dto->getCity();
	}

	/**
	 * @return string
	 */
	public function getState(){
		return $this->dto->getState();
	}

	/**
	 * @return mixed
	 */
	public function getCountry(){
		return @CountryCodes::$iso_3166_countryCodes[$this->dto->getCountry()];
	}

	/**
	 * @return string
	 */
	public function getLink(){
		return $this->dto->getLink();
	}

	/**
	 * @return string
	 */
	public function getStartDateMonth(){
		return  DateTimeUtils::getMonthShortName($this->dto->getStartDate());
	}

	/**
	 * @return bool|string
	 */
	public function getStartDateDay(){
		return  DateTimeUtils::getDay($this->dto->getStartDate());
	}

	/**
	 * @return bool|string
	 */
	public function getStartDateYear(){
		return  DateTimeUtils::getYear($this->dto->getStartDate());
	}

	/**
	 * @return string
	 */
	public function getEndDateMonth(){
		return  DateTimeUtils::getMonthShortName($this->dto->getEndDate());
	}

	/**
	 * @return bool|string
	 */
	public function getEndDateDay(){
		return  DateTimeUtils::getDay($this->dto->getEndDate());
	}

	/**
	 * @return bool|string
	 */
	public function getEndDateYear(){
		return  DateTimeUtils::getYear($this->dto->getEndDate());
	}

	/**
	 * @return string
	 */
	public function getDays(){
		return DateTimeUtils::getDayDiff($this->dto->getStartDate(),$this->dto->getEndDate());
	}
}