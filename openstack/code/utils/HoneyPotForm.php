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
 * Class HoneyPotForm
 */
class HoneyPotForm extends SafeXSSForm {

	const FieldName = 'field_98438688';

	/**
	 * @param Controller $controller
	 * @param String     $name
	 * @param FieldList   $fields
	 * @param FieldList   $actions
	 * @param null       $validator
	 */
	function __construct($controller, $name, FieldList $fields, FieldList $actions, $validator = null) {
			// Guard against automated spam registrations by optionally adding a field
			// that is supposed to stay blank (and is hidden from most humans).
			$fields->push($honey = new TextField(self::FieldName,self::FieldName));
			$honey->addExtraClass('honey');

		$css =<<<CSS
.honey {
	position: absolute; left: -9999px
}
CSS;
			Requirements::customCSS($css, 'honey_css');

			parent::__construct($controller, $name, $fields, $actions, $validator);

		}

		function loadDataFrom($data, $clearMissingFields = false, $fieldList = null) {
			$res = parent::loadDataFrom($data, $clearMissingFields, $fieldList);
			// Check if the honeypot has been filled out
			if(is_array($data) &&  isset($data[self::FieldName]) && $data[self::FieldName]!='') {
				SS_Log::log(sprintf('honeypot triggered (data: %s)',http_build_query($data)), SS_Log::NOTICE);
				return $this->httpError(403);
			}
			return $res;
		}

	/**
	 * @return string
	 */
	public function getHoneyFieldName(){
		return self::FieldName;
	}
}