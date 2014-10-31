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
class Talk extends DataObject {

	static $db = array(
		'DisplayOnSite' => 'Boolean',
		'SelectedForSummit' => 'Boolean',
		'Description' => 'HTMLText',
		'YouTubeID' => 'Varchar(255)',
		'URLSegment' => 'Text',
		'StartTime' => 'Varchar(255)',
		'EndTime' => 'Varchar(255)',
		'Location' => 'Text',
		'Type' => 'Text',		
		'Topic' => 'Text',
		'OtherTopic' => 'Text',
		'PresentationTitle' => 'HTMLText',
		'Abstract' => 'HTMLText',
		'StaffNote' => 'HTMLText',
		'BeenEmailed' => 'Boolean',
		'Track' => 'Text',
		'VoteTotal' => 'Int',
		'MainTopic' => 'Text',
		'Tag' => 'Text',		
		'MarkedToDelete' => 'Boolean',
		'FlagComment' => 'Varchar',
		'Subcategory' => 'Varchar'
	);

	Static $defaults = array(
		'DisplayOnSite' => TRUE,
	);
	
	static $has_one = array(
		'Slides' => 'File',
		'SummitCategory' => 'SummitCategory',
		'Owner' => 'Member',
		'TimeSlot' => 'SummitTimeSlot',
		'SummitTrack' => 'SummitTrack',
		'Summit' => 'Summit'
	);

	static $has_many = array(
		'SpeakerVotes' => 'SpeakerVote',
	);

	static $many_many = array(
		'Speakers' => 'Speaker'
	);

	static $singular_name = 'Talk';
	static $plural_name = 'Talks';
	
	static $summary_fields = array( 
	      'Name' => 'Presentation Name' 
	   );
	
	function getCMSFields() {
		$fields = new FieldList (
			new TextField('Name','Name of Presentation'),
			new TextField('URLSegment','URL Segment'),
			new LiteralField('Break','<p>(Automatically filled in on first save.)</p>'),
			new LiteralField('Break','<hr/>'),
			new TextField('YouTubeID','YouTube Video ID'),
			new TextField('StartTime','Start Time'),
			new TextField('EndTime','End Time'),
			new HTMLEditorField('Description','Description'),
			new TextField('Location','Location')
		);
		return $fields;
	}
	

	function onBeforeWrite() {
		parent::onBeforeWrite();


		// If there is no URLSegment set, generate one from Title
        if((!$this->URLSegment || $this->URLSegment == 'new-presentation') && $this->PresentationTitle != 'New Presentation') 
        {
            $this->URLSegment = $this->generateURLSegment($this->PresentationTitle);
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

	private function generateURLSegment($title){
		$filter = URLSegmentFilter::create();
		$t = $filter->filter($title);

		// Fallback to generic page name if path is empty (= no valid, convertable characters)
		if(!$t || $t == '-' || $t == '-1') $t = "page-$this->ID";

		return $t;
	}

	//Test whether the URLSegment exists already on another Video
    function LookForExistingURLSegment($URLSegment)
    {
        return Company::get()->filter(array('URLSegment' => $URLSegment,'ID:not' => $this->ID))->first();
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
    	// Slides can be emdedded if they are hosted on Crocodoc. Otherwise, there's only a download button displayed by the template
    	if (strpos($this->SlidesLink,'crocodoc.com') !== false) {
    		return true;
		}
    }

    function CanEdit($member = null) {

    	if($memberID = Member::currentUser()->ID) {
	    	$IsSpeaker = $this->Speakers("MemberID = ".$memberID)->Count();
	    	$IsAdmin = ($this->OwnerID == $memberID || Permission::check("ADMIN"));
	    	return $IsSpeaker || $IsAdmin;
    	}
    }

    function CanAssign($member = null) {
     	if($memberID = Member::currentUser()->ID) {
	    	$IsTrackChair = $this->SummitCategory()->SummitTrackChairs('MemberID = '.$memberID);
	    	if ($IsTrackChair->Count() != 0 || Permission::check("ADMIN")) return TRUE;
    	}   	
    }

    function IsAdmin() {
    	return Permission::check("ADMIN");
    }

    function IsSelected() {
	  	if ($selected = SummitSelectedTalk::get()->filter('TalkID',$this->ID)->first()) return TRUE;
    }

    function HasSpeaker() {
    	if ($this->Speakers()->Count()) return TRUE;
    }

    function CalcTotalPoints() {
    	$sqlQuery = new SQLQuery( 
		   "SUM(VoteValue)", // Select 
		   "SpeakerVote", // From 
		   "TalkID = ".$this->ID // Where (optional) 
		); 
		return $sqlQuery->execute()->value();
    }

    function CalcVoteCount() {
    	$sqlQuery = new SQLQuery( 
		   "COUNT(ID)", // Select 
		   "SpeakerVote", // From 
		   "TalkID = ".$this->ID // Where (optional) 
		); 
		return $sqlQuery->execute()->value();
    }    

    function CalcVoteAverage() {
    	$sqlQuery = new SQLQuery( 
		   "AVG(VoteValue)", // Select 
		   "SpeakerVote", // From 
		   "TalkID = ".$this->ID // Where (optional) 
		); 
		return round($sqlQuery->execute()->value(), 2);
    }

    function Status() {
    	$Selections = SummitSelectedTalk::get()->filter('TalkID',$this->ID);

    	// Error out if a talk has more than one selection
    	if($Selections && $Selections->count() > 1) user_error('There cannot be more than one instance of this talk selected. Talk ID '.$this->ID);
    
    	$Selection = NULL;
    	if ($Selections) $Selection = $Selections->first();
    	if ($Selection) {
			$TalkList = SummitSelectedTalkList::get()->byID($Selection->SummitSelectedTalkListID);
			$Category = SummitCategory::get()->byID($TalkList->SummitCategoryID);
			$NumTalksAllowed = $Category->NumSessions;
			$SortOder = $Selection->Order;
			if($SortOder == NULL || $SortOder == 0) user_error('A sort order is required for Talk ID '.$this->ID);
		}


		If (!$Selection) {
			return 'unaccepted';
		} elseif ($SortOder <= $NumTalksAllowed) {
			return 'accepted';
		} else {
			return 'alternate';
		}

    }

}
