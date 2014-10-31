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
 * Class EventDuration
 */
final class EventDuration {
	/**
	 * @var DateTime
	 */
    private $start_date;
	/**
	 * @var DateTime
	 */
	private $end_date;

	/**
	 * @param DateTime $start_date
	 * @param DateTime $end_date
	 */
	public function __construct(DateTime $start_date,DateTime $end_date){
		$this->start_date = $start_date;
		$this->end_date   = $end_date;
	}

	/**
	 * @return DateTime
	 */
	public function getStartDate(){
		return $this->start_date;
	}

	/**
	 * @return DateTime
	 */
	public function getEndDate(){
		return $this->end_date;
	}
} 