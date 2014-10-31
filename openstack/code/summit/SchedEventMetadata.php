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
/*
 *  The SchedEvent object is designed to be replaced / overwritten on each import from Sched.
 *  It should never contain fields that don't map 1:1 to what's available from the Sched API.
 *
 *  This object extends SchedEvent to include OpenStack-specific metadata for our site.
 *  It's stored here in a companion object to prevent it from being destroyed on each import.
 *  The foreign key linking the two is event_key (set for each event by Sched & guaranteed to be consistent)
 *
 */
	
class SchedEventMetadata extends DataObject {

	static $db = array(
		'event_key' => 'Varchar',
		'BeenEmailed' => 'Boolean',
		'YouTubeVideoID' => 'Varchar',
		'HostedMediaURL' => 'Text',
		'MediaType' => "Enum('URL, File')"
	);

	static $has_one = array(
		'UploadedMedia' => 'File'
	);

	static $singular_name = 'EventMetadata';
	static $plural_name = 'EventMetadata';
	
}