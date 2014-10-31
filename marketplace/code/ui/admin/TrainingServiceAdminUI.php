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
 * Class TrainingServiceAdminUI
 */
class TrainingServiceAdminUI extends DataExtension {

	private static $searchable_fields = array('Name');

	/**
	 * @param FieldList $fields
	 * @return FieldList|void
	 */
	public function updateCMSFields(FieldList $fields) {
		$oldFields = $fields->toArray();
		foreach($oldFields as $field){
			$fields->remove($field);
		}
		$fields->push(new LiteralField("Title","<h2>Training</h2>"));
		$fields->push(new TextField("Name","Name"));
		$fields->push(new HtmlEditorField("Overview","Overview"));
		$fields->push(new CheckboxField("Active","Active"));
		$types     = TrainingCourseType::get();
		$levels    = TrainingCourseLevel::get();
		$companies = Company::get();

		if($companies){
			$fields->push(new DropdownField(
				'CompanyID',
				'Company',
				$companies->map("ID", "Name", "Please Select a Company")));
		}

		if($types && $levels && $this->owner->ID>0){
			$config = GridFieldConfig_RecordEditor::create();
			$courses = new GridField('Courses','Courses',$this->owner->Courses(),$config);
			$fields->push($courses);
		}
		else{
			$fields->push(new LiteralField("Warning","** You can not add any Training Course until you create some Training Course Levels and Training Course Types!."));
		}

		return $fields;
	}

	function getCMSValidator()
	{
		return $this->getValidator();
	}

	function getValidator()
	{
		$validator= new RequiredFields(array('Name','Overview','CompanyID'));
		return $validator;
	}

	/**
	 * @param FieldList $fields
	 */
	public function updateSummaryFields(&$fields) {
		$extra_fields = $this->extraStatics();
		if(isset($extra_fields['summary_fields'])){
			$summary_fields = $extra_fields['summary_fields'];

			// if summary_fields were passed in numeric array,
			// convert to an associative array
			if($summary_fields && array_key_exists(0, $summary_fields)) {
				$summary_fields = array_combine(array_values($summary_fields), array_values($summary_fields));
			}
			if($summary_fields) $fields = $summary_fields;
		}
	}
} 