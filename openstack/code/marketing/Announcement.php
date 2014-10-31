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
class Announcement extends DataObject{

	private static $db = array(
			'Content' => 'HTMLText',
			'SortOrder' => 'Int'
	);

	private static $default_sort = 'SortOrder';

	private static $singular_name = 'Announcement';
	private static $plural_name = 'Announcements';
	
	
	static $has_one = array(
			'MarketingPage' => 'MarketingPage'
	);
	
	function getCMSFields(){
		return new FieldList(array(
				new HtmlEditorField('Content')
		));
	}
	
	function getValidator()
	{
		return new RequiredFields(array('Content'));
	}
}