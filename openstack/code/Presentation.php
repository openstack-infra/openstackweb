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
class Presentation extends DataObject {

	static $db = array(
		'Name' => 'HTMLText',
		'DisplayOnSite' => 'Boolean',
		'Featured' => 'Boolean',
		'City' => 'Varchar(255)',
		'Country' => 'Varchar(255)',
		'Description' => 'HTMLText',
		'YouTubeID' => 'Varchar(255)',
		'URLSegment' => 'Text',
		'StartTime' => 'Varchar(255)',
		'EndTime' => 'Varchar(255)',
		'Location' => 'Text',
		'Type' => 'Text',
		'Day' => 'Int',
		'Speakers' => 'Text',
		'SlidesLink' => 'Varchar(255)'
	);

	Static $defaults = array(
		'DisplayOnSite' => TRUE,
		'Country' => 'United States'
	);
	
	static $has_one = array(
		'PresentationCategoryPage' => 'PresentationCategoryPage',
		'Summit' => 'Summit'
	);

	static $has_many = array(
		'Presentations' => 'File'
	);

	static $singular_name = 'Presentation';
	static $plural_name = 'Presentations';
	
	static $summary_fields = array( 
	      'Name' => 'Presentation Name' 
	   );
	
	function getCMSFields() {
		$fields = new FieldList (
			new TextField('Name','Name of Presentation'),
			new TextField('Speakers','Speakers'),
			new DropdownField('Day','Day', array('1' => '1', '2' => '2', '3' => '3', '4' => '4')),
			new TextField('URLSegment','URL Segment'),
			new LiteralField('Break','<p>(Automatically filled in on first save.)</p>'),
			new LiteralField('Break','<hr/>'),
			new TextField('YouTubeID','YouTube Vidoe ID'),
			new TextField('SlidesLink','Link To Slides (if available)'),
			new TextField('StartTime','Video Start Time'),
			new TextField('EndTime','Video End Time'),
			new HTMLEditorField('Description','Description')
		);
		return $fields;
	}
	

	function onBeforeWrite() {
		parent::onBeforeWrite();


		// If there is no URLSegment set, generate one from Title
        if((!$this->URLSegment || $this->URLSegment == 'new-presentation') && $this->Title != 'New Presentation') 
        {
            $this->URLSegment = SiteTree::generateURLSegment($this->Title);
        } 
        else if($this->isChanged('URLSegment')) 
        {
            // Make sure the URLSegment is valid for use in a URL
            $segment = preg_replace('/[^A-Za-z0-9]+/','-',$this->URLSegment);
            $segment = preg_replace('/-+/','-',$segment);
              
            // If after sanitising there is no URLSegment, give it a reasonable default
            if(!$segment) {
                $segment = "presentation-".$this->ID;
            }
            $this->URLSegment = $segment;
        }
  
        // Ensure that this object has a non-conflicting URLSegment value by appending number if needed
        $count = 2;
        while($this->LookForExistingURLSegment($this->URLSegment)) 
        {
            $this->URLSegment = preg_replace('/-[0-9]+$/', null, $this->URLSegment) . '-' . $count;
            $count++;
        }				

	}

	//Test whether the URLSegment exists already on another Video
    function LookForExistingURLSegment($URLSegment)
    {
	    return Company::get()->filter(array('URLSegment' => $URLSegment, 'ID:not' => $this->ID))->first();
    }

    // Pull video thumbnail from YouTube API
    function ThumbnailURL() {
    	if ($this->YouTubeID) {


    		/* $response = @file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$this->YouTubeID."?v=2&alt=jsonc");

	    	if ($response) {
		    	$json = json_decode($response);
		    	if (isset($json->data->thumbnail->sqDefault)) {
					return $json->data->thumbnail->sqDefault;
				}
			} */

			return "http://i.ytimg.com/vi/".$this->YouTubeID."/default.jpg";


		}
    }


    //Generate the link for this product
    function ShowLink()
    {
        $ParentPage = $this->PresentationCategoryPage();

        if ($ParentPage) {
        	return $ParentPage->Link()."presentation/".$this->URLSegment;
        }
    }

    // See if the presentation slides can be embedded
    function EmbedSlides()
    {
    	// Slides can be emdedded if they are hosted on crocodoc. Otherwise, there's only a download button displayed by the template
    	if (strpos($this->SlidesLink,'crocodoc.com') !== false) {
    		return true;
		}
    }

    function SchedEventImport($ParentPageID) {
    	$Events = SchedEvent::get();
    	foreach ($Events as $Event) {
			$Presentation = new Presentation();

			// Bring over existing data
			$Presentation->Name = $Event->eventtitle;
			$Presentation->DisplayOnSite = TRUE;
			$Presentation->Description = $Event->description;
			$Presentation->StartTime = $Event->event_start;
			$Presentation->EndTime = $Event->event_end;
			$Presentation->Type = $Event->event_type;
			$Presentation->Speakers = $Event->speakers;
    	
    		// Determine day

			$day = 1;

    		switch ($Event->event_start) {
    			case '2013-11-05':
    				$day = 1;
    				break;
       			case '2013-11-06':
    				$day = 2;
    				break;
    			case '2013-11-07':
    				$day = 3;
    				break;
    			case '2013-11-08':
    				$day = 4;
    				break;
    			case '2013-11-09':
    				$day = 5;
    				break;
    		}

    		$Presentation->Day = $day;

    		// Assign parent page
    		$Presentation->PresentationCategoryPageID = $ParentPageID;
    		$Presentation->write();

    		if($Event->UploadedMedia()) {
    			$Presentation->SlidesLink = $Event->UploadedMedia()->link();
    		} elseif ($Event->HostedMediaURL()) {
    			$Presentation->SlidesLink = $Event->HostedMediaURL();
    		}

    		$Presentation->write();

    	}

    }

    function AddMedia($ParentPageID) {

        $Presentations = Presentation::get()->filter('PresentationCategoryPageID', $ParentPageID);

        foreach ($Presentations as $Presentation) {
            if(!$Presentation->SlidesLink) {

                $SchedEventMatch = SchedEvent::get()->filter('eventtitle',$Presentation->Name)->first();

                if($SchedEventMatch && $SchedEventMatch->UploadedMedia()) {
                    $Presentation->SlidesLink = $SchedEventMatch->UploadedMedia()->link();
                    $Presentation->write();
                    echo 'Added slides to "' . $Presentation->Name . '" <br/>';
                } elseif ($SchedEventMatch && $SchedEventMatch->HostedMediaURL()) {
                    $Presentation->SlidesLink = $SchedEventMatch->HostedMediaURL();
                    $Presentation->write();
                    echo 'Added slides to "' . $Presentation->Name . '" <br/>';
                }
            }
        }

    }

    function AdjustDates() {
    	$Events = SchedEvent::get();
    	foreach ($Events as $Event) {
    		$Presentation = Presentation::get()->filter('Name', $Event->eventtitle)->first();
    		if($Presentation) {
				$Presentation->StartTime = $Event->event_start;
				$Presentation->EndTime = $Event->event_end;
				$Presentation->write();
    		}
    	}

    }


}