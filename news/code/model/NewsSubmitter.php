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
 * Class NewsSubmitter
 */
final class NewsSubmitter {
	/**
	 * @var string
	 */
    private $first_name;
	/**
	 * @var string
	 */
	private $last_name;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $company;
    /**
     * @var integer
     */
    private $phone;

	/**
	 * @param string $first_name
	 * @param string $last_name
     * @param string $email
     * @param string $company
     * @param integer $phone
	 */
	public function __construct($first_name, $last_name, $email, $company, $phone){
		$this->first_name = $first_name;
		$this->last_name  = $last_name;
        $this->email      = $email;
        $this->company    = $company;
        $this->phone      = $phone;
	}

	/**
	 * @return string
	 */
	public function getFirstName(){
		return $this->first_name;
	}

	/**
	 * @return string
	 */
	public function getLastName(){
		return $this->last_name;
	}

    /**
     * @return string
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCompany(){
        return $this->company;
    }

    /**
     * @return phone
     */
    public function getPhone(){
        return $this->phone;
    }
} 