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
 * Defines the DeploymentSurveyPage
 */

class DeploymentSurveyReport extends Page {
   static $db = array(
	 );
   static $has_one = array(
   );

 	function getCMSFields() {
    	$fields = parent::getCMSFields();

    	return $fields;
 	}
}

class DeploymentSurveyReport_Controller extends Page_Controller {


	function init() {
	    parent::init();
	}

  function MembersWithPublicDeployments() {
      $MembersWithPublicDeployments = New ArrayList();
      $DeploymentSurveys = DeploymentSurvey::get();
      foreach ($DeploymentSurveys as $CurrentSurvey) {
          $PublicDeployments = Deployment::get()->filter(array('DeploymentSurveyID' => $CurrentSurvey->ID, 'IsPublic' => 1));
          If($PublicDeployments) {
            $Member = Member::get()->byID($CurrentSurvey->MemberID);
            $MembersWithPublicDeployments->push($Member);
            echo $Member->FirstName." has public deployments on DeploymentSurvey ID ".$CurrentSurvey->ID.'<br/>';
          }
      }

      return $MembersWithPublicDeployments;

  }

  function PublicDeployments() {
      $Deployments = Deployment::get()->filter('IsPublic', 1)->sort('DeploymentType');
      return $Deployments;
  }  

  function DeploymentsAsJSON() {
      $Deployments = Deployment::get();
      $f = new JSONDataFormatter(); 
      echo $f->convertDataObjectSet($Deployments);
  }

}