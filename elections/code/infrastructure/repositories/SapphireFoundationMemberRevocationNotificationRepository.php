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
 * Class SapphireFoundationMemberRevocationNotificationRepository
 */
final class SapphireFoundationMemberRevocationNotificationRepository
extends SapphireRepository implements IFoundationMemberRevocationNotificationRepository
{

	public function __construct(){
		parent::__construct(new FoundationMemberRevocationNotification);
	}

	/**
	 * @param int $foundation_member_id
	 * @return IFoundationMemberRevocationNotification
	 */
	public function getByFoundationMember($foundation_member_id)
	{
		$query = new QueryObject(new FoundationMemberRevocationNotification);
		$query->addAddCondition(QueryCriteria::equal('Member.ID',$foundation_member_id));
		return $this->getBy($query);
	}

	/**
	 * @param int $days
	 * @param int $batch_size
	 * @return IFoundationMemberRevocationNotification[]
	 */
	public function getNotificationsSentXDaysAgo($days, $batch_size)
	{
		$query = new QueryObject(new FoundationMemberRevocationNotification);
		$query->addAddCondition(QueryCriteria::equal('Action','None'));
		$today = new DateTime('now',new DateTimeZone("UTC"));
		$query->addAddCondition(QueryCriteria::lowerOrEqual('ADDDATE(SentDate, INTERVAL '.$days.' DAY)', $today->format('Y-m-d H:i:s'),false));
		list($res,$size) = $this->getAll($query,0, $batch_size);
		return $res;
	}

	/**
	 * @param string $hash
	 * @return bool
	 */
	public function existsHash($hash)
	{
		$query = new QueryObject(new FoundationMemberRevocationNotification);
		$query->addAddCondition(QueryCriteria::equal('Hash', $hash));
		return $this->getBy($query) != null;
	}

	/**
	 * @param string $hash
	 * @return IFoundationMemberRevocationNotification
	 */
	public function getByHash($hash)
	{
		$query = new QueryObject(new FoundationMemberRevocationNotification);
		$query->addAddCondition(QueryCriteria::equal('Hash',FoundationMemberRevocationNotification::HashConfirmationToken($hash)));
		return $this->getBy($query);
	}
}