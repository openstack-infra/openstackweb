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
/**
 * Class AnniversaryPage
 */
class AnniversaryPage  extends Page {
    function getCMSFields() {
        $fields = parent::getCMSFields();
        // remove unneeded fields
        $fields->removeFieldFromTab("Root.Main","Content");
        return $fields;
    }

    // ThirdAnniversaryPage can't contain children
    static $allowed_children = "none";

}

/**
 * Class AnniversaryPage_Controller
 */
class AnniversaryPage_Controller extends Page_Controller {

	static $allowed_actions = array(
		'third',
		'fourth',
		'handleIndex',
	);

	static $url_handlers = array(
		'' => 'handleIndex',
	);


	public function third(){
		Requirements::css($this->ThemeDir().'/css/anniversary/3/bootstrap.anniversary.css');
		Requirements::css($this->ThemeDir().'/css/anniversary/3/styles.css');
		Requirements::javascript($this->ThemeDir().'/javascript/anniversary.3.js');
		//FB page properties
		$this->Title    = 'The OpenStack Third Anniversary';
		$this->FBImage  = 'http://97ddcca80f76c4bfffa8-fba9438aa8767b03b10d7d590f8ffd05.r77.cf1.rackcdn.com/openstack-3rd-anniversary.png';
		$this->FBImageW = '173';
		$this->FBImageH = '245';
		$this->FBDesc      	 = 'Happy 3th OpenStack! Come celebrate at one of 51 global events.';
		$this->CurrentDomain = Director::protocolAndHost();
		$this->FBUrl         = Director::protocolAndHost().$this->Link('third');
		
		return $this->getViewer('third')->process($this);
	}

	public function fourth(){
		Requirements::css($this->ThemeDir().'/css/anniversary/4/bootstrap.css');
		Requirements::css($this->ThemeDir().'/css/anniversary/4/styles.css');
		Requirements::javascript($this->ThemeDir().'/javascript/anniversary.4.js');
		//FB page properties
		$this->Title         = 'The OpenStack Fourth Anniversary';
		$this->FBImage       = Director::protocolAndHost().'/themes/openstack/images/anniversary/4/bot-big.png';
		$this->FBImageW      = '200';
		$this->FBImageH 	 = '284';
		$this->FBDesc      	 = 'Happy 4th OpenStack! Come celebrate at one of 51 global events.';
		$this->FBUrl         = Director::protocolAndHost().$this->Link('fourth');
		$this->CurrentDomain = Director::protocolAndHost();

		return $this->getViewer('fourth')->process($this);
	}

	public function handleIndex(){
		return $this->fourth();
	}

    public function init() {
	    parent::init();
	    Requirements::javascript(Director::protocol()."platform.twitter.com/widgets.js");
        Requirements::javascript(Director::protocol()."cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js");
    }

}