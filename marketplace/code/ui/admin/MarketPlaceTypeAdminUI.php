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
 * Class MarketPlaceTypeAdminUI
 */
class MarketPlaceTypeAdminUI extends DataExtension {

	/**
	 * @param FieldList $fields
	 * @return FieldList|void
	 */
	public function updateCMSFields(FieldList $fields){

		$oldFields = $fields->toArray();
		foreach($oldFields as $field){
			$fields->remove($field);
		}
		$fields->push(new LiteralField("Title","<h2>Marketplace Type</h2>"));
		$fields->push(new TextField("Name","Name"));
		$fields->push(new CheckboxField("Active","Active"));

		if($this->owner->ID>0){
			$slug_field = new TextField('Slug','Slug');
			$slug_field->setReadonly(true);
			$slug_field->setDisabled(true);
			$slug_field->performReadonlyTransformation();
			$fields->push($slug_field);
			$group_field = new TextField('Group','Group',$this->owner->AdminGroup()->Title);
			$group_field->setReadonly(true);
			$group_field->setDisabled(true);
			$group_field->performReadonlyTransformation();
			$fields->push($group_field);
		}
		return $fields;
	}

	public function onBeforeWrite(){
		//create group here?
		parent::onBeforeWrite();
	}
} 