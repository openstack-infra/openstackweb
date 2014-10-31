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
class PresentationMediaUploadForm extends Form
{

	function __construct($controller, $name)
	{

		$FileAttachment = new FileField('UploadedMedia', 'File', null, null, null, '/presentation-media/');

		$fields = new FieldList(
			$FileAttachment
		);
		$actions = new FieldList(
			new FormAction('doUpload', 'Upload File')
		);
		$validator = new RequiredFields(array('UploadedMedia'));

		parent::__construct($controller, $name, $fields, $actions, $validator);

	}

	function forTemplate()
	{
		return $this->renderWith(array(
			$this->class,
			'Form'
		));
	}

	function doUpload($data, $form)
	{
		if (isset($data['UploadedMedia']['tmp_name'])) {
			if (!empty($data['UploadedMedia']['name'])) {
				// create new single file array from file uploads array
				$file = array();
				$file['name'] = $data['UploadedMedia']['name'];
				$file['type'] = $data['UploadedMedia']['type'];
				$file['tmp_name'] = $data['UploadedMedia']['tmp_name'];
				$file['error'] = $data['UploadedMedia']['error'];
				$file['size'] = $data['UploadedMedia']['size'];

				// create & write uploaded file in DB
				try {
					$newfile = new File();
					$upload = new Upload();
					// get folder from form upload field
					$folder = $form->Fields()->fieldByName('UploadedMedia')->getFolderName();
					$upload->loadIntoFile($file, $newfile, $folder);
					$fileObj = $upload->getFile();

					$EventID = Session::get('UploadMedia.PresentationID');
					if ($EventID) $Event  = SchedEvent::get()->byID($EventID);
					if ($Event) $Metadata = SchedEventMetadata::get()->filter('event_key',$Event->event_key)->first();

					if (isset($Metadata) && $Metadata) {
						$Metadata->UploadedMediaID = $fileObj->ID;
						$Metadata->MediaType = 'File';
						$Metadata->write();
						Session::set('UploadMedia.Success', TRUE);
						Session::set('UploadMedia.FileName', $fileObj->Name);
						Session::set('UploadMedia.Type', 'File');

						Controller::curr()->redirect($form->controller()->link() . 'Success');
					} elseif ($Event) {
						$Metadata = new SchedEventMetadata();
						$Metadata->event_key = $Event->event_key;
						$Metadata->UploadedMediaID = $fileObj->ID;
						$Metadata->MediaType = 'File';
						$Metadata->write();
						Session::set('UploadMedia.Success', TRUE);
						Session::set('UploadMedia.FileName', $fileObj->Name);
						Session::set('UploadMedia.Type', 'File');

						Controller::curr()->redirect($form->controller()->link() . 'Success');
					}

				} catch (ValidationException $e) {
					$form->sessionMessage('Extension not allowed...', 'bad');
					return $this->controller()->redirectBack();
				}
			}
		}

	}

}