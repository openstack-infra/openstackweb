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
 * Class TrainingCourseLocationDTO
 */
class TrainingCourseLocationDTO {
	/**
	 * @var int
	 */
	private $id;
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
	private $start_date;
	/**
	 * @var string
	 */
	private $end_date;
	/**
	 * @var string
	 */
	private $link;

	/**
	 * @param int $id
	 * @param string $city
	 * @param string $state
	 * @param string $country
	 * @param string $start_date
	 * @param string $end_date
	 * @param string $link
	 */
	public function __construct($id, $city, $state, $country, $start_date, $end_date, $link){
		$this->id         = $id;
		$this->city       = $city;
		$this->state      = $state;
		$this->country    = $country;
		$this->start_date = $start_date;
		$this->end_date   = $end_date;
		$this->link       = $link;
	}

	/**
	 * @return int
	 */
	public function getIdentifier(){
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getCity(){
		return $this->city;
	}

	/**
	 * @return string
	 */
	public function getState(){
		return $this->state;
	}

	/**
	 * @return string
	 */
	public function getCountry(){
		return $this->country;
	}

	/**
	 * @return string
	 */
	public function getStartDate(){
		return $this->start_date;
	}

	/**
	 * @return string
	 */
	public function getEndDate(){
		return $this->end_date;
	}

	/**
	 * @return string
	 */
	public function getLink(){
		return $this->link;
	}

} 