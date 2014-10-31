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
 * Class TeamFactory
 */
final class TeamFactory implements ITeamFactory {

	/**
	 * @param array $team_data
	 * @return ITeam
	 */
	public function buildTeam(array $team_data)
	{
		$team = new Team();
		$team->Name        = $team_data['name'];
		$team->CompanyID   = (int)$team_data['company_id'];
		$team->Company     = new Company() ;
		$team->Company->ID = (int)$team_data['company_id'];
		return $team;
	}
} 