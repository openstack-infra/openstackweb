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
 * Interface ITeam
 */
interface ITeam extends IEntity {
	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 * @return void
	 */
	public function updateName($name);

	/**
	 * @return ICLAMember[]
	 */
	public function getMembers();

	/**
	 * @param ICLAMember $member
	 * @return void
	 */
	public function addMember(ICLAMember $member);

	/**
	 * @param ICLAMember $member
	 * @return void
	 */
	public function removeMember(ICLAMember $member);

	/**
	 * @return ITeamInvitation[]
	 */
	public function getInvitations();

	/**
	 * @return ITeamInvitation[]
	 */
	public function getUnconfirmedInvitations();

	/**
	 * @return ICLACompany
	 */
	public function getCompany();

	/**
	 * @param ICLAMember $member
	 * @return bool
	 */
	public function isInvite(ICLAMember $member);

	/**
	 * @param ICLAMember $member
	 * @return bool
	 */
	public function isMember(ICLAMember $member);

	/**
	 * @param ITeamInvitation $invitation
	 * @return void
	 */
	public function removeInvitation(ITeamInvitation $invitation);

	public function clearMembers();

	public function clearInvitations();

} 