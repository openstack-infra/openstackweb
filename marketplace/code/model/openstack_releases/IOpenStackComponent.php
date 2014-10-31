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
 * Interface IOpenStackComponent
 */
interface IOpenStackComponent extends IEntity {

	public function getName();
	public function setName($name);

	public function getCodeName();
	public function setCodeName($codename);

	public function getDescription();
	public function setDescription($description);

	/**
	 * @return bool
	 */
	public function getSupportsVersioning();

	/**
	 * @param bool $supports_versioning
	 * @return void
	 */
	public function setSupportsVersioning($supports_versioning);

	/**
	 * @return bool
	 */
	public function getSupportsExtensions();

	/**
	 * @param bool $supports_extensions
	 * @return void
	 */
	public function setSupportsExtensions($supports_extensions);

	/**
	 * @return IOpenStackApiVersion[]
	 */
	public function getVersions();

	/**
	 * @param IOpenStackApiVersion $new_version
	 * @return void
	 */
	public function addVersion(IOpenStackApiVersion $new_version);

	public function clearVersions();

	/**
	 * @param int $version_id
	 * @return bool
	 */
	public function hasVersion($version_id);

	/**
	 * @return IOpenStackRelease[]
	 */
	public function getSupportedReleases();

} 