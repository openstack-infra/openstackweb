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
final class SapphireFileUploadService implements IFileUploadService {

	/**
	 * Upload object (needed for validation
	 * and actually moving the temporary file
	 * created by PHP).
	 *
	 * @var Upload
	 */
	protected $upload;

	/**
	 * @var string
	 */
	private $folder_name;


	public function __construct(){
		$this->upload = new Upload();
	}

	/**
	 * @param string $folder_name
	 */
	public function setFolderName($folder_name){
		$this->folder_name = $folder_name;
	}

	/**
	 * @param string  $file_name
	 * @param IEntity $entity
	 * @return IEntity
	 */
	public function upload($file_name, IEntity $entity){
		if(!isset($_FILES[$file_name])) return false;
		// assume that the file is connected via a has-one
		$hasOnes = $entity->has_one($file_name);
		// try to create a file matching the relation
		$file = (is_string($hasOnes)) ? Object::create($hasOnes) : new File();
		$this->upload->loadIntoFile($_FILES[$file_name], $file, $this->folder_name);
		if($this->upload->isError()) return false;
		$file = $this->upload->getFile();
		if(!$hasOnes) return false;
		// save to record
		$entity->{$file_name . 'ID'} = $file->ID;
		return $file;
	}
} 