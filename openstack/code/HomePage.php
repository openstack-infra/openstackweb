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
 * Defines the HomePage page type
 */
 
class HomePage extends Page {
   static $db = array(
		'FeedData' => 'HTMLText',
		'EventDate' => 'Date',
		'VideoCurrentlyPlaying' => 'Text'
   );

   static $has_one = array(
   );

	function getCMSFields() {
		$fields = parent::getCMSFields();

		// Summit Video Stream
      	$VideoLiveField = new OptionSetField('VideoCurrentlyPlaying', 'Is the video live streaming at the moment?', array(
            'Yes' => 'Video is being streamed.',
            'No' => 'No video playing.'
        ));

		$fields->addFieldToTab("Root.Main", $VideoLiveField, 'Content');

		// Countdown Date
		$EventStartDate = new DateField('EventDate','First Day of Event (for counting down)');
		$EventStartDate->setConfig('showcalendar', true);
		$EventStartDate->setConfig('showdropdown', true);
		$fields->addFieldToTab('Root.Main', $EventStartDate, 'Content');

		// remove unneeded fields 
		$fields->removeFieldFromTab("Root.Main","Content");

		return $fields;
	}   
}
 
class HomePage_Controller extends Page_Controller {

	static $allowed_actions = array(
		'Video',
		'LatestNews'
	);	
			
	function init() { 
	   parent::init(); 
	       	       
		//	Set default currency unless this is a returning visitor 
	   $VisitorCookie = new Cookie; 
	   if(!$VisitorCookie->get('ReturningVisitor')) { 
	         $VisitorCookie->set('ReturningVisitor', TRUE); 
	   }

	    Requirements::customScript("Shadowbox.init();");

	}

	function UpcomingEvents($num=1) {

		$events = EventPage::get()->where("EventEndDate >= now()")->sort('EventStartDate','ASC')->limit($num);
		$output = '';

        if ($events) {
            foreach ($events as $key => $event) {
                $first = ($key == 0);
                $data = array('IsEmpty'=>0,'IsFirst'=>$first);

                $output .= $event->renderWith('EventHolder_event', $data);
            }
        } else {
            $data = array('IsEmpty'=>1);
            $output .= Page::renderWith('EventHolder_event', $data);
        }

        return $output;
	}

	function DisplayVideo() {
		$getVars = $this->request->getVars();
		return ($this->VideoCurrentlyPlaying == 'Yes' || isset($getVars['video']));
	}

	function Video() {
		//Detect special conditions devices
		$iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");

		//do something with this information
		if( $iPod || $iPhone ||  $iPad ){
		    $this->redirect('http://itechsherpalive2.live-s.cdn.bitgravity.com/cdn-live-s1/_definst_/itechsherpalive2/live/OSS13/playlist.m3u8');
		} else {
			return $this->renderWith(array('HomePage_Video','HomePage','Page'));
		}

	}

	function RssItems($limit = 7) { 

		$feed = new RestfulService('http://planet.openstack.org/rss20.xml',7200);

		$feedXML = $feed->request()->getBody();

		// Extract items from feed 
		$result = $feed->getValues($feedXML, 'channel', 'item'); 

		foreach ($result as $item ) {
			$item->pubDate = date("D, M jS Y", strtotime($item->pubDate));
		}

		return $result->limit(0,$limit);
	}	

	function PastEvents($num=1) {
	  return EventPage::get()->where("EventEndDate <= now()")->sort('EventStartDate')->limit($num);
	}
		
	function ReturningVisitor() {
		$VisitorCookie = new Cookie;
		return ($VisitorCookie->get('ReturningVisitor')==TRUE);
	}
	
	function CompanyCount() {
		$DisplayedCompanies = Company::get()->filter('DisplayOnSite',1);
		$Count = $DisplayedCompanies->Count();
		return $Count;
	}

	function DaysUntil() {
		$date = $this->EventDate;
		return (isset($date)) ? floor((strtotime($date) - time())/60/60/24) : FALSE;
	}
}