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
 * Interface IMarketplaceFactory
 */
interface  IMarketplaceFactory {


	/**
	 * @param  string $name
	 * @return IMarketPlaceType
	 */
	public function buildMarketplaceType($name);


	/**
	 * @param string $title
	 * @return ISecurityGroup
	 */
	public function buildSecurityGroup($title);


	/**
	 * @param string $type
	 * @param int $max_allowed_duration
	 * @return IMarketPlaceVideoType
	 */
	public function buildMarketPlaceVideoType($type, $max_allowed_duration);

	public function buildVideoTypeById($id);

	/**
	 * @param string                $name
	 * @param string                $description
	 * @param string                $youtube_id
	 * @param int                   $length
	 * @param IMarketPlaceVideoType $type
	 * @param ICompanyService       $owner
	 * @return IMarketPlaceVideo
	 */
	public function buildVideo($name, $description, $youtube_id, $length, IMarketPlaceVideoType $type, ICompanyService $owner);
	/***
	 * @param int $id
	 * @return ICompany
	 */
	public function buildCompanyById($id);

	//resources

	// resources
	/**
	 * @param string          $name
	 * @param string          $uri
	 * @param ICompanyService $company_service
	 * @return ICompanyServiceResource
	 */
	public function buildResource($name,$uri,ICompanyService $company_service);

	/**
	 * @param int $region_id
	 * @return IRegion
	 */
	public function buildRegionById($region_id);

	/**
	 * @param int $support_channel_type_id
	 * @return ISupportChannelType
	 */
	public function buildSupportChannelTypeById($support_channel_type_id);

} 