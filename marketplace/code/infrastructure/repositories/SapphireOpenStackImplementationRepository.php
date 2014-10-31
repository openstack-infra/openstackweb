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
 * Class SapphireOpenStackImplementationRepository
 */
abstract class SapphireOpenStackImplementationRepository
	extends SapphireRegionalSupportedCompanyServiceRepository
	implements IOpenStackImplementationRepository
{

	public function getWithCapabilitiesEnabled(QueryObject $query, $offset = 0, $limit = 10){
		$class = $this->entity_class;
		$do    = $class::get()->where(" EXISTS( SELECT ID FROM OpenStackImplementationApiCoverage C WHERE ImplementationID = CompanyService.ID) ")->order($query->getOrder())->limit($limit,$offset);
		if(is_null($do)) return array(array(),0);
		$res    = $do->toArray();
		return array($res, (int) $do->count());
	}

	public function delete(IEntity $entity){
		$entity->clearCapabilities();
		$entity->clearHypervisors();
		$entity->clearGuests();
		parent::delete($entity);
	}
}
