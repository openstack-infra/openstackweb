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
 * Interface ICompanyServiceResource
 */
interface ICompanyServiceResource extends IEntity{

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param $name
	 * @return void
	 */
	public function setName($name);

	/**
	 * @return string
	 */
	public function getUri();

	/**
	 * @param string $uri
	 * @return void
	 */
	public function setUri($uri);

	/**
	 * @return string
	 */
	public function getOrder();

	/**
	 * @param string $order
	 * @return void
	 */
	public function setOrder($order);

	/**
	 * @return ICompanyService
	 */
	public function getOwner();

	/**
	 * @param ICompanyService $new_owner
	 * @return void
	 */
	public function setOwner(ICompanyService $new_owner);
} 