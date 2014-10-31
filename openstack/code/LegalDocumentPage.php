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
	class LegalDocumentPage extends Page {
		static $db = array(
		);
		static $has_one = array(
			'LegalDocumentFile' => 'File'
	     );

	 	function getCMSFields() {
	    	$fields = parent::getCMSFields();

	    	$AttachmentField = new FileIFrameField ('LegalDocumentFile','Attach a PDF or DOC file');
	    	$AttachmentField->allowedExtensions = array('doc','pdf');

	    	$fields->addFieldToTab('Root.Main', $AttachmentField);
	    	    	    		    
	    	return $fields;
	 	}

	}

	class LegalDocumentPage_Controller extends Page_Controller {
		function init() {
			parent::init();
		}
	}