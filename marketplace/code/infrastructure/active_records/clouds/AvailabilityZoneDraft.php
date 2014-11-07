<?php
/**
 * Class AvailabilityZoneDraft
 */
class AvailabilityZoneDraft
	extends DataObject
	implements IAvailabilityZone
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Name' => 'Varchar',
	);

	static $has_one = array(
		'Location' => 'DataCenterLocationDraft',
	);

	static $indexes = array(
		'Location_Name' => array('type'=>'unique', 'value'=>'LocationID,Name'),
	);

	/**
	 * @return mixed|string
	 */
	public function getName()
	{
		return $this->getField('Name');
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->setField('Name',$name);
	}

	/**
	 * @return DataObject|IDataCenterLocation
	 */
	public function getLocation()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Location','AvailabilityZones')->getTarget();
	}

	/**
	 * @param IDataCenterLocation $location
	 */
	public function setLocation(IDataCenterLocation $location)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Location','AvailabilityZones')->setTarget($location);
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}
}