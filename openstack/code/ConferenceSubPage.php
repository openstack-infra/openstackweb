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
 * Defines the ConferenceSubPage page type
 */
class ConferenceSubPage extends Page {
   static $db = array(
      'HideSideBar' => 'Boolean'
	);
   static $has_one = array(
   );
   
 	function getCMSFields() {
    	$fields = parent::getCMSFields();

      $fields->addFieldToTab('Root.Metadata', new CheckboxField ('HideSideBar','Hide The Sidebar on this page.'));
    	
    	return $fields;
 	}   
}
 
class ConferenceSubPage_Controller extends Page_Controller {
	function init() {
	    parent::init();
	}

  function TrackingLink() {

    // Get the tracking code from the session if one is set.
    $source = Session::get('TrackingLinkSource');

    // Now look to see if a tracking code was passed in via a URL param.
    // This will override what's in the session if need be
    $getVars = $this->request->getVars();

    if(isset($getVars['source'])) {
      $source = Convert::raw2sql($getVars['source']);
      // Save the source id from the URL param into the session
      Session::set('TrackingLinkSource', $source);
    }

    return $source;   
  }

  function TrackingLinkScript() {

    $trackingLink = $this->TrackingLink();

    if($trackingLink) {

      $script = '

      <script type="text/javascript">

      $(function() {
         $("a.tracking-link").attr("href", function(i, h) {
           return h + ("'.$trackingLink.'");
         });
      });

      </script>


      ';

      return $script;

    }

  }

}