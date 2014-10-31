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
 * Class MarketplaceFactory
 */
final class MarketplaceFactory implements IMarketplaceFactory {

	/**
	 * @param  string $name
	 * @return IMarketPlaceType
	 */
	public function buildMarketplaceType($name)
	{
		$marketplace_type = new MarketPlaceType;
		$marketplace_type->setName($name);
		$marketplace_type->activate();
		$slug = str_replace(' ', '-', strtolower($name));
		$marketplace_type->setSlug($slug);
		$g = $marketplace_type->createSecurityGroup();
		$marketplace_type->setAdminGroup($g);
		return $marketplace_type;
	}

	/**
	 * @param string $title
	 * @return ISecurityGroup
	 */
	public function buildSecurityGroup($title)
	{
		$g =  new Group;
		$g->setTitle($title);
		$g->setDescription($title);
		$g->setSlug(str_replace(' ', '-', strtolower($title)));
		return $g;
	}

	/**
	 * @param string $type
	 * @param int    $max_allowed_duration
	 * @return IMarketPlaceVideoType
	 */
	public function buildMarketPlaceVideoType($type, $max_allowed_duration)
	{
		$video_type = new MarketPlaceVideoType;
		$video_type->Type              = $type;
		$video_type->MaxTotalVideoTime = $max_allowed_duration;
		return $video_type;
	}

	public function buildVideoTypeById($id){
		$video_type = new MarketPlaceVideoType;
		$video_type->ID = $id;
		return $video_type;
	}

	/***
	 * @param int $id
	 * @return ICompany
	 */
	public function buildCompanyById($id)
	{
		$company = new Company;
		$company->ID = $id;
		return $company;
	}

	/**
	 * @param string          $name
	 * @param string          $uri
	 * @param ICompanyService $company_service
	 * @return ICompanyServiceResource
	 */
	public function buildResource($name, $uri, ICompanyService $company_service)
	{
		$resource = new CompanyServiceResource;
		$resource->setName($name);
		$resource->setUri($uri);
		$resource->setOwner($company_service);
		return $resource;
	}


	/**
	 * @param string                $name
	 * @param string                $description
	 * @param string                $youtube_id
	 * @param int                   $length
	 * @param IMarketPlaceVideoType $type
	 * @param ICompanyService       $owner
	 * @return IMarketPlaceVideo
	 */
	public function buildVideo($name, $description, $youtube_id, $length, IMarketPlaceVideoType $type, ICompanyService $owner)
	{
		$video = new MarketPlaceVideo;
		$video->setName($name);
		$video->setDescription($description);
		$video->setYouTubeId($youtube_id);
		$video->setLength($length);
		$video->setType($type);
		$video->setOwner($owner);
		return $video;
	}

	/**
	 * @param int $region_id
	 * @return IRegion
	 */
	public function buildRegionById($region_id)
	{
		$region = new Region;
		$region->ID = $region_id;
		return $region;
	}

	/**
	 * @param int $support_channel_type_id
	 * @return ISupportChannelType
	 */
	public function buildSupportChannelTypeById($support_channel_type_id)
	{
		$support_channel_type = new SupportChannelType;
		$support_channel_type->ID = $support_channel_type_id;
		return $support_channel_type;
	}

}