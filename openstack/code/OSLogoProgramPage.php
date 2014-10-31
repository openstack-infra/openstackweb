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
	class OSLogoProgramPage extends Page {
		static $db = array(
		);
		static $has_one = array(
	     );
		static $has_many = array(
		);

		static $defaults = array ( 
		 'ShowInMenus' => false, 
		 'ShowInSearch' => false 
		);      		
		
		
		function getCMSFields() {
			$fields = parent::getCMSFields();
			return $fields;
		}
				
		
	}

	class OSLogoProgramPage_Controller extends Page_Controller {

		public static $allowed_actions = array (
			'Form'
      	);	

		function init() {
			parent::init();
		}

		function Form() {
			$Form = new OSLogoProgramForm($this, 'Form');
			return $Form;
		}
			
	}