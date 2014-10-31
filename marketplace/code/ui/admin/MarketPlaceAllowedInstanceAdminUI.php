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
 * Class MarketPlaceAllowedInstanceAdminUI
 */
final class MarketPlaceAllowedInstanceAdminUI extends DataExtension {

	public function updateCMSFields(FieldList $fields) {
		$oldFields = $fields->toArray();
		foreach($oldFields as $field){
			$fields->remove($field);
		}

		$fields->push(new TextField("MaxInstances","Max. Instances"));

		$companies = Company::get();
		if($companies){
			$fields->push($ddl = new DropdownField(
				'CompanyID',
				'Company',
				$companies->map("ID", "Name")));
			$ddl->setEmptyString("Please Select a Company");
		}


		$market_place_types = MarketPlaceType::get();
		if($market_place_types){
			$fields->push($ddl = new DropdownField(
				'MarketPlaceTypeID',
				'MarketPlaceType',
				$market_place_types->map("ID", "Name")));

			$ddl->setEmptyString( "Please Select a Market Place Type");
		}

		return $fields;
	}

	function getCMSValidator()
	{
		return $this->getValidator();
	}

	function getValidator()
	{
		$validator= new RequiredFields(array('CompanyID','MarketPlaceTypeID','MaxInstances'));
		return $validator;
	}
} 