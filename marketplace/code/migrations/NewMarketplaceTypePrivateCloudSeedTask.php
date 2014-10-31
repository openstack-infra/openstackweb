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
 * Class NewMarketplaceTypePrivateCloudSeedTask
 */
final class NewMarketplaceTypePrivateCloudSeedTask extends MigrationTask {

	protected $title = "New Marketplace Type \"Private Cloud\" Seeding  Migration";

	protected $description = "Add new Marketplace Type 'Private Cloud' to DB and its Security Group";

	function up(){
		echo "Starting Migration Proc ...<BR>";
		//check if migration already had ran ...
		$migration = Migration::get()->filter('Name',$this->title)->first();

		if (!$migration) {

			$marketplace_types = array(
				'Private Cloud',
			);

			$factory = new MarketplaceFactory;
			$service = new MarketplaceTypeManager(
				new SapphireMarketPlaceTypeRepository,
				new SapphireSecurityGroupRepository,
				SapphireTransactionManager::getInstance());

			foreach($marketplace_types as $marketplace_type){
				try{
					$service->store($factory->buildMarketplaceType($marketplace_type));
				}
				catch(Exception $ex){

				}
			}

			DB::query("INSERT INTO CloudServiceOffered_PricingSchemas (CloudServiceOfferedID, PricingSchemaTypeID) SELECT PublicCloudServiceOfferedID, PricingSchemaTypeID FROM PublicCloudServiceOffered_PricingSchemas;");

			DB::query("UPDATE OpenStackImplementationApiCoverage SET ClassName='CloudServiceOffered'
WHERE ImplementationID IN (SELECT ID FROM CompanyService where ClassName='PublicCloudService');");

			DB::query("UPDATE DataCenterRegion SET CloudServiceID = PublicCloudID;");

			DB::query("ALTER TABLE `CloudServiceOffered_PricingSchemas`
			ADD UNIQUE INDEX `CloudServiceOffered_PricingSchemaType` (`CloudServiceOfferedID` ASC, `PricingSchemaTypeID` ASC);");

			DB::query("ALTER TABLE `DataCenterRegion` DROP INDEX `Name_PublicCloud`;");

			DB::query("ALTER TABLE `DataCenterRegion`
			ADD UNIQUE INDEX `Name_CloudService` (`Name` ASC, `CloudServiceID` ASC);");

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