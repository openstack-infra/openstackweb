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
 * Class UnitOfWork
 */
final class UnitOfWork {
	/**
	 * @var UnitOfWork
	 */
	private static $instance;

	private function __construct(){}

	private function __clone(){}

	private $new_entities_identity_map                   = array();
	private $delete_entities_identity_map                = array();
	private $update_entities_identity_map                = array();
	private $loaded_collections_many_2_many_identity_map = array();
	private $loaded_collections_one_2_many_identity_map  = array();
	private $loaded_many_2_one_identity_map              = array();
	private $identity_map                                = array();
	/**
	 * @return UnitOfWork
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance = new UnitOfWork();
		}
		return self::$instance;
	}


	public function loadMany2OneAssociation(Many2OneAssociation $association){
		$owner_key       = spl_object_hash($association->getOwner());
		$association_key = md5(sprintf('%s_%s',$owner_key,$association->getName()));
		if(!array_key_exists($owner_key,$this->loaded_many_2_one_identity_map)){
			$this->loaded_many_2_one_identity_map[$owner_key] = array();
		}
		$associations = $this->loaded_many_2_one_identity_map[$owner_key];
		$associations[$association_key] = $association;
		$this->loaded_many_2_one_identity_map[$owner_key] = $associations;
	}

	public function getMany2OneAssociation(IEntity $owner, $name){
		$owner_key       = spl_object_hash($owner);
		$association_key = md5(sprintf('%s_%s',$owner_key,$name));
		if(array_key_exists($owner_key,$this->loaded_many_2_one_identity_map)){
			$associations = $this->loaded_many_2_one_identity_map[$owner_key];
			if(array_key_exists($association_key,$associations)){
				return $associations[$association_key];
			}
		}
		return false;
	}

	public function getCollection(IEntity $owner, $child_class,QueryObject $query, $type){
		$owner_key      = spl_object_hash($owner);
		$query_key      = md5(sprintf("%s_%s_%s", $query->__toString() , implode(',',$query->getAlias()), implode(',',$query->getOrder())));
		$collection_key = md5(sprintf('%s_%s_%s',$owner_key,$child_class,$query_key));
		$collections = array();

		if($type == '1-to-many') {
			if(array_key_exists($owner_key,$this->loaded_collections_one_2_many_identity_map)){
				$collections = $this->loaded_collections_one_2_many_identity_map[$owner_key];
			}
		}
		else{
			if(array_key_exists($owner_key,$this->loaded_collections_many_2_many_identity_map)){
				$collections = $this->loaded_collections_many_2_many_identity_map[$owner_key];
			}
		}
		if(array_key_exists($collection_key,$collections)){
			return $collections[$collection_key];
		}
		return false;
	}

	public function loadCollection(PersistentCollection $collection){
		$info           = $collection->getComponentInfo();
		$owner_key      = spl_object_hash($info['ownerObj']);
		$query          = $collection->getQuery();
		$query_key      = md5(sprintf("%s_%s_%s", $query->__toString() , implode(',',$query->getAlias()),implode(',',$query->getOrder())));
		$collection_key = md5(sprintf('%s_%s_%s',$owner_key,$info['childClass'],$query_key));
		if($info['type'] == '1-to-many') {
			if(!array_key_exists($owner_key,$this->loaded_collections_one_2_many_identity_map)){
				$this->loaded_collections_one_2_many_identity_map[$owner_key] = array();
			}
			$collections                  = $this->loaded_collections_one_2_many_identity_map[$owner_key];
			$collections[$collection_key] = $collection;
			$this->loaded_collections_one_2_many_identity_map[$owner_key] = $collections;
		}
		else{
			if(!array_key_exists($owner_key,$this->loaded_collections_many_2_many_identity_map)){
				$this->loaded_collections_many_2_many_identity_map[$owner_key] = array();
			}
			$collections       = $this->loaded_collections_many_2_many_identity_map[$owner_key];
			$collections[$collection_key] = $collection;
			$this->loaded_collections_many_2_many_identity_map[$owner_key] = $collections;
		}
	}

	public function getCollectionsForEntity(IEntity $entity){
		$owner_key = spl_object_hash($entity);
		if(array_key_exists($owner_key,$this->loaded_collections_one_2_many_identity_map)){
			$one_2_many = $this->loaded_collections_one_2_many_identity_map[$owner_key];
		}
		if(array_key_exists($owner_key,$this->loaded_collections_many_2_many_identity_map)){
			$many_2_many = $this->loaded_collections_many_2_many_identity_map[$owner_key];
		}
		return array($one_2_many,$many_2_many);
	}

	public function scheduleForInsert(IEntity $entity){
		$key = spl_object_hash($entity);
		$this->new_entities_identity_map[$key] = $entity;
	}

	public function scheduleForUpdate(IEntity $entity){
		$key        = spl_object_hash($entity);

		if (isset($this->delete_entities_identity_map[$key])) {
			throw new Exception('Entity is removed');
		}
		if (!isset($this->new_entities_identity_map[$key])) {
			$this->update_entities_identity_map[$key] = $entity;
		}
	}

	/**
	 * @param string $class_name
	 * @param int $id
	 * @return null|IEntity
	 */
	public function getFromCache($class_name, $id){
		if(array_key_exists($class_name,$this->identity_map)){
			$cache = $this->identity_map[$class_name];
			if(array_key_exists($id,$cache))
				return $cache[$id];
		}
		return null;
	}

