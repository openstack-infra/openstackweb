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
 * Interface ICompanyService
 */
interface ICompanyService extends IManipulableEntity {
	/**
	 * @param ICompany $company
	 * @return void
	 */
	public function setCompany(ICompany $company);

	/**
	 * @return ICompany
	 */
	public function getCompany();

	/**
	 * @param IMarketPlaceType $marketplace
	 * @return void
	 */
	public function setMarketplace(IMarketPlaceType $marketplace);

	/**
	 * @return IMarketPlaceType
	 */
	public function getMarketplace();


	public function getName();
	public function setName($name);

	/**
	 * @return string
	 */
	public function getSlug();

	public function getOverview();
	public function setOverview($overview);

	public function getCall2ActionUri();
	public function setCall2ActionUri($call_2_action_uri);

	/**
	 * @param ICompanyServiceResource $resource
	 * @return void
	 */
	public function addResource(ICompanyServiceResource $resource);

	/**
	 * @return ICompanyServiceResource[]
	 */
	public function getResources();

	public function sortResources(array $new_sort);

	/**
	 * @param IMarketPlaceVideo $video
	 * @return void
	 */
	public function addVideo(IMarketPlaceVideo $video);

	/**
	 * @return IMarketPlaceVideo[]
	 */
	public function getVideos();

	public function clearVideos();
	public function clearResources();
}