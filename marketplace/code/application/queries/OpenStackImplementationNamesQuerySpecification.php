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
 * Class OpenStackImplementationNamesQuerySpecification
 */
final class OpenStackImplementationNamesQuerySpecification
	implements IOpenStackImplementationNamesQuerySpecification{

	private $name_pattern;
	public function __construct($name_pattern){
		$this->name_pattern = $name_pattern;
	}

	/**
	 * @return string
	 */
	public function getNamePatternToSearch()
	{
		return $this->name_pattern;
	}

	/**
	 * @return array
	 */
	public function getSpecificationParams()
	{
		return array('name_pattern'=>$this->name_pattern);
	}
}