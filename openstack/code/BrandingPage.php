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
 * Defines the LogoDownloads page type
 */
class BrandingPage extends Page
{
	static $db = array();
	static $has_one = array();

	function getCMSFields()
	{
		$fields = parent::getCMSFields();

		return $fields;
	}
}

class BrandingPage_Controller extends Page_Controller
{

	function init()
	{
		parent::init();

		Requirements::javascript("http://assets.zendesk.com/external/zenbox/v2.5/zenbox.js");
		Requirements::css("http://assets.zendesk.com/external/zenbox/v2.5/zenbox.css");
		Requirements::customScript('
					 if (typeof(Zenbox) !== "undefined") {
					    Zenbox.init({
					      dropboxID:   "20115046",
					      url:         "https://openstack.zendesk.com",
					      tabID:       "Ask Us",
					      tabColor:    "black",
					      tabPosition: "Right"
					    });
					  }

				');


	}

	function BrandingMenu()
	{
		return TRUE;
	}

}