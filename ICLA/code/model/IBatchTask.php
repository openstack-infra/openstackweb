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
/***
 * Interface IBatchTask
 */
interface IBatchTask extends IEntity {
	/***
	 * @return string
	 */
	public function name();
	/***
	 * @return int
	 */
	public function lastRecordProcessed();

	/**
	 * @return string
	 */
	public function lastResponse();

	/**
	 * @return DateTime
	 */
	public function lastResponseDate();

	/**
	 * @return int
	 */
	public function totalRecords();

	/**
	 * @param string $response
	 * @return void
	 */
	public function updateResponse($response);

	/**
	 * @return void
	 */
	public function updateLastRecord();

	/**
	 * @param int $total_qty
	 * @return void
	 */
	public function initialize($total_qty);

} 