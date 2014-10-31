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
final class JobUpdateTask  extends MigrationTask {

	protected $title = "Jobs Updates Migration";

	protected $description = "Set ExpirationDate and active fields for current Jobs";

	function up(){
		echo "Starting Migration Proc ...<BR>";
		//check if migration already had ran ...
		$migration = Migration::get()->filter('Name', $this->title)->first();
		if (!$migration) {

			DB::query("update JobPage_Live set active =1;");
			DB::query("update JobPage set active =1;");
			DB::query("update JobPage_versions set active =1;");

			DB::query("update JobPage set ExpirationDate = DATE_ADD(JobPostedDate, INTERVAL 2 MONTH)
where JobPage.JobPostedDate IS NOT NULL;");

			DB::query("update JobPage_Live set ExpirationDate = DATE_ADD(JobPostedDate, INTERVAL 2 MONTH)
where JobPage_Live.JobPostedDate IS NOT NULL;");

			DB::query("update JobPage_versions set ExpirationDate = DATE_ADD(JobPostedDate, INTERVAL 2 MONTH)
where JobPage_versions.JobPostedDate IS NOT NULL;");

			$migration = new Migration();
			$migration->Name = $this->title;
			$migration->Description = $this->description;
			$migration->Write();
		}
		echo "Ending  Migration Proc ...<BR>";
	}

	function down()	{

	}

} 