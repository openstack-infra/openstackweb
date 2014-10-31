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
class UpdateDeploymentSurveyDigest extends MigrationTask
{

	protected $title = "Update Deployment Survey Digest Field";

	protected $description = "Set SendDigest = 1 for all the old survey deployments";

	function up()
	{
		echo "Starting  Proc ...<BR>";
		$migration = Migration::get()->filter('Name',$this->title)->first();
		if (!$migration) {
			$migration = new Migration();
			$migration->Name = $this->title;
			$migration->Description = $this->description;
			$migration->Write();
		   
			//run migration
			$surveys = DeploymentSurvey::get();

			foreach($surveys as $survey){
				$survey->SendDigest = 1;
				$survey->write();
			}
			
			
		}
		else{
			echo "Migration Already Ran! <BR>";
		}
		echo "Migration Done <BR>";
	}

	function down()
	{

	}
}