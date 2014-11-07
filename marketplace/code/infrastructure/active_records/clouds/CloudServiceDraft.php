<?php

/**
 * Class CloudServiceDraft
 */
class CloudServiceDraft extends OpenStackImplementationDraft {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $has_many = array(
		'DataCenters'       => 'DataCenterLocationDraft',
		//@override
		'Capabilities'      => 'CloudServiceOfferedDraft',
		'DataCenterRegions' => 'DataCenterRegionDraft',
	);

	/**
	 * @return IDataCenterLocation[]
	 */
	public function getDataCentersLocations()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'DataCenters')->toArray();
	}

	/**
	 * @param IDataCenterLocation $data_center_location
	 * @throws EntityValidationException
	 */
	public function addDataCenterLocation(IDataCenterLocation $data_center_location)
	{
		$data_centers = AssociationFactory::getInstance()->getOne2ManyAssociation($this,'DataCenters');
		foreach($data_centers->toArray() as $location){
			if($data_center_location->getCountry() == $location->getCountry() && $data_center_location->getCity() == $location->getCity())
				throw new EntityValidationException(array( array('message'=>sprintf('DataCenter Location ( %s - %s) already exists!.',$location->getCity(),$location->getCountry()))));
		}
		$data_centers->add($data_center_location);
	}

	/**
	 * @return void
	 */
	public function clearDataCentersLocations()
	{
		$locations = AssociationFactory::getInstance()->getOne2ManyAssociation($this,'DataCenters');
		foreach($locations as $location){
			$location->clearAvailabilityZones();
		}
		$locations->removeAll();
	}

	/**
	 * @override
	 */
	public function clearCapabilities()
	{
		$services = AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Capabilities');
		foreach($services as $service){
			$service->clearPricingSchemas();
		}
		$services->removeAll();
	}

	/**
	 * @return IDataCenterRegion[]
	 */
	public function getDataCenterRegions()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'DataCenterRegions')->toArray();
	}

	/**
	 * @param IDataCenterRegion $region
	 * @return void
	 */
	public function addDataCenterRegion(IDataCenterRegion $region)
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'DataCenterRegions')->add($region);
	}

	/**
	 * @return void
	 */
	public function clearDataCenterRegions()
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'DataCenterRegions')->removeAll();
	}

	/**
	 * @param string $region_slug
	 * @return IDataCenterRegion
	 */
	public function getDataCenterRegion($region_slug)
	{
		$regions = $this->getDataCenterRegions();
		foreach($regions as $region){
			$current_slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $region->getName()));
			if($region_slug===$current_slug) return $region;
		}
		return false;
	}
} 