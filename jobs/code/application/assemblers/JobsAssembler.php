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
 * Class JobsAssembler
 */
final class JobsAssembler {
	/**
	 * @param IJobRegistrationRequest $request
	 * @return array
	 */
	public static function convertJobRegistrationRequestToArray(IJobRegistrationRequest $request){
		$res                           = array();
		$main_info                     = $request->getMainInfo();
		$res['title']                  = $main_info->getTitle();
		$res['url']                    = $main_info->getUrl();
		$res['description']            = $main_info->getDescription();
		$res['instructions']           = $main_info->getInstructions();
		$res['company_name']           = $main_info->getCompany()->Name;
		$res['location_type']          = $main_info->getLocationType();
		$expiration_date               = $main_info->getExpirationDate();
		if(!is_null($expiration_date))
			$res['expiration_date']    = $expiration_date->format('Y-m-d');
		$point_of_contact              = $request->getPointOfContact();
		$res['point_of_contact_name']  = $point_of_contact->getName();
		$res['point_of_contact_email'] = $point_of_contact->getEmail();
		$locations = array();
		foreach($request->getLocations() as $location){
			$l            = array();
			$l['city']    = $location->city();
			$l['state']   = $location->state();
			$l['country'] = $location->country();
			array_push($locations,$l);
		}
		$res['locations'] = $locations;
		return $res;
	}
} 