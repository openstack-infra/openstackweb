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
 * Class UpdateAnniversaryPage
 */
final class UpdateAnniversaryPage extends MigrationTask {

	protected $title = "Update Anniversary Page 4bDay";

	protected $description = "Update Anniversary Page 4bDay";

	function up()
	{
		echo "Starting Migration Proc ...<BR>";
		$migration = Migration::get()->filter('Name',$this->title)->first();
		if (!$migration) {


			//run migration
			$query = <<<SQL
update SiteTree_Live
SET ClassName='AnniversaryPage'
where ID = 891;
SQL;

			DB::query($query);

			$migration = new Migration();
			$migration->Name = $this->title;
			$migration->Description = $this->description;
			$migration->Write();

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