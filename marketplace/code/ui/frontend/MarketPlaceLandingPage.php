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
 * Class MarketPlaceLandingPage
 */
final class MarketPlaceLandingPage extends MarketPlacePage {
	static $allowed_children = array("MarketPlaceDirectoryPage");
}
/**
 * Class MarketPlaceLandingPage_Controller
 */
final class MarketPlaceLandingPage_Controller extends MarketPlacePage_Controller {

	/**
	 * @var IOpenStackImplementationRepository
	 */
	private $public_cloud_repository;

	function init(){
		parent::init();
		Requirements::css("marketplace/code/ui/frontend/css/marketplace.landing.css");
		Requirements::javascript(Director::protocol()."maps.googleapis.com/maps/api/js?sensor=false");
		Requirements::javascript("marketplace/code/ui/frontend/js/markerclusterer.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/oms.min.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/infobubble-compiled.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/google.maps.jquery.js");
		Requirements::javascript("marketplace/code/ui/frontend/js/landing.page.js");
		$this->public_cloud_repository   = new SapphirePublicCloudRepository;
	}

	public function getDataCenterLocationsJson(){
		$locations = array();
		$query = new QueryObject;
		$query->addAddCondition(QueryCriteria::equal("Active",true));
		list($list,$size) = $this->public_cloud_repository->getAll($query,0,1000);
		foreach($list as $public_cloud){
			foreach($public_cloud->getDataCentersLocations() as $location){
				$json_data = array();
				$json_data['color']        = $location->getDataCenterRegion()->getColor();
				$json_data['country']      = Geoip::countryCode2name($location->getCountry());
				$json_data['city']         = $location->getCity();
				$json_data['lat']          = $location->getLat();
				$json_data['lng']          = $location->getLng();
				$json_data['product_name'] = $public_cloud->getName();
				$json_data['product_url']  = $this->buildCloudLink($public_cloud->getCompany()->URLSegment.'/'. strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $public_cloud->getName()))));
				$json_data['owner']        = $public_cloud->getCompany()->getName();
				array_push($locations,$json_data);
			}
		}
		return json_encode($locations);
	}


	private function buildCloudLink($route){
		return $this->getMarketPlaceTypeLink(3).$route;
	}

}