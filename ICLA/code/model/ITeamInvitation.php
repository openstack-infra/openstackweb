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
 * Interface ITeamInvitation
 */
interface ITeamInvitation extends IEntity {

	/**
	 * @return InviteInfoDTO
	 */
	public function getInviteInfo();

	/**
	 * @return bool
	 */
	public function isInviteRegisteredAsUser();

	/**
	 * @return ITeam
	 */
	public function getTeam();

	/**
	 * @return string
	 */
	public function generateConfirmationToken();

	/**
	 * @param string $token
	 * @return bool
	 * @throws InvalidHashInvitationException
	 * @throws InvitationAlreadyConfirmedException
	 */
	public function doConfirmation($token);

	/**
	 * @return ICLAMember
	 */
	public function getMember();

	public function updateInvite(ICLAMember $invite);
}