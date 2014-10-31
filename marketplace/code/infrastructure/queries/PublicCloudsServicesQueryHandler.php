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
 * Class PublicCloudsServicesQueryHandler
 */
final class PublicCloudsServicesQueryHandler
	implements IQueryHandler {
	/**
	 * @param IQuerySpecification $specification
	 * @return IQueryResult
	 */
	public function handle(IQuerySpecification $specification){

		$sql1    = <<< SQL
        SELECT OpenStackComponent.Name, OpenStackComponent.CodeName FROM CompanyService
        INNER JOIN OpenStackImplementationApiCoverage  ON OpenStackImplementationApiCoverage.ImplementationID = CompanyService.ID
        INNER JOIN OpenStackReleaseSupportedApiVersion ON OpenStackReleaseSupportedApiVersion.ID = OpenStackImplementationApiCoverage.ReleaseSupportedApiVersionID
        INNER JOIN OpenStackComponent                  ON OpenStackComponent.ID = OpenStackReleaseSupportedApiVersion.OpenStackComponentID
        WHERE
        CompanyService.ClassName IN ('PublicCloudService') AND
        OpenStackImplementationApiCoverage.ClassName='CloudServiceOffered'
		GROUP BY OpenStackComponent.Name, OpenStackComponent.CodeName

SQL;

		$topics  = array();
		$results = DB::query($sql1);
		for ($i = 0; $i < $results->numRecords(); $i++) {
			$record = $results->nextRecord();
			$value  = sprintf("%s - %s",$record['Name'],$record['CodeName']);
			array_push($topics, new SearchDTO($value, $value));
		}

		return new OpenStackImplementationNamesQueryResult($topics);
	}
} 