	public function setToCache(IEntity $entity){
		$id         = $entity->getIdentifier();
		$class_name = get_class($entity);
		if(!array_key_exists($class_name,$this->identity_map))
			$this->identity_map[$class_name] = array();
		$this->identity_map[$class_name][$id] = $entity;
	}

	public function scheduleForDelete(IEntity $entity){
		$key = spl_object_hash($entity);
		if (isset($this->new_entities_identity_map[$key])) {
			unset($this->new_entities_identity_map[$key]);
			//do nothing
			return;
		}
		if (isset($this->update_entities_identity_map[$key])) {
			unset($this->update_entities_identity_map[$key]);
		}
		$this->delete_entities_identity_map[$key] = $entity;
	}


	private function isMarkedDirtyAtTargetSide(Many2OneAssociation $association){
		$target          = $association->getTarget();
		$collection_name = $association->getInversedBy();
		$query           = $association->getTargetCriteria();
		$child_class     = get_class($association->getOwner());
		$one_to_many     = $this->getCollection($target,$child_class,$query,'1-to-many');
		$many_to_many    = $this->getCollection($target,$child_class,$query,'many-to-many');
		$collection      = $one_to_many?$one_to_many:$many_to_many;
		if($collection && $collection->isDirty()){
			return true;
		}
		return false;
	}

	/**
	 * @param array $extraFields
	 * @return array
	 * @throws Exception
	 */
	private function processExtraFields(array $extraFields){
		$single_values    = array();
		$multi_values     = array();
		$multi_values_qty = 0;
		$queries_set      = array();

		//iterate over metadata and figure which are single values and which ones multi ones
		foreach($extraFields as $name => $values) {
			if(count($values)>1){
				//if we got a multi value then store it, so we can post process it
				$multi_values[$name] = $values;
				//check if multi value set has the same items qty than others, iow,
				//if we have several multi values sets, each one should have the same qty of items, otherwise , throw error
				if($multi_values_qty!=0 && $multi_values_qty !=count($values))
					throw Exception('Multi values extra fields must be set on equally quantity!');
				//store count
				$multi_values_qty = count($values);
			}
			else{
				//store single value row
				$single_values[$name] = $values[0];
			}
		}

		if($multi_values_qty>0){
			//if we have multi values sets , then iterate over it
			//and convert on rows to flatten the repetitive group structure
			foreach($multi_values as $name => $values){
				$i=0;
				foreach ($values as $value) {
					if((count($queries_set)-1) < $i){
						array_push($queries_set,array());
					}
					$row             = $queries_set[$i];
					$row[$name]      = $value;
					$queries_set[$i] = $row;
					$i++;
				}
			}
			//once we processed and flatened structure, add the single values
			//to each new row
			if(count($single_values)>0){
				foreach($queries_set as $row){
					foreach($single_values as $name=>$value){
						$row[$name] = $value;
					}
				}
			}
		}
		else{
			//if we have only single values, then create a single row with those values
			$queries_set[0] = $single_values;
		}
		return $queries_set;
	}

