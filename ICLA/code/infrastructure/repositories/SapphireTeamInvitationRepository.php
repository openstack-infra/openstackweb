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
 * Class SapphireTeamInvitationRepository
 */
final class SapphireTeamInvitationRepository
	extends SapphireRepository
	implements ITeamInvitationRepository
{

	public function __construct(){
		parent::__construct(new TeamInvitation);
	}

	/**
	 * @param string $token
	 * @return bool
	 */
	public function existsConfirmationToken($token)
	{
		$query = new QueryObject;
		$query->addAddCondition(QueryCriteria::equal('ConfirmationHash',TeamInvitation::HashConfirmationToken($token)));
		return  !is_null( $this->getBy($query));
	}

	/**
	 * @param string $token
	 * @return ITeamInvitation
	 */
	public function findByConfirmationToken($token)
	{
		$query = new QueryObject;
		$query->addAddCondition(QueryCriteria::equal('ConfirmationHash',TeamInvitation::HashConfirmationToken($token)));
		return $this->getBy($query);
	}

	/**
	 * @param string $email
	 * @param bool $all
	 * @return ITeamInvitation[]
	 */
	public function findByInviteEmail($email, $all = false)
	{
		$query = new QueryObject;
		$query->addAddCondition(QueryCriteria::equal('Email',$email));
		if(!$all)
		$query->addAddCondition(QueryCriteria::isNull('ConfirmationHash'));
		list($res, $size) =  $this->getAll($query,0,1000);
		return $res;
	}

	/**
	 * @param string $email
	 * @param ITeam $team
	 * @return ITeamInvitation
	 */
	public function findByInviteEmailAndTeam($email, ITeam $team){

		$query = new QueryObject;
		$query->addAddCondition(QueryCriteria::equal('Email',$email));
		$query->addAddCondition(QueryCriteria::equal('TeamID',$team->getIdentifier()));
		return $this->getBy($query);
	}
}