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
 * Class MarketPlacePage
 */
class MarketPlacePage extends Page {
}
/**
 * Class MarketPlacePage_Controller
 */
class MarketPlacePage_Controller extends Page_Controller {

	function init(){
		parent::init();
		Requirements::css("marketplace/code/ui/frontend/css/marketplace.css");
	}

	public function getDirectoryPages(){
		return MarketPlaceDirectoryPage::get();
	}

	public function canViewTab($type){
		$class = '';
		switch($type){
			case 1:
				$class = 'TrainingDirectoryPage';
				break;
			case 2:
				$class = 'DistributionsDirectoryPage';
				break;
			case 3:
				$class = 'PublicCloudsDirectoryPage';
				break;
			case 4:
				$class = 'ConsultantsDirectoryPage';
				break;
			case 5:
				$class = 'MarketPlaceDriverPage';
				break;
			case 6:
				$class = 'PrivateCloudsDirectoryPage';
				break;
		}
		if(!empty($class)){
			$page  = $class::get()->first();
			if($page)
			{
				$view_type = $page->CanViewType;
				switch($view_type){
					case 'LoggedInUsers':{
						$member = Member::currentUser();
						if(!$member) return false;
						return $member->isAdmin();
					}
					break;
				}
				return true;
			}
			return false;
		}
		return false;
	}

	public function getMarketPlaceTypeLink($type){
		$class = '';
		$link = '#';
		switch($type){
			case 1:
				$class = 'TrainingDirectoryPage';
				break;
			case 2:
				$class = 'DistributionsDirectoryPage';
				break;
			case 3:
				$class = 'PublicCloudsDirectoryPage';
				break;
			case 4:
				$class = 'ConsultantsDirectoryPage';
				break;
			case 5:
				$class = 'MarketPlaceDriverPage';
				break;
			case 6:
				$class = 'PrivateCloudsDirectoryPage';
				break;
		}
		if(!empty($class)){
			$page  = $class::get()->first();
			if($page)
				$link  = $page->Link();
		}
		return $link;
	}
}