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
 * Class EventsAssembler
 */
final class EventsAssembler {
	/**
	 * @param IEventRegistrationRequest $request
	 * @return array
	 */
	public static function convertEventRegistrationRequestToArray(IEventRegistrationRequest $request){
		$res                           = array();
		$main_info                     = $request->getMainInfo();
		$res['title']                  = $main_info->getTitle();
		$res['url']                    = $main_info->getUrl();
		$res['label']                  = $main_info->getLabel();
		$point_of_contact              = $request->getPointOfContact();
		$res['point_of_contact_name']  = $point_of_contact->getName();
		$res['point_of_contact_email'] = $point_of_contact->getEmail();
		$location                      = $request->getLocation();
		$res['city']                   = $location->getCity();
		$res['state']                  = $location->getState();
		$res['country']                = $location->getCountry();
		$duration                      = $request->getDuration();
		$res['start_date']             = $duration->getStartDate()->format('Y-m-d');
		$res['end_date']               = $duration->getEndDate()->format('Y-m-d');
		return $res;
	}
}