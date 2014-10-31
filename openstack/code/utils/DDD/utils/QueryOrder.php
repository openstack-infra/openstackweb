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
 * Class QueryOrder
 */
final class QueryOrder {

	private $field;
	private $dir;

	private function __construct($field,$dir){
		$this->field    = $field;
		$this->dir = $dir;
	}

	public function getField(){
		return $this->field;
	}

	public function getDir(){
		return $this->dir;
	}

	public static function asc($field){
		return new QueryOrder($field,'ASC');
	}

	public static function desc($field){
		return new QueryOrder($field,'DESC');
	}

	public function __toString()
	{
		$field = $this->field;
		if(strpos($field,'.')){
			$parts = explode('.',$field);
			$parsed_field = '';
			foreach($parts as $part){
				$parsed_field .= sprintf('`%s`.',$part);
			}
			$field = trim($parsed_field,'.');
		}
		else{
			$field = sprintf('`%s`',$field);
		}
		return sprintf(" %s %s ",$field,$this->dir);
	}
}