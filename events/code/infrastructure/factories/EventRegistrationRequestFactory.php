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
 * Class EventRegistrationRequestFactory
 */
final class EventRegistrationRequestFactory
	implements IEventRegistrationRequestFactory {

	/**
	 * @param EventMainInfo $info
	 * @param EventPointOfContact $point_of_contact
	 * @param EventLocation $location
	 * @param EventDuration $duration
	 * @param SponsorInfo   $sponsor
	 * @return IEventRegistrationRequest
	 */
	public function buildEventRegistrationRequest(EventMainInfo $info,
	                                              EventPointOfContact $point_of_contact,
	                                              EventLocation $location,
	                                              EventDuration $duration, SponsorInfo $sponsor = null)
	{
		$registration_request = new EventRegistrationRequest;
		$registration_request->registerMainInfo($info);
		$registration_request->registerPointOfContact($point_of_contact);
		$registration_request->registerLocation($location);
		$registration_request->registerDuration($duration);
		if(!is_null($sponsor)){
			$registration_request->registerSponsor($sponsor);
		}
		return $registration_request;
	}

	/**
	 * @param array $data
	 * @return EventMainInfo
	 */
	public function buildEventMainInfo(array $data)
	{
		$main_info = new EventMainInfo(trim($data['title']) ,trim($data['url']), 'Details');
		return $main_info;
	}

	/**
	 * @param array $data
	 * @return EventLocation
	 */
	public function buildEventLocation(array $data)
	{
		$location = new EventLocation(trim($data['city']),trim(@$data['state']),trim($data['country']));
		return $location;
	}

	/**
	 * @param array $data
	 * @return EventDuration
	 */
	public function buildEventDuration(array $data)
	{

		$duration = new EventDuration(
			DateTime::createFromFormat('Y-m-d', $data['start_date']),
			DateTime::createFromFormat('Y-m-d', $data['end_date']));
		return $duration;
	}

	/**
	 * @param array $data
	 * @return SponsorInfo
	 */
	public function buildSponsorInfo(array $data)
	{
		if(isset($data['sponsor'])){
			return new SponsorInfo(trim($data['sponsor']),trim(@$data['sponsor_url']));
		}
		return null;
	}

	public function buildEvent(IEventRegistrationRequest $request) {

		$event                      = new EventPage;
		$event->Title               = $request->Title;
		$event->ClassName           = 'EventPage';
		$event->ParentID            = 41;
		$event->EventLink           = $request->Url;
		$event->EventLinkLabel      = $request->Label;
		$event->EventStartDate      = $request->StartDate;
		$event->EventEndDate        = $request->EndDate;
		$event->EventLocation       = (!empty($request->State))?sprintf("%s, %s, %s",$request->City,$request->State,$request->Country):sprintf("%s, %s",$request->City,$request->Country);
		$event->IsSummit            = false;
		return $event;
	}

	public function buildEventAlertEmail(IEventRegistrationRequest $last)
	{
		$email = new EventAlertEmail;
		$email->setLastEventRegistrationRequest($last);
		return $email;
	}

	/**
	 * @param array $data
	 * @return EventPointOfContact
	 */
	public function buildPointOfContact(array $data)
	{
		$contact = new EventPointOfContact(trim($data['point_of_contact_name']),trim($data['point_of_contact_email']));
		return $contact;
	}
}