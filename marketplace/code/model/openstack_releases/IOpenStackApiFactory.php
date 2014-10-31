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
 * Interface IOpenStackApiFactory
 */
interface IOpenStackApiFactory {


	/**
	 * @param string $name
	 * @param string $release_number
	 * @param DateTime $release_date
	 * @param string $release_notes_url
	 * @return IOpenStackRelease
	 */
	public function buildOpenStackRelease($name, $release_number, DateTime $release_date, $release_notes_url);


	/**
	 * @param int $id
	 * @return IOpenStackRelease
	 */
	public function buildOpenStackReleaseById($id);

	/**
	 * @param string $name
	 * @param string $code_name
	 * @param string $description
	 * @return IOpenStackComponent
	 */
	public function buildOpenStackComponent($name, $code_name, $description);

	/**
	 * @param int $id
	 * @return IOpenStackComponent
	 */
	public function buildOpenStackComponentById($id);

	/***
	 * @param string           $version
	 * @param string           $status
	 * @param IOpenStackComponent $component
	 * @return IOpenStackApiVersion
	 */
	public function buildOpenStackApiVersion($version,$status, IOpenStackComponent $component);

	/**
	 * @param int $id
	 * @return IOpenStackApiVersion
	 */
	public function buildOpenStackApiVersionById($id);

	/**
	 * @param IOpenStackRelease    $release
	 * @param IOpenStackComponent  $component
	 * @param IOpenStackApiVersion $api_version
	 * @return IReleaseSupportedApiVersion
	 */
	public function buildReleaseSupportedApiVersion(IOpenStackRelease $release,IOpenStackComponent $component,IOpenStackApiVersion $api_version );
} 