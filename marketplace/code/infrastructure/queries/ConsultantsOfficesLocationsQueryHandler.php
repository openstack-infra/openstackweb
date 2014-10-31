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
 * Class ConsultantsOfficesLocationsQueryHandler
 */
final class ConsultantsOfficesLocationsQueryHandler implements IConsultantsOfficesLocationsQueryHandler {
	/**
	 * @param IQuerySpecification $specification
	 * @return IQueryResult
	 */
	public function handle(IQuerySpecification $specification)
	{
		$params = $specification->getSpecificationParams();
		$term   = @$params['name_pattern'];
		$filter = '';
		if(!empty($term)){
			$term   = Convert::raw2sql($term);

			$countries         = Geoip::getCountryDropDown();
			$matched_countries = array_filter($countries, function($el) use ($term) {
				return ( strpos( strtolower($el), strtolower($term)) !== false );
			});
			$country_filter = '';
			if(count($matched_countries)){
				foreach($matched_countries as $code => $name ){
					$country_filter .= " OR Country = '{$code}' ";
				}
			}
			else{
				$country_filter =  " OR Country LIKE '%{$term}%' ";
			}
			$filter = "WHERE City LIKE '%{$term}%' {$country_filter}";
		}

		$locations = array();
		$sql    = <<< SQL
        SELECT City,State,Country FROM Office
		{$filter}
		GROUP BY City,State,Country

SQL;
		$results = DB::query($sql);
		for ($i = 0; $i < $results->numRecords(); $i++) {
			$record = $results->nextRecord();
			$city    = $record['City'];
			$state   = $record['State'];
			$country = Geoip::countryCode2name($record['Country']);
			if(!empty($state))
				$value   = sprintf('%s, %s, %s',$city, $state, $country);
			else
				$value   = sprintf('%s, %s',$city, $country);
			array_push($locations, new SearchDTO($value,$value));
		}
		return new OpenStackImplementationNamesQueryResult($locations);
	}
} 