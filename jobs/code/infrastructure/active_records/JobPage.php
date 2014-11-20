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
 * Class JobPage
 */
class JobPage
	extends Page
	implements IJob {


	static $db = array(
		'JobPostedDate'         => 'Date',
		'ExpirationDate'        => 'Date',
		'JobCompany'            => 'Text',
		'JobMoreInfoLink'       => 'Text',
		'JobLocation'           => 'Text',
		'FoundationJob'         => 'Boolean',
		'Active'                => 'Boolean',
		'JobInstructions2Apply' => 'HTMLText',
		'LocationType'          =>  "Enum('N/A, Remote, Various', 'N/A')",
	);


	static $has_many = array(
		'Locations'  => 'JobLocation',
	);

	private static $defaults = array(
		"Active" => 1,
	);

	private static $has_one = array();

	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		if(empty($this->ExpirationDate)){
			$expiration_date = new DateTime;
			$expiration_date->add(new DateInterval('P2M'));
			$this->ExpirationDate = $expiration_date->format('Y-m-d');
		}
	}

	/**
	 * @return string
	 */
	public function getFormattedLocation(){
		if(!empty($this->LocationType)){
			switch($this->LocationType){
				case 'Various':{
					$res = '';
					foreach($this->locations() as $location){
						$str_location = $location->city();
						$state = $location->state();
						if(!empty($state))
							$str_location .= ', '.$state;
						$str_location .= ', '.$location->country();
						$res .= $str_location.'<BR>';
					}
					return $res;
				}
				break;
				case 'N/A':
					if(!emptY($this->JobLocation)){
						return $this->JobLocation;
					}
					return $this->LocationType;
					break;
				default:
					return $this->LocationType;
					break;
			}

		}
	}

	public function isExpired(){
		if(!empty($this->JobExpired)){
			$expiration_date = new DateTime($this->ExpirationDate);
			$now             = new DateTime;
			return $expiration_date <  $now;
		}
		return false;
	}

	public function RecentJob() {
		//check if the job posting is less than two weeks old
		return $this->JobPostedDate > date('Y-m-d H:i:s',strtotime('-2 weeks'));
	}

	function getCMSFields() {
		$fields = parent::getCMSFields();
		// the date field is added in a bit more complex manner so it can have the dropdown date picker
		$JobPostedDate = new DateField('JobPostedDate','Date Posted');
		$JobPostedDate->setConfig('showcalendar', true);
		$JobPostedDate->setConfig('showdropdown', true);

		$fields->addFieldToTab('Root.Main', $JobPostedDate, 'Content');
		$fields->addFieldToTab('Root.Main', new DateField_Disabled('ExpirationDate','Expiration Date'), 'Content');
		$fields->addFieldToTab('Root.Main', new TextField('JobMoreInfoLink','More Information About This Job (URL)'), 'Content');
		$fields->addFieldToTab('Root.Main', new TextField('JobCompany','Company'), 'Content');
		$fields->addFieldToTab('Root.Main', new HtmlEditorField('JobInstructions2Apply','Job Instructions to Apply'), 'Content');
		$fields->addFieldToTab('Root.Main', new CheckboxField ('FoundationJob','This is a job with the OpenStack Foundation'));
		$fields->addFieldToTab('Root.Main', new CheckboxField ('Active','Is Active?'));
		$fields->addFieldToTab('Root.Main', new DropdownField('LocationType','Location Type',singleton('JobPage')->dbObject('LocationType')->enumValues()));
		// remove unneeded fields
		$fields->removeFieldFromTab("Root.Main","MenuTitle");


		// rename fields
		$fields->renameField("Content", "Job Description");
		$fields->renameField("Title", "Job Title");
		return $fields;
	}

	/**
	 * @return int
	 */
	public function getIdentifier()	{
		return (int)$this->getField('ID');
	}

	/**
	 * @return void
	 */
	public function deactivate(){
		$this->Active = 0;
	}


	/**
	 * @return IJobLocation[]
	 */
	public function locations()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Locations')->toArray();
	}

	public function addLocation(IJobLocation $location)
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Locations')->add($location);
	}


	/**
	 * @return void
	 */
	public function clearLocations()
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Locations')->removeAll();
	}
}