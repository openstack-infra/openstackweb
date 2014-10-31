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
 * Class AbstractEntityRepository
 */
abstract class AbstractEntityRepository implements IEntityRepository {

	/**
	 * @var string
	 */
	protected $entity_class;

	/**
	 * @param IEntity $entity
	 */
	public function __construct(IEntity $entity){
		if($entity instanceof DataExtension)
			$this->entity_class = get_class($entity->getOwner());
		else
			$this->entity_class = get_class($entity);
	}
} 