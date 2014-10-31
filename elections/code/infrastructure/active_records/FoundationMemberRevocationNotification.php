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
 * Class FoundationMemberRevocationNotification
 */
final class FoundationMemberRevocationNotification
	extends DataObject
	implements IFoundationMemberRevocationNotification {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Action'       => "Enum('None, Renew, Revoked, Resign','None')",
		'ActionDate'   => 'SS_Datetime',
		'SentDate'     => 'SS_Datetime',
		'Hash'         => 'Text',
	);

	static $has_one = array(
		'LastElection' => 'Election',
		'Recipient'    => 'Member',
	);

	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->setField('SentDate',gmdate('Y-m-d H:i:s'));
	}


	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @return string
	 */
	public function action()
	{
		return (string)$this->getField('Action');
	}

	/**
	 * @return DateTime
	 */
	public function actionDate()
	{
		if($this->isExpired() && $this->action()=='None')
			return $this->expirationDate();

		$date = $this->getField('ActionDate');
		return new DateTime($date);
	}

	/**
	 * @return DateTime
	 */
	public function sentDate()
	{
		$date = $this->getField('SentDate');
		return new DateTime($date);
	}

	/**
	 * @return bool
	 */
	public function isExpired()
	{
		$date      = new DateTime('now',new DateTimeZone("UTC"));
		$date      = $date->sub(new DateInterval('P'.IFoundationMemberRevocationNotification::DaysBeforeRevocation.'D'));
		$sent_date = $this->sentDate();
		return $sent_date <= $date;
	}

	/**
	 * @param IElection $latest_election
	 * @return void
	 */
	public function renew(IElection $latest_election)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'LastElection')->setTarget($latest_election);
		$this->setField('Action','Renew');
		$this->setField('ActionDate',gmdate('Y-m-d H:i:s'));
	}

	/**
	 * @return void
	 */
	public function revoke()
	{
		$member = $this->recipient();
		$member->convert2SiteUser();
		$this->setField('Action','Revoked');
		$this->setField('ActionDate',gmdate('Y-m-d H:i:s'));
	}

	public function resign(){
		$member = $this->recipient();
		$member->resign();
		$this->setField('Action','Resign');
		$this->setField('ActionDate',gmdate('Y-m-d H:i:s'));
	}

	/**
	 * @return IFoundationMember
	 */
	public function recipient()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Recipient')->getTarget();
	}

	/**
	 * @return string
	 */
	public function hash()
	{
		return (string)$this->getField('Hash');
	}

	/**
	 * @return string
	 */
	public function generateHash()
	{
		$generator = new RandomGenerator();
		$token     = $generator->randomToken();
		$hash      = self::HashConfirmationToken($token);
		$this->setField('Hash',$hash);
		return $token;
	}

	public static function HashConfirmationToken($token){
		return md5($token);
	}

	/**
	 * @return IElection
	 */
	public function latestElection()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'LastElection')->getTarget();
	}

	/**
	 * @return int
	 */
	public function remainingDays()	{
		$send_date = $this->sentDate();
		$void_date = $send_date->add(new DateInterval('P'.IFoundationMemberRevocationNotification::DaysBeforeRevocation.'D'));
		$diff      =  (int)$void_date->diff($send_date)->format("%a");
		return IFoundationMemberRevocationNotification::DaysBeforeRevocation - $diff;
	}

	/**
	 * @return DateTime
	 */
	public function expirationDate()
	{
		$sent_date = $this->sentDate();
		$void_date = $sent_date->add(new DateInterval('P'.IFoundationMemberRevocationNotification::DaysBeforeRevocation.'D'));
		return $void_date;
	}

	/**
	 * @return bool
	 */
	public function isValid()
	{
		if($this->isExpired()) return false;
		$action = $this->getField('Action');
		return $action == 'None';
	}
}