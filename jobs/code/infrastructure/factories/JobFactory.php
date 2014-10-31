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
 * Class JobFactory
 */
final class JobFactory implements IJobFactory {

	/**
	 * @param JobMainInfo       $info
	 * @param IJobLocation[]    $locations
	 * @param JobPointOfContact $point_of_contact
	 * @return IJobRegistrationRequest
	 */
	public function buildJobRegistrationRequest(JobMainInfo $info,
	                                            array $locations,
	                                            JobPointOfContact $point_of_contact){
		$request = new JobRegistrationRequest();
		$request->registerMainInfo($info);
		foreach ($locations as $location) {
			$request->registerLocation($location);
		}
		$request->registerPointOfContact($point_of_contact);
		return $request;
	}

	/**
	 * @param array $data
	 * @return JobMainInfo
	 */
	public function buildJobMainInfo(array $data){
		$company_id    = isset($data['company_id']) ? intval(@$data['company_id']):0;
		$company_name  = trim(@$data['company_name']);
		$company       = new Company;
		$company->Name = $company_name;
		$company->ID   = $company_id;
		return new JobMainInfo(trim($data['title']),$company, trim($data['url']), trim($data['description']), trim($data['instructions']), trim($data['location_type']));
	}

	/**
	 * @param array $data
	 * @return IJobLocation[]
	 */
	public function buildJobLocations(array $data){

		$res = array();

		if(array_key_exists('location_city',$data)){

			$cities    = $data['location_city'];
			$states    = $data['location_state'];
			$countries = $data['location_country'];

			for($i=0; $i < count($cities); $i++){
				$location        = new JobLocation();
				$location->City    = trim(@$cities[$i]);
				$location->State   = trim(@$states[$i]);
				$location->Country = trim(@$countries[$i]);
				array_push($res, $location);
			}
		}
		else if(array_key_exists('locations',$data)){
			$locations = $data['locations'];
			foreach($locations as $location){
				$entity        = new JobLocation();
				$entity->City    = trim(@$location['city']);
				$entity->State   = trim(@$location['state']);
				$entity->Country = trim(@$location['country']);
				array_push($res, $entity);
			}
		}
		return $res;
	}

	/**
	 * @param array $data
	 * @return JobPointOfContact
	 */
	public function buildJobPointOfContact(array $data){
		$contact = new JobPointOfContact(trim($data['point_of_contact_name']), trim($data['point_of_contact_email']));
		return $contact;
	}

	/**
	 * @param IJobRegistrationRequest $request
	 * @return IJob
	 */
	public function buildJob(IJobRegistrationRequest $request){
		$job                        = new JobPage;
		$job->JobPostedDate         = $request->PostDate;
		$job->JobCompany            = $request->CompanyName;
		$job->JobCompany            = $request->CompanyName;
		$job->ExpirationDate        = $request->ExpirationDate;
		$job->Content               = $request->Description;
		$job->Title                 = $request->Title;
		$job->JobMoreInfoLink       = $request->Url;
		$job->JobInstructions2Apply = $request->Instructions2Apply;
		$job->LocationType          = $request->LocationType;
		foreach($request->getLocations() as $location)
			$job->addLocation($location);
		return $job;
	}

	/**
	 * @param IJobRegistrationRequest $request
	 * @return IJobAlertEmail
	 */
	public function buildJobAlertEmail(IJobRegistrationRequest $request)
	{
		$email = new JobAlertEmail;
		$email->setLastJobRegistrationRequest($request);
		return $email;
	}
}