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
 * Class CourseDTO
 */
class CourseDTO {

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var int
	 */
	private $training_id;
	/**
	 * @var string
	 */
	private $company_url;
	/**
	 * @var string
	 */
	private $level;
	/**
	 * @var bool
	 */
	private $is_online;
	/**
	 * @var string
	 */
	private $start_date;
	/**
	 * @var string
	 */
	private $end_date;
	/**
	 * @var string
	 */
	private $city;
	/**
	 * @var string
	 */
	private $state;
	/**
	 * @var string
	 */
	private $country;

	/**
	 * @var string
	 */
	private $link;
	/**
	 * @param int    $id
	 * @param string $name
	 * @param string $description
	 * @param int    $training_id
	 * @param string $company_url
	 * @param string $level
	 * @param bool   $is_online
	 * @param string $start_date
	 * @param string $end_date
	 * @param string $city
	 * @param string $state
	 * @param string $country
	 * @param string $link
	 */
	public function __construct($id, $name,$description, $training_id, $company_url, $level, $is_online, $start_date, $end_date, $city, $state, $country, $link){
		$this->id          = $id;
		$this->name        = $name;
		$this->description = $description;
		$this->training_id = $training_id;
		$this->company_url=$company_url;
		$this->level = $level;
		$this->is_online = $is_online;
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->city = $city;
		$this->state = $state;
		$this->country = $country;
		$this->link = $link;
	}

	public function getCourseID()
	{
		return $this->id;
	}

	public function getCourseName()
	{
		return $this->name;
	}

	public function getTrainingID()
	{
		return $this->training_id;
	}

	public function getCompanyURL()
	{
		return $this->company_url;
	}

	public function getLevel()
	{
		return $this->level;
	}

	public function getIsOnline()
	{
		return $this->is_online;
	}

	public function getStartDate()
	{
		return $this->start_date;
	}

	public function getEndDate()
	{
		return $this->end_date;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function getCountry()
	{
		return $this->country;
	}

	public function getState()
	{
		return $this->state;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getLink(){
		return $this->link;
	}
}