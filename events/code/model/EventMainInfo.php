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
 * Class EventMainInfo
 */
final class EventMainInfo {
	/**
	 * @var string
	 */
	private $title;
	/**
	 * @var string
	 */
	private $url;
	/**
	 * @var string
	 */
	private $label;

	/**
	 * @param string $title
	 * @param string $url
	 * @param string $label
	 */
	public function __construct($title,$url,$label){
		$this->title = $title;
		$this->url   = $url;
		$this->label = $label;
	}

	public function getTitle(){
		return $this->title;
	}

	public function getUrl(){
		return $this->url;
	}

	public function getLabel(){
		return $this->label;
	}
} 