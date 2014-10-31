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
 * Class ElectionFactory
 */
final class ElectionFactory implements IElectionFactory {

	/**
	 * @param int    $id
	 * @param DateTime $open_date
	 * @param DateTime $end_date
	 * @return IElection
	 */
	public function build($id, DateTime $open_date, DateTime $end_date)
	{
		$election = new Election();
		$election->ID             = $id;
		$election->ElectionsOpen  = $open_date->format('Y-m-d');
		$election->ElectionsClose = $end_date->format('Y-m-d');
		return $election;
	}
}