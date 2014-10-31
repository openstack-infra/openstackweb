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
 * Class EventRegistrationRequest
 */
final class EventRegistrationRequest
	extends DataObject
	implements IEventRegistrationRequest
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Title'               => 'Varchar(35)',
		'Url'                 => 'Varchar(255)',
		'Label'               => 'Varchar(50)',
		'City'                => 'Varchar(100)',
		'State'               => 'Varchar(50)',
		'Country'             => 'Varchar(50)',
		'StartDate'           => 'Date',
		'EndDate'             => 'Date',
		'PostDate'            => 'SS_Datetime',
		'Sponsor'             => 'Text',
		'SponsorLogoUrl'      => 'Varchar(255)',
		'Lat'                 => 'Decimal',
		'Lng'                 => 'Decimal',
		'isPosted'            => 'Boolean',
		'PointOfContactName'  => 'Varchar(100)',
		'PointOfContactEmail' => 'Varchar(100)',
		'isRejected'          => 'Boolean',
	);

	static $has_one = array(
		'Member' => 'Member',
	);

	static $indexes = array(
	);

	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->PostDate = SS_Datetime::now()->Rfc2822();
	}
	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @param EventMainInfo $info
	 * @return void
	 */
	function registerMainInfo(EventMainInfo $info)
	{
		$this->Title = $info->getTitle();
		$this->Url   = $info->getUrl();
		$this->Label = $info->getLabel();
	}

	/**
	 * @param EventLocation $location
	 * @return void
	 */
	public function registerLocation(EventLocation $location)
	{
		$this->City    = $location->getCity();
		if($location->getState())
			$this->State   = $location->getState();

		$this->Country  = $location->getCountry();
		list($lat,$lng) = $location->getCoordinates();
		$this->Lat      = $lat;
		$this->Lng      = $lng;
	}

	/**
	 * @param EventDuration $duration
	 * @return void
	 */
	public function registerDuration(EventDuration $duration)
	{
		$this->StartDate = $duration->getStartDate()->format('Y-m-d');
		$this->EndDate   = $duration->getEndDate()->format('Y-m-d ');
	}

	/**
	 * @param Member $user
	 * @return void
	 */
	public function registerUser(Member $user)
	{
		$this->MemberID = $user->ID;
	}

	/**
	 * @return boolean
	 */
	public function hasRegisteredUser()
	{
		return $this->Member()!==false;
	}

	/**
	 * @return Member
	 */
	public function getRegisteredUser()
	{
		return $this->Member();
	}

	/**
	 * @param SponsorInfo $sponsor_info
	 * @return void
	 */
	public function registerSponsor(SponsorInfo $sponsor_info)
	{
		$this->Sponsor        = $sponsor_info->getName();
		$this->SponsorLogoUrl = $sponsor_info->getUrl();
	}

	/**
	 * @throws EntityValidationException
	 */
	public function markAsPosted()
	{
		if($this->isRejected)
			throw new EntityValidationException(array(array('message'=>'request already rejected!')));

		if($this->isPosted)
			throw new EntityValidationException(array(array('message'=>'event request already posted!.')));

		$this->isPosted = true;
	}

	/**
	 * @throws EntityValidationException
	 */
	public function markAsRejected(){
		if($this->isPosted)
			throw new EntityValidationException(array(array('message'=>'event request already posted!.')));

		if($this->isRejected)
			throw new EntityValidationException(array(array('message'=>'request already rejected!')));

		$this->isRejected = true;
	}

	/**
	 * @param EventPointOfContact $point_of_contact
	 * @return void
	 */
	function registerPointOfContact(EventPointOfContact $point_of_contact)
	{
		$this->PointOfContactName  = $point_of_contact->getName();
		$this->PointOfContactEmail = $point_of_contact->getEmail();
	}

	/**
	 * @return EventPointOfContact
	 */
	function getPointOfContact()
	{
		return new EventPointOfContact($this->PointOfContactName, $this->PointOfContactEmail);
	}

	/**
	 * @return EventMainInfo
	 */
	function getMainInfo()
	{
		return new EventMainInfo($this->Title, $this->Url, $this->Label);
	}

	/**
	 * @return EventLocation
	 */
	public function getLocation()
	{
		return new EventLocation($this->City, $this->State,$this->Country);
	}

	/**
	 * @return EventDuration
	 */
	public function getDuration()
	{
		return new EventDuration(new DateTime($this->StartDate), new DateTime($this->EndDate));
	}
}