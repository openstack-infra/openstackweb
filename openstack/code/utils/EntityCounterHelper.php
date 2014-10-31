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
 * Class EntityCounterHelper
 */
final class EntityCounterHelper {
	/**
	 * @var EntityCounterHelper
	 */
	private static $instance;

	private function __construct(){}

	private function __clone(){}

	/**
	 * @return EntityCounterHelper
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance = new EntityCounterHelper();
		}
		return self::$instance;
	}

	public function EntityCount($entity_name, callable $payload_function=null){
		$cache  = SS_Cache::factory('cache_entity_count');
		$result = unserialize($cache->load('var_'.$entity_name));
		if(!$result){
			if($payload_function==null){
				$sqlQuery = new SQLQuery(
					"COUNT(ID)",
					array($entity_name)
				);
				$result = $sqlQuery->execute()->value();
			}
			else{
				$result = $payload_function();
			}
			$cache->save(serialize($result), 'var_'.$entity_name);
		}

		if(Director::is_ajax()){
			return json_encode($result);
		}
		return $result;
	}
} 