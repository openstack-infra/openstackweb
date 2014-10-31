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
	class ProductPage extends Page {
		static $db = array(
		);
		static $has_one = array(
	     );
		static $has_many = array(
			'Features' => 'Feature'
		);
		
		
		function getCMSFields() {
			$fields = parent::getCMSFields();
			$featureGroup = new GridField('Features','Features',$this->Features());
			$fields->addFieldToTab('Root.Features',$featureGroup);
			return $fields;
		}
				
		
	}

	class ProductPage_Controller extends Page_Controller {

		function init() {
			parent::init();
		}

		
		function FeatureList() {
			$FeatureQuery = "Roadmap = FALSE AND ProductPageID =".$this->ID;
			$FeatureList = Feature::get()->where($FeatureQuery);
			return $FeatureList;
		}
		
		function RoadmapList() {
			$RoadmapQuery = "Roadmap = TRUE AND ProductPageID =".$this->ID;
			$RoadmapList = Feature::get()->where($RoadmapQuery);
			return $RoadmapList;
		}
		
	}