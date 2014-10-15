<?php

/**
 * Class ConsultantSapphireRender
 */
final class ConsultantSapphireRender {

	/**
	 * @var IConsultant
	 */
	private $consultant;

	/**
	 * @var IEntityRepository
	 */
	private $region_repository;

	public function __construct(IConsultant $consultant){
		$this->consultant        = $consultant;
		$this->region_repository = new SapphireRegionRepository;
	}

	public function draw(){
		Requirements::css("marketplace/code/ui/frontend/css/marketplace.css");
		Requirements::javascript("marketplace/code/ui/frontend/js/consultant.page.js");
		$services = $this->consultant->getServicesOffered();
		$unique_services = array();
		$unique_regions = array();
		foreach ($services as $service) {
			if (!array_key_exists($service->getType(), $unique_services))
				$unique_services[$service->getType()] = $service;
			if (!array_key_exists($service->getRegionID(), $unique_regions)) {
				$region = $this->region_repository->getById($service->getRegionID());
				$unique_regions[$service->getRegionID()] = $region;
			}
		}
		return Controller::curr()->Customise(
			array(
				'Consultant' => $this->consultant,
				'Services' => new ArrayList(array_values($unique_services)),
				'Regions' => new ArrayList(array_values($unique_regions)),
			)
		)->renderWith(array('ConsultantsDirectoryPage_consultant', 'ConsultantsDirectoryPage', 'MarketPlacePage'));
	}
} 