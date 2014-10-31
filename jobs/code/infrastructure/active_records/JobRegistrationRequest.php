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
 * Class JobRegistrationRequest
 */
final class JobRegistrationRequest
extends DataObject
implements IJobRegistrationRequest {

	private static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	private static $db = array(
		//main info
		'Title'               => 'Varchar(100)',
		'Url'                 => 'Varchar(255)',
		'CompanyName'         => 'Varchar(255)',
		'Description'         => 'HTMLText',
		'Instructions2Apply'  => 'HTMLText',
		'ExpirationDate'      => 'Date',
		//contact
		'PointOfContactName'  => 'Varchar(100)',
		'PointOfContactEmail' => 'Varchar(100)',
		//admin fields
		'PostDate'            => 'SS_Datetime',
		'isPosted'            => 'Boolean',
		'isRejected'          => 'Boolean',
		'LocationType'        =>  "Enum('N/A, Remote, Various', 'N/A')",
		//old location fields (not used anymore)
		'City'                => 'Varchar(100)',
		'State'               => 'Varchar(50)',
		'Country'             => 'Varchar(50)',
	);

	private static $has_one = array(
		'Member'  => 'Member',
		'Company' => 'Company',
	);


	static $has_many = array(
		'Locations'  => 'JobLocation',
	);


	static $indexes = array(

	);

	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		if(empty($this->PostDate)){
			$this->PostDate = SS_Datetime::now()->Rfc2822();
		}
		if(empty($this->ExpirationDate)){
			$expiration_date = new DateTime;
			$expiration_date->add(new DateInterval('P2M'));
			$this->ExpirationDate = $expiration_date->format('Y-m-d');
		}
	}
	/**
	 * @return int
	 */
	public function getIdentifier()	{
		return (int)$this->getField('ID');
	}

	public function markAsPosted() {
		if($this->isRejected)
			throw new EntityValidationException(array(array('message'=>'This job has already been rejected!.')));

		if($this->isPosted)
			throw new EntityValidationException(array(array('message'=>'This job has already been approved!.')));

		$this->isPosted = true;
	}

	public function markAsRejected() {
		if($this->isPosted)
			throw new EntityValidationException(array(array('message'=>'This job has already been approved!.')));

		if($this->isRejected)
			throw new EntityValidationException(array(array('message'=>'This job has already been rejected!.')));

		$this->isRejected = true;
	}

	/**
	 * @param JobPointOfContact $point_of_contact
	 * @return void
	 */
	function registerPointOfContact(JobPointOfContact $point_of_contact) {
		$this->PointOfContactName  = $point_of_contact->getName();
		$this->PointOfContactEmail = $point_of_contact->getEmail();
	}

	/**
	 * @param Member $user
	 * @return void
	 */
	public function registerUser(Member $user){
		$this->MemberID = $user->ID;
	}

	/**
	 * @param JobMainInfo $info
	 * @return void
	 */
	public function registerMainInfo(JobMainInfo $info)	{
		$this->Title              = $info->getTitle();
		$this->Url                = $info->getUrl();
		$this->Description        = $info->getDescription();
		$this->Instructions2Apply = $info->getInstructions();
		$this->LocationType       = $info->getLocationType();
		if($info->getCompany()->ID > 0)
			$this->CompanyID          = $info->getCompany()->ID;
		else
			$this->CompanyName        = $info->getCompany()->Name;
	}

	/**
	 * @param IJobLocation $location
	 * @return mixed
	 */
	public function registerLocation(IJobLocation $location){

		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Locations')->add($location);
	}

	/**
	 * @return JobPointOfContact
	 */
	function getPointOfContact()
	{
		return new JobPointOfContact($this->PointOfContactName, $this->PointOfContactEmail);
	}

	/**
	 * @return JobMainInfo
	 */
	function getMainInfo()
	{
		$company = $this->Company();
		$company->Name = $this->CompanyName;
		return new JobMainInfo($this->Title, $company,$this->Url, $this->Description, $this->Instructions2Apply, $this->LocationType, new DateTime($this->ExpirationDate));
	}

	/**
	 * @return IJobLocation[]
	 */
	public function getLocations()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Locations')->toArray();
	}

	/**
	 * @return void
	 */
	public function clearLocations()
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Locations')->removeAll();
	}
}