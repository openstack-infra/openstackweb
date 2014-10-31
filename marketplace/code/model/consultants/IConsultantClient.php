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
 * Interface IConsultantClient
 */
interface IConsultantClient extends IEntity {
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
	 * @return void
	 */
	public function getOrder();

	/**
	 * @param int $order
	 * @return void
	 */
	public function setOrder($order);


	/**
	 * @return IConsultant
	 */
	public function getConsultant();

	/**
	 * @param IConsultant $consultant
	 * @return void
	 */
	public function setConsultant(IConsultant $consultant);
} 