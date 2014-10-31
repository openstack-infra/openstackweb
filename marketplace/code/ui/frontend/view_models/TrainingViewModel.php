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
 * Class TrainingViewModel
 */
class TrainingViewModel extends ViewableData {

	/**
	 * @var ArrayList
	 */
	private $courses;
	/**
	 * @var ICompany
	 */
	private $company;
	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $description;

	/**
	 * @param int      $id
	 * @param string   $name
	 * @param string   $description
	 * @param ICompany $company
	 */
	public function __construct($id,$name,$description, ICompany $company){
		$this->id          = $id;
		$this->name        = $name;
		$this->description = $description;
		$this->company     = $company;
	}

	/**
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * @return ICompany
	 */
	public function getCompany(){
		return $this->company;
	}

	/**
	 * @param ArrayList $courses
	 */
	public function setCourses(ArrayList $courses){
		$this->courses = $courses;
	}

	/**
	 * @return ArrayList
	 */
	public function getCourses(){
		return $this->courses;
	}
}