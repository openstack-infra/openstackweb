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
class InvolvementType extends DataObject {

	static $db = array(
		'Name' => 'Text',
	);

	static $has_one = array(
	);

	static $belongs_many_many = array(
		'Orgs' => 'Org'
	);

	static $singular_name = 'Involvement Type';
	static $plural_name = 'Involvement Types';

}