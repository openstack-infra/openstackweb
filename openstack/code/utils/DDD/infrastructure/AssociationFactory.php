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
 * Class AssociationFactory
 */
final class AssociationFactory  {

	/**
	 * @var AssociationFactory
	 */
	private static $instance;

	private function __construct(){}

	private function __clone(){}

	/**
	 * @return AssociationFactory
	 */

	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance = new AssociationFactory;
		}
		return self::$instance;
	}

	/**
	 * @param DataObject  $entity
	 * @param             $association_name
	 * @param QueryObject $query
	 * @return bool|PersistentCollection
	 * @throws Exception
	 */
	public function getOne2ManyAssociation(DataObject $entity, $association_name, QueryObject $query = null){
		if(is_null($query)) $query = new QueryObject;
		$child_class = $entity->has_many($association_name);
		if(!$child_class) throw new Exception(sprintf("entity %s has not an one-to-many association called %s",get_class($entity),$association_name));
		$old = UnitOfWork::getInstance()->getCollection($entity,$child_class,$query,'1-to-many');
		if($old) return $old;
		$component = $entity->getComponents($association_name, (string)$query, $query->getOrder(true), $query->getAlias(),"");
		return new PersistentCollection($entity, $component, $query,'1-to-many',$association_name);
	}

	/**
	 * @param DataObject  $entity
	 * @param             $association_name
	 * @param QueryObject $query
	 * @return bool|PersistentCollection
	 * @throws Exception
	 */
	public function getMany2ManyAssociation(DataObject $entity, $association_name, QueryObject $query = null){
		if(is_null($query)) $query= new QueryObject;
		list($parentClass, $componentClass, $parentField, $componentField, $table) = $entity->many_many($association_name);
		if(!$componentClass) throw new Exception(sprintf("entity %s has not a many-to-many association called %s",get_class($entity),$association_name));
		$old = UnitOfWork::getInstance()->getCollection($entity,$componentClass,$query,'many-to-many');
		if($old) return $old;
		$component = $entity->getManyManyComponents($association_name,(string)$query, $query->getOrder(), $query->getAlias(),"");
		return new PersistentCollection($entity,$component, $query, 'many-to-many',$association_name);
	}

	/**
	 * @param DataObject  $entity
	 * @param             $association_name
	 * @param null        $inversed_by
	 * @param QueryObject $target_query
	 * @return bool|Many2OneAssociation
	 * @throws Exception
	 */
	public function getMany2OneAssociation(DataObject $entity, $association_name, $inversed_by =  null, QueryObject $target_query = null){
		$class_name =  $entity->has_one($association_name);
		if(!$class_name) throw new Exception(sprintf("entity %s has not an many-to-one association called %s",get_class($entity),$association_name));
		$old = UnitOfWork::getInstance()->getMany2OneAssociation($entity,$association_name);
		if($old) return $old;
		return new Many2OneAssociation($entity,$association_name,$inversed_by,$target_query);
	}
}