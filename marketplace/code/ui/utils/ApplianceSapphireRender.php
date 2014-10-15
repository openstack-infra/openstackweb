<?php

/**
 * Class ApplianceSapphireRender
 */
final class ApplianceSapphireRender {

	/**
	 * @var IAppliance
	 */
	private $appliance;

	public function __construct(IAppliance $appliance){
		$this->appliance = $appliance;
	}

	public function draw(){
		Requirements::javascript("marketplace/code/ui/frontend/js/openstack.implementation.capabilities.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/implementation.page.js");
		Requirements::css("marketplace/code/ui/frontend/css/marketplace.css");
		return Controller::curr()->Customise($this->appliance)->renderWith(array('DistributionsDirectoryPage_implementation', 'DistributionsDirectoryPage', 'MarketPlacePage'));
	}
} 