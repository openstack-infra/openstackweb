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
 * Class DeploymentSurveyTask
 */
final class DeploymentSurveyTask extends CliController {

	function process(){

		set_time_limit(0);

		$batch_size = 15;

		if(isset($_GET['batch_size'])){
			$batch_size = intval(trim(Convert::raw2sql($_GET['batch_size'])));
		}

		$surveys     = DeploymentSurvey::getNotDigestSent($batch_size);
		$deployments = Deployment::getNotDigestSent($batch_size);

		$title1 = "User Surveys completed";
		$title2 = "Deployment profiles completed";

		$body = "";

		$body .= "<style>";
		$body .= "*{ font-family: sans-serif;} p {font-size: 11px}";
		$body .= "</style>";

		$body .= "<h2>" . $title1 . "</h2>";

		$send = false;

		if($surveys){

			foreach($surveys as $survey){
				$org_name  =  intval($survey->OrgID) > 0 ?  $survey->Org()->Name:$survey->Member()->getOrgName();
				$body .= "<p><b>Org: " .$org_name  . "</b>";
				$body .= "<br>Updated: " . $survey->UpdateDate;
				$body .= '<br><a href="' . Director::absoluteURL('admin/deployments/DeploymentSurvey/' . $survey->ID .'/edit') . '" target="_blank">View Details</a>';
				$body .= "</p>";

				$survey->SendDigest = 1;
				$survey->write();
			}

			$send = true;

		}

		$body .= "<h2>" . $title2 . "</h2>";

		if($deployments){

			foreach($deployments as $dep){
				$org_name  =  intval($dep->OrgID) > 0 ?  $dep->Org()->Name:$dep->DeploymentSurvey()->Member()->getOrgName();
				$body .= "<p><b>Org: " . $org_name . "</b>";
				$body .= "<br>Updated: " . $dep->UpdateDate;
				$body .= "<br>Is Public: " . $dep->IsPublic;
				if(!$dep->IsPublic){
					$body .= '<br><a href="' . Director::absoluteURL('admin/deployments/Deployment/' . $dep->ID .'/edit') . '" target="_blank">View Details</a>';
				}else{
					$body .= '<br><a href="' . Director::absoluteURL('sangria/ViewDeploymentDetails?dep=' . $dep->ID ) . '" target="_blank">View Details</a>';
				}
				$body .= "</p>";

				$dep->SendDigest = 1;
				$dep->write();
			}

			$send = true;

		}

		if($send == true){
			//echo $body;
			global $email_new_deployment;
			$email = EmailFactory::getInstance()->getInstance()->buildEmail($email_new_deployment, $email_new_deployment, 'New Deployments and Surveys', $body);
			$email->send();
		}
	}
} 