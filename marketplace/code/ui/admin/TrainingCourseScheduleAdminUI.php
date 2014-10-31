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
 * Class TrainingCourseScheduleAdminUI
 */
class TrainingCourseScheduleAdminUI extends DataExtension {

	public function updateCMSFields(FieldList $fields) {
		$oldFields = $fields->toArray();
		foreach($oldFields as $field){
			$fields->remove($field);
		}

		$CountryCodes = CountryCodes::$iso_3166_countryCodes;
		$CountryCodes[""] = $CountryCodes["unspecified"];
		unset($CountryCodes["unspecified"]);

		$fields->push(new LiteralField("Title","<h2>Course Schedule </h2>"));
		$fields->push(new TextField("City","City"));
		$fields->push(new TextField("State","State"));
		$fields->push(new DropdownField("Country","Country",$CountryCodes));

		if($this->owner->ID > 0 ){
			$config = GridFieldConfig_RecordEditor::create();
			$config->removeComponentsByType('GridFieldAddExistingAutocompleter');
			$times  = new GridField("Times","Times", $this->owner->Times(),$config);
			$fields->push($times);
		}
		return $fields;
	}

	function getCMSValidator()
	{
		return $this->getValidator();
	}

	function getValidator()
	{
		$validator= new RequiredFields(array('City','Country'));
		return $validator;
	}
} 