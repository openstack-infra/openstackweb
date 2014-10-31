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
 * Defines the ConferencePage page type
 */
class ConferencePage extends Page
{
    static $db = array(
        'HeaderArea' => 'HTMLText',
        'Sidebar' => 'HTMLText',
        'HeadlineSponsors' => 'HTMLText',
        'GAConversionId'=>'Text',
        'GAConversionLanguage'=>'Text',
        'GAConversionFormat'=>'Text',
        'GAConversionColor'=>'Text',
        'GAConversionLabel'=>'Text',
        'GAConversionValue'=>'Int',
        'GARemarketingOnly'=>'Boolean',
	    //Facebook Conversion Params
	    'FBPixelId'        => 'Text',
	    'FBValue'          => 'Text',
	    'FBCurrency'       => 'Text',
    );

    static $defaults = array(
	    //Google
        "GAConversionId" => "994798451",
        "GAConversionLanguage" => "en",
        "GAConversionFormat" => "3",
        "GAConversionColor" => "ffffff",
        "GAConversionLabel" => "IuM5CK3OzQYQ89at2gM",
        "GAConversionValue" => 0,
        "GARemarketingOnly" => false,
	    //FB
	    'FBPixelId'        => '6013247449963',
	    'FBValue'          => '0.00',
	    'FBCurrency'       => 'USD',
    );

    static $has_one = array(
        'Summit' => 'Summit'
    );

    function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new HTMLEditorField('HeaderArea', 'Page Header Area'), 'Content');
        $fields->addFieldToTab('Root.Main', new HTMLEditorField('Sidebar', 'Right Sidebar Content'));
        $fields->addFieldToTab('Root.Main', new HTMLEditorField('HeadlineSponsors', 'Headline Sponsors'));
        $summits = Summit::get();

        $SummitDropDownField = new DropdownField("SummitID", "Summit", $summits->map("ID", "Name"));
        $fields->addFieldToTab('Root.Main', $SummitDropDownField);

        //Google Conversion Tracking params
        $fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionId","Conversion Id","994798451"));
        $fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionLanguage","Conversion Language","en"));
        $fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionFormat","Conversion Format","3"));
        $fields->addFieldToTab("Root.GoogleConversionTracking",new ColorField("GAConversionColor","Conversion Color","ffffff"));
        $fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionLabel","Conversion Label","IuM5CK3OzQYQ89at2gM"));
        $fields->addFieldToTab("Root.GoogleConversionTracking",new TextField("GAConversionValue","Conversion Value","0"));
        $fields->addFieldToTab("Root.GoogleConversionTracking",new CheckboxField("GARemarketingOnly","Remarketing Only"));
	    //Facebook Conversion Params
	    $fields->addFieldToTab("Root.FacebookConversionTracking",new TextField("FBPixelId","Pixel Id","6013247449963"));
	    $fields->addFieldToTab("Root.FacebookConversionTracking",new TextField("FBValue","Value","0.00"));
	    $fields->addFieldToTab("Root.FacebookConversionTracking",new TextField("FBCurrency","Currency","USD"));

	    return $fields;
    }
}

class ConferencePage_Controller extends Page_Controller
{

    /*
     * Return google tracking script if ?order=complete query string param is present
     * using settings of current conference page
     */
    function GATrackingCode(){
        $request = $this->request;
        $order = $request->requestVar("order");
        $tracking_code = '';
        if(isset($order) && $order=="complete"){
            //add GA tracking script
            $page = ConferencePage::get()->byID($this->ID);
            if($page && !empty($page->GAConversionId)
                && !empty($page->GAConversionLanguage)
                && !empty($page->GAConversionFormat)
                && !empty($page->GAConversionColor)
                && !empty($page->GAConversionLabel)){
                $tracking_code = $this->renderWith("ConferencePage_GA",array(
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
        }
        return $tracking_code;
    }



    function FBTrackingCode(){
        $request = $this->request;
        $order = $request->requestVar("order");
        $tracking_code = '';
        if(isset($order) && $order=="complete"){
	        //add FB tracking script
	        $page = ConferencePage::get()->byID($this->ID);
	        if($page && !empty($page->FBPixelId)
		        && !empty($page->FBValue)
		        && !empty($page->FBCurrency)
		        ){
		        $tracking_code = $this->renderWith("ConferencePage_FB",array(
			        "FB_Data"=> new ArrayData(array(
					        "FBPixelId"  => $page->FBPixelId,
					        "FBValue"    => $page->FBValue,
					        "FBCurrency" => $page->FBCurrency,
				        ))
		        ));
	        }
        }
        return $tracking_code;
    }

    function init()
    {
        RSSFeed::linkToFeed($this->Link() . "rss", "RSS feed of the OpenStack Conference");
        parent::init();
    }

    function rss()
    {
        $rss = new RSSFeed($this->Children(), $this->Link(), "OpenStack Event Updates RSS Feed", "This feed provides updates related to the OpenStack Spring 2012 Conference and Design Summit.", "Title", "Content");
        $rss->outputToBrowser();
    }

    function TrackingLink()
    {

        // Get the tracking code from the session if one is set.
        $source = Session::get('TrackingLinkSource');

        // Now look to see if a tracking code was passed in via a URL param.
        // This will override what's in the session if need be
        $getVars = $this->request->getVars();

        if (isset($getVars['source'])) {
            $source = Convert::raw2sql($getVars['source']);
            // Save the source id from the URL param into the session
            Session::set('TrackingLinkSource', $source);
        }

        return $source;
    }

    function TrackingLinkScript()
    {

        $trackingLink = $this->TrackingLink();

        if ($trackingLink) {

            $script = <<< JS
			      jQuery(document).ready(function($){
			         $("a.tracking-link").attr("href", function(i, h) {
			  		 return h.replace('summithomepage','$trackingLink');
         			});
      		});
JS;
        Requirements::customScript($script,'TrackingLinkScript');

        }

    }

    public function NewsItems($num = 5)
    {
        return ConferenceNewsPage::get()->filter('ParentID',$this->ID)->sort('Created')->limit($num);
    }

    public function SubPages($num = 10)
    {
	    return ConferenceSubPage::get()->filter('ParentID',$this->ID)->sort('Sort','ASC')->limit($num);
    }

}