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
 * Class CSVReader
 */
final class CSVReader {

	/**
	 * @var resource
	 */
	private $file_handle;

	/**
	 * @param string $filename
	 * @throws InvalidArgumentException
	 */
	public function __construct($filename){
		if(!file_exists($filename))
			throw new InvalidArgumentException;
		$this->file_handle = fopen($filename, "r");
		if(!$this->file_handle)
			throw new InvalidArgumentException;
	}

	function __destruct() {
		fclose($this->file_handle);
	}

	/**
	 * @return array|bool
	 */
	function getLine(){
		if (!feof($this->file_handle) ) {
			return fgetcsv($this->file_handle, 1024);
		}
		return false;
	}
} 