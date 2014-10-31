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
 * Interface ICompany
 */
interface ICompany extends IEntity  {

	public function getName();
	public function setName($name);

	public function getDescription();
	public function setDescription($description);

	public function getOverview();
	public function setOverview($overview);

	public function getCommitment();
	public function setCommitment($commitment);

	/**
	 * @return ITraining
	 */
	public function getDefaultTraining();

	/**
	 * @return ITraining[]
	 */
	public function getTrainings();



	/**
	 * @param IMarketPlaceType $type
	 * @return int
	 */
	public function getAllowedMarketplaceTypeInstances(IMarketPlaceType $type);

} 