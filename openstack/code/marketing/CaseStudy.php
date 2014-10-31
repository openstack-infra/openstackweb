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
class CaseStudy extends DataObject{

	private static $db = array(
		'Name' => 'Text',
		'Tagline' => 'Text',
		'Link' => 'Text',
		'SortOrder' => 'Int'
	);

	private static $default_sort = 'SortOrder';

	private static $has_one = array(
		'Thumbnail' => 'Image',
		'MarketingPage' => 'MarketingPage'
	);
	
	function getCMSFields(){
	
		$image = new CustomUploadField('Thumbnail','Thumbnail');
		//save to path marketing/case_study
		$image->setFolderName('marketing/case_study');
		$image->setAllowedFileCategories('image');

		$image_validator = new Upload_Validator();
		$image_validator->setAllowedExtensions(array('jpg','png','jpeg'));
		$image->setValidator($image_validator);

		return new FieldList(
				new TextField('Name'),
				new TextField('Tagline'),
				new TextField('Link'),
				$image
		);
	}
	
	function getValidator()	{
		$validator= new FileRequiredFields(array('Name','Tagline','Link'));
        $validator->setRequiredFileFields(array("Thumbnail"));
        return $validator;
	}
	
	public function getPreview(){
		$img = $this->Thumbnail();
		if($img->exists()){
			return $img->SetRatioSize('60','60');
		}
		return 'n/a';
	}
	

	public function getSmallPreview(){
		$img = $this->Thumbnail();
		if($img->exists()){
			return $img->SetRatioSize('30','30');
		}
		return 'n/a';
	}
	
}