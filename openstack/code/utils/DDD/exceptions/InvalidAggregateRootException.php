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
 * Class InvalidAggregateRootException
 */
class InvalidAggregateRootException extends Exception {

	public function __construct($aggregate_root_name, $aggregate_root_id, $aggregate_name, $aggregate_id){
		$message = sprintf('Entity %s (%s) does not belongs to Entity %s (%s)'
			, $aggregate_name
			, $aggregate_id
			, $aggregate_root_name
			, $aggregate_root_id);
		parent::__construct($message);
	}
}