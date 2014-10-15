<?php

/**
 * Class PublicCloudSapphireRender
 */
final class PublicCloudSapphireRender {

	/**
	 * @var IPublicCloudService
	 */
	private $cloud;

	public function __construct(IPublicCloudService $cloud){
		$this->cloud = $cloud;
	}

	public function draw(){
		Requirements::css("marketplace/code/ui/frontend/css/marketplace.css");
		Requirements::javascript(Director::protocol() . "maps.googleapis.com/maps/api/js?sensor=false");
		Requirements::javascript("marketplace/code/ui/admin/js/utils.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/markerclusterer.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/oms.min.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/infobubble-compiled.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/google.maps.jquery.js");

		Requirements::javascript("marketplace/code/ui/frontend/js/openstack.implementation.capabilities.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/cloud.page.js");
		return Controller::curr()->Customise($this->cloud)->renderWith(array('CloudsDirectoryPage_cloud', 'PublicCloudsDirectoryPage', 'MarketPlacePage'));
	}
} 