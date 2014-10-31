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
 * Class RevocationNotificationFactory
 */
final class RevocationNotificationFactory implements IFoundationMemberRevocationNotificationFactory {

	/**
	 * @param IFoundationMember $foundation_member
	 * @param IElection         $last_election
	 * @return IFoundationMemberRevocationNotification
	 */
	public function build(IFoundationMember $foundation_member, IElection $last_election)
	{
		$notification                  = new FoundationMemberRevocationNotification;
		$notification->RecipientID     = $foundation_member->getIdentifier();
		$notification->LastElectionID  = $last_election->getIdentifier();
		return $notification;
	}
}