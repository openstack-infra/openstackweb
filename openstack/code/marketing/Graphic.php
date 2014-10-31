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
class Graphic extends DataObject {

	private static $db = array(
		'Name' => 'Text',
		'SortOrder' => 'Int'
	);

	private  static $has_one = array(
        'MarketingPage' => 'MarketingPage',
		'Thumbnail'     => 'Image'
	);

	private static $default_sort = 'SortOrder';

    private static $has_many = array('Files' => 'GraphicFile');

	function getCMSFields() {

		$fields = new FieldList();

		$files = new GridField('Files', 'Create/Edit File',$this->Files(), GridFieldConfig_RecordEditor::create(10));
		$files->getConfig()->getComponentByType('GridFieldDataColumns')->setDisplayFields(
			array(
				'Name' => 'FileName',
				'SmallPreview' => 'Thumbnail'
			)
		);

		$image   = new CustomUploadField('Thumbnail', 'Thumbnail');
		$image->setFolderName('marketing/graphics');
		$image->setAllowedFileCategories('image');

		$image_validator = new Upload_Validator();
		$image_validator->setAllowedExtensions(array('jpg', 'png', 'jpeg'));
		$image->setValidator($image_validator);

		$fields->push(new TextField('Name'));
		$fields->push($image);

		if($this->ID > 0)
			$fields->push($files);

		return $fields;
	}

	public function getType() {
		$qty = $this->Files()->Count();
		return $qty > 1 ? "folder (" . $qty . " items)" : "file";
	}

	public function HasPreview() {
		$img = $this->Thumbnail();
		return $img->exists();
	}
	
	public function getPreview() {
		$img = $this->Thumbnail();
		if ($img->exists()) {
			return $img->SetRatioSize('134', '134');
		}
		return 'n/a';
	}
	
	public function getSmallPreview() {
		$img = $this->Thumbnail();
		if ($img->exists()) {
			return $img->SetRatioSize('20', '20');
		}
		return 'n/a';
	}

	public function getURLPreview() {
		$files = $this->ValidFiles();
		if ($files->Count() == 1) {
			$img = $files->First()->Thumbnail();
			if ($img->exists()) {
				return $img->getURL();
			}
			return '#';
		} else {
			$img = $this->Thumbnail();
			if ($img->exists()) {
				return $img->getURL();
			}
			return '#';
		}
	}
	
	public function getFileLink() {
		$files = $this->ValidFiles();
		if ($files->Count() == 1) {
			$file = $files->First()->Attachment();
			if ($file->exists()) {
				return $file->getURL();
			}
			return '#';
		} else {
			
			return '#';
		}
	}
	
	public function getBannerName(){
		$files = $this->ValidFiles();
		if ($files->Count() == 1) {
			$file = $files->First()->Attachment();
			if ($file->exists()) {
				return $file->Name;
			}
			return $this->Name;
		} else {
			return $this->Name;
		}
	}

	function getValidator() {
		return new RequiredFields(array('Name'));
	}

	public function DonwloadAllZip() {
		$zipper = new Zipper();
		$files = $this->Files();
		$file_list = array();
		$zip_name = FileUtils::convertToFileName($this->Name). '.zip';
		foreach ($files as $file) {
			if (!$file->Attachment()->Exists())
				continue;
			$name = $file->Attachment()->Filename;
			array_push($file_list, Director::baseFolder() . '/' . $name);
		}
		if ($zipper->getZipLink($file_list,Director::baseFolder() . '/assets/marketing/temp',$zip_name))
			return '/assets/marketing/temp/' .$zip_name;
		else
			return '#';
	}

	public function ValidFiles() {
		$files = GraphicFile::get()->filter(array('GraphicID' => $this->ID,'ThumbnailID:GreaterThan' => 0,'AttachmentID:GreaterThan' => 0 ))->sort(array('SortOrder'=>' ASC',  'Created' => 'ASC'));
		return $files;
	}

	public function MoreThanValidFiles($len) {
		return $this->ValidFiles()->Count() > $len;
	}

}