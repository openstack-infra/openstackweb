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
class MarketingPage extends Page{
	
	private static $db = array(
		'GraphicsNotes'      => 'HTMLText',
        'AnnouncementsNotes' => 'Text',
	);

	private static $has_many= array(
		'Graphics'       => 'Graphic',
		'Presentations'  => 'CollateralPresentation',
		'Materials'      => 'EventMaterial',
		'CaseStudies'    => 'CaseStudy',
		'SectionLinks'   => 'SectionLink',
		'Announcements'  => 'Announcement',
        'YouTubeVideos'  => 'YouTubeVideo',
	);
	
	function getCMSFields(){
		
		$fields = parent::getCMSFields();
		//graphics

		$graphics              = new GridField('Graphics', 'Graphics', $this->Graphics(), GridFieldConfig_RecordEditor::create(10));
		$graphics->getConfig()->getComponentByType('GridFieldDataColumns')->setDisplayFields(
			array(
				'Name'         => 'Name',
				'SmallPreview' => 'Thumbnail'
			)
		);
		$graphics->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));

		$graphics_title_editor = new HTMLEditorField('GraphicsNotes','Graphics Subtitle',15,10);

		$fields->addFieldsToTab('Root.Graphics', array (
            $graphics_title_editor ,$graphics
		));

		// presentations

		$collateral_presentations = new GridField('Presentations', 'Presentations', $this->Presentations(), GridFieldConfig_RecordEditor::create(10));
		$collateral_presentations->getConfig()->getComponentByType('GridFieldDataColumns')->setDisplayFields(
			array('Name'=>'Name','FileName'=>'File Name')
		);
		$collateral_presentations->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));

		$fields->addFieldsToTab('Root.Collateral+Presentations',array($collateral_presentations));

		// event materials
		$event_materials =  new GridField('Materials', 'Materials',$this->Materials(), GridFieldConfig_RecordEditor::create(10));
		$event_materials->getConfig()->getComponentByType('GridFieldDataColumns')->setDisplayFields(
			array('Name'=>'Name','FileName'=>'File Name')
		);
		$event_materials->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));

		$fields->addFieldsToTab('Root.Events Materials',array($event_materials));

		//case studies

		$case_study =  new GridField('CaseStudies', 'CaseStudies',$this->CaseStudies(), GridFieldConfig_RecordEditor::create(10));
		$case_study->getConfig()->getComponentByType('GridFieldDataColumns')->setDisplayFields(
			array('Name'=>'Name','SmallPreview'=>'Preview','Link'=>'Link')
		);
		$case_study->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));

		$fields->addFieldsToTab('Root.Case Studies',array($case_study));

		//Announcements

		$announcements =  new GridField('Announcements', 'Announcements',$this->Announcements(), GridFieldConfig_RecordEditor::create(10));
		$announcements->getConfig()->getComponentByType('GridFieldDataColumns')->setDisplayFields(
			array('Content'=>'Content')
		);
		$announcements->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));
		$fields->addFieldsToTab('Root.Announcements',
			array (new TextField('AnnouncementsNotes','Announcements Title'),$announcements));

		//section links

		$section_links =  new GridField('SectionLinks', 'SectionLinks',$this->SectionLinks(), GridFieldConfig_RecordEditor::create(10));
		$section_links->getConfig()->getComponentByType('GridFieldDataColumns')->setDisplayFields(
			array('FileName'=>'Thumbnail File Name','SmallPreview'=>'Preview','Link'=>'Link')
		);
		$section_links->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));
		$fields->addFieldsToTab('Root.Section Links', array($section_links));

		//videos

		$youtube_vids =  new GridField('YouTubeVideos', 'YouTubeVideos',$this->YouTubeVideos() ,GridFieldConfig_RecordEditor::create(10));
		$youtube_vids->getConfig()->getComponentByType('GridFieldDataColumns')->setDisplayFields(
			array('FileName'=>'Thumbnail File Name','SmallPreview'=>'Preview','Url'=>'Url')
		);
		$youtube_vids->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));
		$fields->addFieldsToTab('Root.Section YouTube Videos ', array($youtube_vids));

		//remove main content
		$fields->removeFieldFromTab("Root.Main","Content");
		return $fields;
	}
}

class MarketingPage_Controller extends Page_Controller{
	
	public function LatestGraphics(){

		$graphics = Graphic::get()
			->sort(array('SortOrder'=>'ASC','Created'=>'ASC'))
			->innerJoin('GraphicFile','GraphicFile.GraphicID = Graphic.ID');

		return $graphics;
	}
	
	public function Feeds(){
		$results = DB::query('
		select *
		from (
		select File.`Name`,File.Filename as Data,GraphicFile.ClassName,GraphicFile.LastEdited from GraphicFile
		inner join File on File.ID=GraphicFile.AttachmentID
		union
		select EventMaterial.`Name`,File.Filename as Data,EventMaterial.ClassName,EventMaterial.LastEdited from EventMaterial
		inner join File on File.ID=EventMaterial.AttachmentID
		union
		select Link,Link as Data,ClassName,LastEdited from SectionLink where ImageID>0
		union
		select CollateralPresentation.`Name`,File.Filename as Data, CollateralPresentation.ClassName,CollateralPresentation.LastEdited from CollateralPresentation
		inner join File on File.ID=CollateralPresentation.AttachmentID where AttachmentId > 0
		union
		select Name, Link as Data,ClassName,LastEdited from CaseStudy where CaseStudy.ThumbnailID >0) Feed
		order by LastEdited desc limit 0,10;');
		
		$feeds = new ArrayList();
       	for ($i = 0; $i < $results->numRecords(); $i++) {
   			$record = $results->nextRecord();
   			$class_name = '';
   			switch ($record['ClassName']){
   				case 'GraphicFile':
   					$class_name = 'New File';
   				break;
   				case 'CaseStudy':
   					$class_name = 'New Case Study';
   				break;
   				case 'SectionLink':
   					$class_name = 'New Section Link';
   				break;
   				case 'CollateralPresentation':
   					$class_name = 'New Collateral';
   				break;
   				case 'EventMaterial':
   					$class_name = 'New Events Materials';
   				break;
   			} 
         	$feeds->push(new ArrayData( array('Name' => $record['Name'], 'Data' => $record['Data'], 'ClassName' => $class_name,'Created' => $record['LastEdited']) ));
      	}
      	return $feeds; 
	}
	
	public function LatestCases(){
		return CaseStudy::get()->filter('ThumbnailID:GreaterThan',0)->sort(array('SortOrder' => 'ASC', 'Created' => 'ASC' ));
	}
	
	public function LatestSectionLinks(){
		return SectionLink::get()->filter('ImageID:GreaterThan',0)->sort(array('SortOrder' => 'ASC', 'Created' => 'ASC' ));
	}
	
	public function LatestPresentations(){
		return CollateralPresentation::get()->filter('AttachmentID:GreaterThan',0)->sort(array('SortOrder' => 'ASC', 'Created' => 'ASC' ));
	}
	
	public function LatestEventsMaterial(){
		return EventMaterial::get()->filter('AttachmentID:GreaterThan',0)->sort(array('SortOrder' => 'ASC', 'Created' => 'ASC' ));
	}
	
	public function LatestAnnouncements($num=5){
		return Announcement::get()->sort(array('SortOrder' => 'ASC', 'Created' => 'ASC' ))->limit($num);
	}

    public function LatestYouTubeVideos(){
 	    return YouTubeVideo::get()->filter('ThumbnailID:GreaterThan',0)->sort(array('SortOrder' => 'ASC', 'Created' => 'ASC' ));
    }
}