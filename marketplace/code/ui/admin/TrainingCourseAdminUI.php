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
 * Class TrainingCourseAdminUI
 */
class TrainingCourseAdminUI extends DataExtension {

	function getCMSValidator(){
		return $this->getValidator();
	}

	function getValidator(){
		$validator= new RequiredFields(array('Name','Description','Link','LevelID'));
		return $validator;
	}

	public function updateCMSFields(FieldList $fields) {

		$oldFields = $fields->toArray();
		foreach($oldFields as $field){
			$fields->remove($field);
		}

		$fields->push(new LiteralField("Title","<h2>Training Course</h2>"));
		$fields->push(new TextField("Name","Name"));
		$fields->push(new CheckboxField("Online","Online"));
		//only for online
		$fields->push(new TextField("Link","Link"));
		$fields->push(new HtmlEditorField("Description","Description",null));
		$fields->push(new CheckboxField("Paid","Is Paid"));

		$levels = TrainingCourseLevel::get();
		if($levels){
			$level = new DropdownField("LevelID","Level", TrainingCourseLevel::get()->map("ID",'Level','--Select Level--'));
			$fields->push($level);
		}

		if($this->owner->ID > 0){

			$config   = GridFieldConfig_RecordEditor::create();
			$config->getComponentByType('GridFieldDataColumns')->setDisplayFields(array(
				'City' => 'City',
				'State' => 'State',
				'Country' => 'Country',
			));
			$config->removeComponentsByType('GridFieldAddExistingAutocompleter');
			$schedule = new GridField("Schedules","Schedules",$this->owner->Schedules(), $config);
			$fields->push($schedule);

			$config = GridFieldConfig_RelationEditor::create();
			$config->getComponentByType('GridFieldDataColumns')->setDisplayFields(array(
				'Name' => 'Name'
			));
			$config->removeComponentsByType('GridFieldAddNewButton');
			$config->removeComponentsByType('GridFieldEditButton');


			$projects = new GridField(
				'Projects',
				'Projects',
				$this->owner->Projects(),
				$config);

			$config = GridFieldConfig_RelationEditor::create();
			$config->removeComponentsByType('GridFieldAddNewButton');
			$config->removeComponentsByType('GridFieldEditButton');
			$config->getComponentByType('GridFieldDataColumns')->setDisplayFields(array(
				'Name' => 'Name'
			));

			$prerequisite = new GridField(
				'Prerequisites',
				'Prerequisites',
				$this->owner->Prerequisites(),
				$config);

			$fields->push($projects);
			$fields->push($prerequisite);
		}

		return $fields;
	}
} 