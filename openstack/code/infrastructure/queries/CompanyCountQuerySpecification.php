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
 * Class CompanyCountQuerySpecification
 */
final class CompanyCountQuerySpecification implements IQuerySpecification {

	/**
	 * @var string
	 */
	private $member_level;

	/**
	 * @var string
	 */
	private $country;

	/**
	 * @param string $member_level
	 * @param string $country
	 */
	public function __construct($member_level = null, $country = null){
		$this->member_level = $member_level;
		$this->country = $country;
	}

	/**
	 * @return array
	 */
	public function getSpecificationParams()
	{
		return array($this->member_level, $this->country );
	}
}