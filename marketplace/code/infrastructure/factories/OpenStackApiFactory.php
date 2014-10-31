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
 * Class OpenStackApiFactory
 */
final class OpenStackApiFactory implements IOpenStackApiFactory {

	/**
	 * @param string            $name
	 * @param string            $code_name
	 * @param string            $description
	 * @return IOpenStackComponent
	 */
	public function buildOpenStackComponent($name, $code_name, $description)
	{
		$component = new OpenStackComponent;
		$component->setName($name);
		$component->setCodeName($code_name);
		$component->setDescription($description);
		return $component;
	}

	/**
	 * @param int $id
	 * @return IOpenStackComponent
	 */
	public function buildOpenStackComponentById($id)
	{
		$component = new OpenStackComponent;
		$component->ID = (int)$id;
		return $component;
	}

	/***
	 * @param string           $version
	 * @param string           $status
	 * @param IOpenStackComponent $component
	 * @return IOpenStackApiVersion
	 */
	public function buildOpenStackApiVersion($version,$status, IOpenStackComponent $component)
	{
		$api_version = new OpenStackApiVersion;
		$api_version->setVersion($version);
		$api_version->setStatus($status);
		if(!is_null($component)){
			$api_version->setReleaseComponent($component);
			$component->addVersion($api_version);
		}
		return $api_version;
	}

	/**
	 * @param int $id
	 * @return IOpenStackApiVersion
	 */
	public function buildOpenStackApiVersionById($id){
		$api_version = new OpenStackApiVersion;
		$api_version->ID = $id;
		return $api_version;
	}


	/**
	 * @param string $name
	 * @param string $release_number
	 * @param DateTime $release_date
	 * @param string $release_notes_url
	 * @return IOpenStackRelease
	 */
	public function buildOpenStackRelease($name, $release_number, DateTime $release_date, $release_notes_url)
	{
		$release = new OpenStackRelease;
		$release->setName($name);
		$release->setReleaseNumber($release_number);
		$release->setReleaseDate($release_date);
		$release->setReleaseNotesUrl($release_notes_url);
		return $release;
	}

	/**
	 * @param int $id
	 * @return IOpenStackRelease
	 */
	public function buildOpenStackReleaseById($id)
	{
		$release = new OpenStackRelease;
		$release->ID = (int)$id;
		return $release;
	}

	/**
	 * @param IOpenStackRelease    $release
	 * @param IOpenStackComponent  $component
	 * @param IOpenStackApiVersion $api_version
	 * @return IReleaseSupportedApiVersion
	 */
	public function buildReleaseSupportedApiVersion(IOpenStackRelease $release, IOpenStackComponent $component, IOpenStackApiVersion $api_version)
	{
		$supported_api = new OpenStackReleaseSupportedApiVersion;
		$supported_api->setRelease($release);
		$supported_api->setOpenStackComponent($component);
		$supported_api->setApiVersion($api_version);
		return $supported_api;
	}
}