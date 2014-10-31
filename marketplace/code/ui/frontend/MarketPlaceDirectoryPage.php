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
class MarketPlaceDirectoryPage extends MarketPlacePage
{
	static $db = array(
		'GAConversionId'       => 'Text',
		'GAConversionLanguage' => 'Text',
		'GAConversionFormat'   => 'Text',
		'GAConversionColor'    => 'Text',
		'GAConversionLabel'    => 'Text',
		'GAConversionValue'    => 'Int',
		'GARemarketingOnly'    => 'Boolean',
		'RatingCompanyID'      => 'Int',
		'RatingBoxID'          => 'Int',
	);

	static $defaults = array(
		'RatingCompanyID' => 4398,
		'RatingBoxID'     => 11919,
	);

	function getCMSFields()
	{
		$fields = parent::getCMSFields();
		//Google Conversion Tracking Params
		$fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionId","Conversion Id"));
		$fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionLanguage","Conversion Language","en"));
		$fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionFormat","Conversion Format","3"));
		$fields->addFieldToTab("Root.GoogleConversionTracking",new ColorField("GAConversionColor","Conversion Color","ffffff"));
		$fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionLabel","Conversion Label"));
		$fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionValue","Conversion Value","0"));
		$fields->addFieldToTab("Root.GoogleConversionTracking",new CheckboxField("GARemarketingOnly","Remarketing Only"));
		$fields->addFieldToTab("Root.RatingBoxWidget",new LiteralField('Label','** more info at <a href="http://www.rating-system.com/integration/UserGuide.aspx">User Guide</a>'));
		$fields->addFieldToTab("Root.RatingBoxWidget",new TextField("RatingCompanyID","Company ID",4398));
		$fields->addFieldToTab("Root.RatingBoxWidget",new TextField("RatingBoxID","Rating Box ID",11919));
		return $fields;
	}

	static $allowed_children = "none";

}

class MarketPlaceDirectoryPage_Controller extends MarketPlacePage_Controller {

	private static $allowed_actions = array();

	/**
	 * @return string
	 */
	protected function GATrackingCode(){

		$tracking_code = '';
		//add GA tracking script
		$page = $this->data();

		if($page && !empty($page->GAConversionId)
			&& !empty($page->GAConversionLanguage)
			&& !empty($page->GAConversionFormat)
			&& !empty($page->GAConversionColor)
			&& !empty($page->GAConversionLabel)){

			$tracking_code = $this->renderWith("MarketPlaceDirectoryPage_GA",array(
				"GA_Data"=> new ArrayData(array(
						"GAConversionId"       => $page->GAConversionId,
						"GAConversionLanguage" => $page->GAConversionLanguage,
						"GAConversionFormat"   => $page->GAConversionFormat,
						"GAConversionColor"    => $page->GAConversionColor,
						"GAConversionLabel"    => $page->GAConversionLabel,
						"GAConversionValue"    => $page->GAConversionValue,
						"GARemarketingOnly"    => $page->GARemarketingOnly?"true":"false",
					))
			));
		}

		return $tracking_code;
	}


	function init() 	{
		//redirect always to HTTP bc review widget
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$destURL = str_replace('https:', 'http:', Director::absoluteURL($_SERVER['REQUEST_URI']));
			// This coupling to SapphireTest is necessary to test the destination URL and to not interfere with tests
			if(class_exists('SapphireTest', false) && SapphireTest::is_running_test()) {
				return $destURL;
			} else {
				if(!headers_sent()) header("Location: $destURL");
				die("<h1>Your browser is not accepting header redirects</h1><p>Please <a href=\"$destURL\">click here</a>");
			}
		}
		parent::init();
	}

	public function getRatingSystemCompanyId(){
		$page = $this->data();
		return intval($page->RatingCompanyID);
	}

	public function getRatingSystemRatingBoxId(){
		$page = $this->data();
		return intval($page->RatingBoxID);
	}
}