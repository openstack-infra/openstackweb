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
class Page extends SiteTree {

	private static $db = array(
		'IncludeJquery' => 'Boolean',
		'PageJavaScript' => 'Text',
		'PageCSS' => 'Text',
		'IncludeShadowBox' => 'Boolean',
		'BootstrapConverted' => 'Boolean'
	);

	private static $has_one = array(
	);
		
	public function InPast($fieldname) {
	    return $this->$fieldname < date('Y-m-d H:i:s');
	}
	
	public function InFuture($fieldname) {
  	  return $this->$fieldname > date('Y-m-d H:i:s');
	}
	
	public function TimeRightNow() {
	    return date('Y-m-d H:i:s');
	}
	
	function getCMSFields() {
	     $fields = parent::getCMSFields();
	     

	     $fields->addFieldToTab('Root.Settings', new TextField ('PageCSS','Custom CSS File For This Page (must be in CSS directory)'));
	     
		 $fields->addFieldToTab('Root.Settings', new CheckboxField ('IncludeJquery','Include JQuery In This Page'));
		 
		 $fields->addFieldToTab('Root.Settings', new CheckboxField ('IncludeShadowBox','Include ShadowBox Javascript and CSS'));
		
		 $fields->addFieldToTab('Root.Settings', new TextareaField ('PageJavaScript','Custom JavaScript For This Page'));

		 $fields->addFieldToTab('Root.Settings', new CheckboxField ('BootstrapConverted','Converted To Bootstrap'));

	     return $fields;
	 }

 	public static function SchedShortCodeHandler($arguments,$caption = null,$parser = null) {

	    $customise = array();
	    /*** SET DEFAULTS ***/
	    $customise['schedule'] = 'http://openstackconferencespring2012.sched.org/';
	     
	    //overide the defaults with the arguments supplied
	    $customise = array_merge($customise,$arguments);
	     
	    //get our Sched template
	    $template = new SSViewer('Sched');
	    	     
	    //return the customized template
	    return $template->process(new ArrayData($customise));
	     
	}
 	
 	public static function ExternalLinkShortCodeHandler($arguments,$caption = null,$parser = null) {
	    // first things first, if we dont have a url, then we don't need to
	    // go any further
	    if (empty($arguments['url'])) {
	        return;
	    }
	     
	    $customise = array();

	    /*** SET DEFAULTS ***/
	    $customise['category'] = 'Outbound Links';
	    //if no name is provided as an option, we'll use the URL instead
	    $customise['name'] = $arguments['url'];
	    $customise['newwindow'] = FALSE;
	    $customise['cssclass'] = FALSE;


	    //set the caption
    	    $customise['caption'] = $caption ? Convert::raw2xml($caption) : false;
	     
	    //override the defaults with the arguments supplied
	    $customise = array_merge($customise,$arguments);
	     
	    //get our ExternalLink template
	    $template = new SSViewer('ExternalLinkShortCode');
	     
	    //return the customised template
	    return $template->process(new ArrayData($customise));
	}


	function requireDefaultRecords() {

		parent::requireDefaultRecords();

		// create a 400 ErrorPage
		if ($this->class == 'ErrorPage') {

			// Ensure that an assets path exists before we do any error page creation
			if(!file_exists(ASSETS_PATH)) {
				mkdir(ASSETS_PATH);
			}

			$ErrorPage400 = DataObject::get_one('ErrorPage', "\"ErrorCode\" = '400'");
			$ErrorPage400Exists = ($ErrorPage400 && $ErrorPage400->exists()) ? true : false;
			$ErrorPage400Path = ErrorPage::get_filepath_for_errorcode(400);
			if(!($ErrorPage400Exists && file_exists($ErrorPage400Path))) {
				if(!$ErrorPage400Exists) {
					$ErrorPage400 = new ErrorPage();
					$ErrorPage400->ErrorCode = 400;
					$ErrorPage400->Title = _t('ErrorPage.ERRORPAGE400TITLE', '400 Error');
					$ErrorPage400->Content = _t(
						'ErrorPage.ERRORPAGE400CONTENT',
						'<p>An error occurred while processing your request.</p>'
					);
					$ErrorPage400->Status = 'New page';
					$ErrorPage400->write();
					$ErrorPage400->publish('Stage', 'Live');
				}

				// Ensure a static error page is created from latest error page content
				$response = Director::test(Director::makeRelative($ErrorPage400->Link()));
				if($fh = fopen($ErrorPage400Path, 'w')) {
					$written = fwrite($fh, $response->getBody());
					fclose($fh);
				}

				if($written) {
					DB::alteration_message('400 error page created', 'created');
				} else {
					DB::alteration_message(sprintf(
						'400 error page could not be created at %s. Please check permissions',
						$ErrorPage400Path), 'error');
				}
			}

			$ErrorPage412 = DataObject::get_one('ErrorPage', "\"ErrorCode\" = '412'");
			$ErrorPage412Exists = ($ErrorPage412 && $ErrorPage412->exists()) ? true : false;
			$ErrorPage412Path = ErrorPage::get_filepath_for_errorcode(412);
			if(!($ErrorPage412Exists && file_exists($ErrorPage412Path))) {
				if(!$ErrorPage412Exists) {
					$ErrorPage412 = new ErrorPage();
					$ErrorPage412->ErrorCode = 412;
					$ErrorPage412->Title = _t('ErrorPage.ERRORPAGE412TITLE', '412 Error');
					$ErrorPage412->Content = _t(
						'ErrorPage.ERRORPAGE412CONTENT',
						'<p>Your Session has expired!.</p>'
					);
					$ErrorPage412->Status = 'New page';
					$ErrorPage412->write();
					$ErrorPage412->publish('Stage', 'Live');
				}

				// Ensure a static error page is created from latest error page content
				$response = Director::test(Director::makeRelative($ErrorPage412->Link()));
				if($fh = fopen($ErrorPage412Path, 'w')) {
					$written = fwrite($fh, $response->getBody());
					fclose($fh);
				}

				if($written) {
					DB::alteration_message('412 error page created', 'created');
				} else {
					DB::alteration_message(sprintf(
						'412 error page could not be created at %s. Please check permissions',
						$ErrorPage412Path), 'error');
				}
			}
		}
	}

}