	/**
	 * Commits the UnitOfWork, executing all operations that have been postponed
	 * up to this point. The state of all managed entities will be synchronized with
	 * the database.
	 *
	 * The operations are executed in the following order:
	 *
	 * 1) All entity insertions
	 * 2) All entity updates
	 * 3) All collection deletions
	 * 4) All collection updates
	 * 5) All entity deletions
	 *
	 *
	 * @return void
	 *
	 * @throws \Exception
	 */
	public function commit(){

		foreach($this->loaded_many_2_one_identity_map as $key => $associations){
			//if marked for delete, then skip...
			if(array_key_exists($key, $this->delete_entities_identity_map)) continue;
			//if owner its already marked for update or insert , then skip write
			$delay_entity_update = array_key_exists($key,$this->new_entities_identity_map)    ||
				                   array_key_exists($key,$this->update_entities_identity_map);
			foreach($associations as $association_key => $association){
				$owner             = $association->getOwner();
				if($association->isInverseSide()){
					//if association is reverse side, dont change from here, and check if target
					//is already marked to update...
					$delay_entity_update = $this->isMarkedDirtyAtTargetSide($association);
					if($delay_entity_update){
						$key = spl_object_hash($owner);
						unset($this->new_entities_identity_map[$key]);
						unset($this->update_entities_identity_map[$key]);
					}
					continue;
				}
				if(!$association->isDirty()) continue;
				//update ownership
				$join_field = $association->getName().'ID';
				$target     = $association->getTarget();
				//if child does not exists, then create it
				if($target->ID == 0){
					$key = spl_object_hash($target);
					unset($this->new_entities_identity_map[$key]);
					unset($this->update_entities_identity_map[$key]);
					$target->write();
				}

				$owner->$join_field = $target->ID;

				if(!$delay_entity_update){
					//mark as dirty...
					if($owner->exists())
						$this->scheduleForUpdate($owner);
					else
						$this->scheduleForInsert($owner);
					$delay_entity_update = true;
				}
			}
		}

		foreach($this->new_entities_identity_map as $key => $entity){
			$entity->write();
		}

		foreach($this->update_entities_identity_map as $key => $entity){
			//is marked for deletion, skip
			if(array_key_exists($key,$this->delete_entities_identity_map)) continue;
			//is not changed, skip ...
			if(!$entity->isChanged()) continue;
			$entity->write();
		}

		foreach($this->loaded_collections_one_2_many_identity_map as $owner_key => $collections){
			foreach($collections as $collection_key => $collection){
				if($collection->isDirty()){
					$info =  $collection->getComponentInfo();
					//get deleted ones...
					$to_delete = $collection->getDeleteDiff();
					if(count($to_delete)>0){
						foreach($to_delete as $entity){
							$entity->delete();
						}
					}
					//get inserted ones
					$to_insert = $collection->getInsertDiff();
					if(count($to_insert)>0){
						foreach($to_insert as $entity){

							$entity->$info['joinField'] = $info['ownerObj']->getIdentifier();
							$entity->write();
						}
					}
					//get changed ones
					$to_update = $collection->getDirties();
					foreach($to_update as $entity){
						$entity->write();
					}
				}
			}
		}

		foreach($this->loaded_collections_many_2_many_identity_map as $owner_key => $collections){
			foreach($collections as $collection_key => $collection){
				if($collection->isDirty()){
					$info =  $collection->getComponentInfo();
					$parentField = $info['joinField'];
					$childField  = ($info['childClass'] == $info['ownerClass']) ? "ChildID" : ($info['localField']);
					$owner_id    = $info['ownerObj']->ID == 0 ? $info['ownerObj']->OldID:$info['ownerObj']->ID;
					//get deleted ones...
					$to_delete   = $collection->getDeleteDiff();
					//list of queries to execute
					$queries_2_exec   = array();
					if(count($to_delete)>0){
						foreach($to_delete as $entity){
							array_push($queries_2_exec,"DELETE FROM \"{$info['tableName']}\" WHERE \"$parentField\" = {$owner_id} AND \"$childField\" = {$entity->getIdentifier()}" );
						}
					}
					//get inserted ones
					$to_insert = $collection->getInsertDiff();
					if(count($to_insert)>0){
						foreach($to_insert as $entity){
							//if we have extra fields (data that will be added to join table as relationship metatdata...)
							if($extraFields = $collection->getExtraFields($entity)){

								foreach($this->processExtraFields($extraFields) as $row){
									$extraKeys = $extraValues = '';
									foreach($row as $name => $value){
										$extraKeys .= ", \"$name\"";
										$extraValues .= ", '" . Convert::raw2sql($value) . "'";
									}
									array_push($queries_2_exec,"INSERT INTO \"{$info['tableName']}\" (\"$parentField\",\"$childField\" $extraKeys) VALUES ({$info['ownerObj']->getIdentifier()}, {$entity->getIdentifier()} $extraValues)");
								}

							}//end of extra fields...
							else
								array_push($queries_2_exec,"INSERT INTO \"{$info['tableName']}\" (\"$parentField\",\"$childField\") VALUES ({$info['ownerObj']->getIdentifier()}, {$entity->getIdentifier()})");
						}
					}

					$to_update = $collection->getUpdateDiff();
					if(count($to_update)>0){
						foreach($to_update as $entity){
							if($extraFields = $collection->getExtraFields($entity)){
								array_push($queries_2_exec,"DELETE FROM \"{$info['tableName']}\" WHERE \"$parentField\" = {$owner_id} AND \"$childField\" = {$entity->getIdentifier()}" );
								foreach($this->processExtraFields($extraFields) as $row){
									$extraKeys = $extraValues = '';
									foreach($row as $name => $value){
										$extraKeys .= ", \"$name\"";
										$extraValues .= ", '" . Convert::raw2sql($value) . "'";
									}

									array_push($queries_2_exec,"INSERT INTO \"{$info['tableName']}\" (\"$parentField\",\"$childField\" $extraKeys) VALUES ({$info['ownerObj']->getIdentifier()}, {$entity->getIdentifier()} $extraValues)");
								}
							}
						}
					}

					//execute all stacked queries so far..
					foreach($queries_2_exec as $query_2_exec){
						DB::query($query_2_exec);
					}
					//get changed ones
					$to_update = $collection->getDirties();
					foreach($to_update as $entity){
						$entity->write();
					}
				}
			}
		}

		// Entity deletions come last and need to be in reverse commit order
		$this->delete_entities_identity_map = array_reverse($this->delete_entities_identity_map, true);
		foreach($this->delete_entities_identity_map as $key => $entity){
			$entity->delete();
		}

		// Clear up
		$this->loaded_many_2_one_identity_map              =
		$this->new_entities_identity_map                   =
		$this->update_entities_identity_map                =
		$this->loaded_collections_one_2_many_identity_map  =
		$this->loaded_collections_many_2_many_identity_map =
		$this->delete_entities_identity_map                = array();
	}

}