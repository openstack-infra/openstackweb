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
 * Class JobRegistrationRequestDigestTask
 */
final class JobRegistrationRequestDigestTask extends CliController {

	function process(){

		set_time_limit(0);

		try{

			$batch_size = 15;
			if(isset($_GET['batch_size'])){
				$batch_size = intval(trim(Convert::raw2sql($_GET['batch_size'])));
			}
			$manager  = new JobRegistrationRequestManager(
				new SapphireJobRegistrationRequestRepository,
				new SapphireJobRepository,
				new SapphireJobAlertEmailRepository,
				new JobFactory,
				new JobsValidationFactory,
				new SapphireJobPublishingService,
				SapphireTransactionManager::getInstance()
			);

			$manager->makeDigest($batch_size,
				NEW_JOBS_REGISTRATION_REQUEST_EMAIL_ALERT_TO,
				Director::absoluteURL('sangria/ViewJobsDetails'));
			return 'OK';
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			echo $ex->getMessage();
		}
	}
} 