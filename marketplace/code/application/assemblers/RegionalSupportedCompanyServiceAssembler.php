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
final class RegionalSupportedCompanyServiceAssembler {

	public static function convertRegionalSupportedCompanyServiceToArray(IRegionalSupportedCompanyService $company_service){
		$res = CompanyServiceAssembler::convertCompanyServiceToArray($company_service);
		//regional support
		$regional_supports = array();
		foreach($company_service->getRegionalSupports() as $regional_support){
			array_push($regional_supports,self::convertRegionalSupportToArray($regional_support));
		}
		$res['regional_support'] = $regional_supports;
		return $res;
	}

	/**
	 * @param IRegionalSupport $regional_support
	 * @return array
	 */
	public static function convertRegionalSupportToArray(IRegionalSupport $regional_support){
		$res = array();
		$res['id']               = $regional_support->getIdentifier();
		$res['region_id']        = $regional_support->getRegion()->getIdentifier();
		$res['region_name']      = $regional_support->getRegion()->getName();
		$res['support_channels'] = array();
		foreach($regional_support->getSupportChannelTypes() as $support_channel){
			array_push($res['support_channels'], self::convertSupportChannel($regional_support->getRegion()->getIdentifier(),$support_channel));
		}
		return $res;
	}

	/**
	 * @param int                 $region_id
	 * @param ISupportChannelType $support_channel
	 * @return array
	 */
	public static function convertSupportChannel($region_id, ISupportChannelType $support_channel){
		$res = array();
		$res['type_name'] = $support_channel->getType();
		$res['region_id'] = $region_id;
		$res['type_id']   = $support_channel->getIdentifier();
		$res['type_name'] = $support_channel->getType();
		$res['data']      = $support_channel->getInfo();
		return $res;
	}
} 