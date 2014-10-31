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
 * Interface IFoundationMember
 */
interface IFoundationMember extends IEntity {

	const FoundationMemberGroupSlug = 'foundation-members';
	const CommunityMemberGroupSlug = 'community-members';
	/**
	 * @return void
	 */
	public function convert2SiteUser();

	/**
	 * @return bool
	 */
	public function isFoundationMember();

	/**
	 * @return void
	 */
	public function upgradeToFoundationMember();

	/**
	 * @param int $latest_election_id
	 * @return bool
	 */
	public function hasPendingRevocationNotifications($latest_election_id);

	public function resign();

} 