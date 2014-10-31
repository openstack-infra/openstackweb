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
 * Class SponsorInfo
 */
final class SponsorInfo {
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $url;

	/**
	 * @param string $name
	 * @param string $url
	 */
	public function __construct($name, $url){
		$this->name = $name;
		$this->url  = $url;
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
	public function getUrl(){
		return $this->url;
	}
} 