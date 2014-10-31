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
class YouTubeVideo extends DataObject {

    private static $db = array(
        'Url' => 'Text',
        'SortOrder' => 'Int'
    );

    private static $has_one = array(
        'MarketingPage' => 'MarketingPage',
        'Thumbnail' => 'Image',
    );

	private static $default_sort = 'SortOrder';

    function getCMSFields(){

	    $fields = new FieldList;

        $image = new CustomUploadField('Thumbnail','Thumbnail');
	    $image->setFolderName('marketing/youtube_vids_thumbs');
	    $image->setAllowedFileCategories('image');

        $image_validator = new Upload_Validator();
        $image_validator->setAllowedExtensions(array('jpg','png','jpeg'));
        $image->setValidator($image_validator);

	    $fields->push(new TextField('Url'));
	    $fields->push($image);

    }

    function getValidator()	{
        $validator= new FileRequiredFields(array('Url'));
        $validator->setRequiredFileFields(array("Thumbnail"));
        return $validator;
    }

    public function getFileName(){
        $img = $this->Thumbnail();
        if($img->exists()){
            return $img->Filename;
        }
        return 'n/a';
    }

    public function getSmallPreview(){
        $img = $this->Thumbnail();
        if($img->exists()){
            return $img->SetRatioSize('150','75');
        }
        return 'n/a';
    }

    public function getPreview(){
        $img = $this->Thumbnail();
        if($img->exists()){
            return $img->SetRatioSize('300','169');
        }
        return 'n/a';
    }
}