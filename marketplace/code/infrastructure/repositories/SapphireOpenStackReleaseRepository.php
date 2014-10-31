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
class SapphireOpenStackReleaseRepository
	extends SapphireRepository
implements  IOpenStackReleaseRepository
{

	public function __construct(){
		parent::__construct(new OpenStackRelease);
	}

	/**
	 * @param IEntity $entity
	 * @return int
	 */
	public function add(IEntity $entity)
	{
		//supported components
		foreach($entity->getOpenStackComponents(true) as $component){
			$entity->getManyManyComponents('Components')->Add($component);
		}
		//supported versions
		foreach($entity->getSupportedApiVersions(true) as $supported_version){
			$entity->getComponents('SupportedApiVersions')->add($supported_version);
		}

		return $entity->write();
	}

	/**
	 * @param string $name
	 * @return IOpenStackRelease
	 */
	public function getByName($name)
	{
		$class = $this->entity_class;
		return $class::get()->filter('Name',$name)->first();
	}

	/**
	 * @param string $release_number
	 * @return IOpenStackRelease
	 */
	public function getByReleaseNumber($release_number)
	{
		$class = $this->entity_class;
		return $class::get()->filter('ReleaseNumber', $release_number)->first();
	}
}