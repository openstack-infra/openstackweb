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
 * Interface IMarketPlaceType
 */
interface IMarketPlaceType extends IEntity {

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name);

	/**
	 * @return string
	 */
	public function getSlug();

	/**
	 * @param string $slug
	 * @return void
	 */
	public function setSlug($slug);

	/**
	 * @return bool
	 */
	public function isActive();

	/**
	 * @return void
	 */
	public function activate();

	/**
	 * @return void
	 */
	public function deactivate();

	/**
	 * @return string
	 */
	public function getAdminGroupSlug();

	/**
	 * @return ISecurityGroup
	 */
	public function getAdminGroup();

	/**
	 * @param ISecurityGroup $group
	 * @return void
	 */
	public function setAdminGroup(ISecurityGroup $group);

	/**
	 * @return ISecurityGroup
	 */
	public function createSecurityGroup();
}