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
/**
 * Class CSVExporter
 */
final class CSVExporter {

	/**
	 * @var CSVExporter
	 */
	private static $instance;

	private function __construct(){}

	private function __clone(){}

	/**
	 * @return CSVExporter
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance = new CSVExporter();
		}
		return self::$instance;
	}


	/**
	 * @param        $filename
	 * @param array  $data
	 * @param string $field_separator
	 * @param string $mime_type
	 */
	public function export($filename, array $data, $field_separator = "\t", $mime_type ='application/vnd.ms-excel'){
		ob_end_clean();
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: ".$mime_type);

		$flag = false;
		foreach($data as $row) {
			if(!$flag) {
				// display field/column names as first row
				echo implode($field_separator, array_keys($row)) . "\n";
				$flag = true;
			}
			array_walk($row, array($this,'cleanData'));
			echo implode($field_separator, array_values($row)) . "\n";
		}
	}

	function cleanData(&$str)
	{
		$str = preg_replace("/\t/", "\\t", $str);
		$str = preg_replace("/\r?\n/", "\\n", $str);
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}
} 