class Page_Controller extends ContentController {

	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	private static $allowed_actions = array (
		'logout',
		'FeedbackForm',
	);
	

    protected function CustomScripts(){

        Requirements::javascript("themes/openstack/javascript/jquery.ticker.js");
        Requirements::javascript("themes/openstack/javascript/jquery.tools.min.js");
        Requirements::javascript("themes/openstack/javascript/jquery.colorbox-min.js");
        Requirements::javascript("themes/openstack/javascript/jcarousellite.min.js");

        Requirements::javascript("themes/openstack/javascript/jcarousellite.min.js");
        Requirements::javascript("themes/openstack/javascript/bootstrap.min.js");
        Requirements::javascript("themes/openstack/javascript/navigation.js");

        $filename = 'themes/openstack/javascript/'.$this->URLSegment.'.js';
        if (file_exists(Director::baseFolder().'/'.$filename)) {
            Requirements::javascript($filename);
        }
        $filename = 'themes/openstack/css/'.$this->URLSegment.'.css';
        if (file_exists(Director::baseFolder().'/'.$filename)) {
            Requirements::css($filename);
        }

        $page_id = $this->ID;
	    if(!is_null($page_id)){
	        $page    = Page::get()->byID($page_id);
	        if($page  && !empty($page->PageCSS)){
	            $custom_css_file = THEMES_DIR ."/openstack/css/{$page->PageCSS}";
	            if(@file_exists($custom_css_file))
	                Requirements::css($custom_css_file);
	        }
	    }
    }

	public function init() {
		parent::init();

		// Summit Landing Page Redirects
		// Looks to see if ?source is set and redirects to either English or Chinese landing page
		// based on the source
		if(isset($this->request)) $getVars = $this->request->getVars();
		$chineseLangCampaigns = array("o2", "o4", "o6", "o8", "o17", "o18", "o22");

		/* if(isset($getVars['source'])) {
			if(in_array($getVars['source'], $chineseLangCampaigns)) {
				// Chinese language landing page
				$this->redirect('/summit/hkc');
			} else {
				// English language landing page
				$this->redirect('/summit/hk');
			}
		} */

		Requirements::block(SAPPHIRE_DIR . "/javascript/jquery_improvements.js");
		Requirements::block(FRAMEWORK_DIR . '/thirdparty/jquery/jquery.js');
		Requirements::block(FRAMEWORK_DIR . '/thirdparty/jquery/jquery.min.js');

		if(Director::isLive()) {
			Requirements::javascript('themes/openstack/javascript/jquery.min.js');
		}
		else{
			Requirements::javascript('themes/openstack/javascript/jquery.js');
		}

        Requirements::javascript('themes/openstack/javascript/jquery-migrate-1.2.1.min.js');

        Requirements::javascript("themes/openstack/javascript/jquery.cookie.js");

        Requirements::css("themes/openstack/javascript/shadowbox/shadowbox.css");
        Requirements::javascript("themes/openstack/javascript/shadowbox/shadowbox.js");

        if(Director::get_current_page()->IncludeShadowBox){
            Requirements::customScript("Shadowbox.init();");
        }

		Requirements::javascript('themes/openstack/javascript/filetracking.jquery.js');

        Requirements::customScript("jQuery(document).ready(function($) {

			var d = new Date();
            var user_date = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
            $.cookie('user_date',user_date , { expires: 360, path: '/' });

            $('body').filetracking();

            $('.outbound-link').live('click',function(event){
                var href = $(this).attr('href');
                recordOutboundLink(this,'Outbound Links',href);
                event.preventDefault();
                event.stopPropagation()
                return false;
            });
        });");

        $this->CustomScripts();

	}

	public function DateSortedChildren(){ 
	   $children = $this->Children(); 
	   if(!$children) 
	      return null; 
	   $children->sort('EventStartDate', 'DESC');
	   return $children; 
	}

	// Feedback form in site footer
	function FeedbackForm() {
		$FeedbackForm = new feedbackForm($this, 'FeedbackForm');
		// Since we are not handling sensitive data with logged in users,
		// it's fine to disable the CSFR security token.
		$FeedbackForm->enableSecurityToken();
		return $FeedbackForm;
	}

	// Simple methods used to get & set messages that display on the page.
	public function setMessage($type, $message) {   
		Session::set('Message', array(
		    'MessageType' => $type,
		    'Message' => $message
		));
    	}
 
    public function getMessage() {
        	if($message = Session::get('Message')){
		 	Session::clear('Message');
		    	$array = new ArrayData($message);
		    	return $array->renderWith('Message');
       		}
    	}
	
	public function logout() {
   		Security::logout(true); 
	}

	public function CurrentProtocol() {
		return Director::protocol();
	}

	public function EncodedLink() {
		return urlencode($this->link());
	}
}
