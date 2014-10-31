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
 * Class ConsultantAssembler
 */
final class ConsultantAssembler {

	/**
	 * @param IConsultant $consultant
	 * @return array
	 */
	public static function convertConsultantToArray(IConsultant $consultant){
		$res = RegionalSupportedCompanyServiceAssembler::convertRegionalSupportedCompanyServiceToArray($consultant);
		//offices
		$res['offices'] = array();
		foreach($consultant->getOffices() as $office){
			array_push($res['offices'],self::convertOfficeToArray($office));
		}
		//clients
		$res['reference_clients'] = array();
		foreach($consultant->getPreviousClients() as $client){
			array_push($res['reference_clients'],self::convertClientToArray($client));
		}
		//languages
		$res['languages_spoken'] = array();
		foreach($consultant->getSpokenLanguages() as $language){
			array_push($res['languages_spoken'],self::convertLanguageToArray($language));
		}
		//expertise areas
		$res['expertise_areas'] = array();
		foreach($consultant->getExpertiseAreas() as $area){
			array_push($res['expertise_areas'],$area->getIdentifier());
		}
		//configuration management
		$res['configuration_management'] = array();
		foreach($consultant->getConfigurationManagementExpertises() as $config_expertise){
			array_push($res['configuration_management'],$config_expertise->getIdentifier());
		}
		//services offered
		$services_offered = array();
		foreach($consultant->getServicesOffered() as $offered_service){
			if(!array_key_exists($offered_service->getIdentifier(),$services_offered))
				$services_offered[$offered_service->getIdentifier()] = array();
			array_push($services_offered[$offered_service->getIdentifier()],$offered_service->getRegionId());
		}
		$res['services_offered'] = array();
		foreach($services_offered as $id => $regions){
			$aux = array('id'=>$id,'regions'=>$regions);
			array_push($res['services_offered'],$aux);
		}
		return $res;
	}

	public static function convertOfficeToArray(IOffice $office){
		$res = array();
		$res['address_1'] = $office->getAddress();
		$res['address_2'] = $office->getAddress1();
		$res['city']      = $office->getCity();
		$res['state']     = $office->getState();
		$res['zip_code']  = $office->getZipCode();
		$res['country']   = $office->getCountry();
		return $res;
	}

	public static function convertClientToArray(IConsultantClient $client){
		$res = array();
		$res['name'] = $client->getName();
		return $res;
	}

	public static function convertLanguageToArray(ISpokenLanguage $language){
		$res = array();
		$res['name'] = $language->getName();
		return $res;
	}
}