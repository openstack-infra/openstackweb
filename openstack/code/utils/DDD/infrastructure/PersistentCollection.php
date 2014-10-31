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
 * Class PersistentCollection
 */
final class PersistentCollection extends RelationList {

	private $owner;
	private $component_set;
	private $snapshot     = array();
	private $updated_extra_fields = array();
	private $extra_fields = array();
	private $is_dirty     = false;
	private $query        = null;
	protected $items      = array();
	private  $type;
	private $association_name;


	/**
	 * @param string       $owner
	 * @param RelationList $component_set
	 * @param QueryObject  $query
	 * @param string       $type
	 * @param              $association_name
	 */
	public function __construct($owner, RelationList $component_set, QueryObject $query, $type='1-to-many',$association_name){
		$this->owner            = $owner;
		$this->type             = $type;
		$this->association_name = $association_name;
		$this->component_set    = $component_set;

		foreach($this->component_set->toArray() as $item){
			$class_name      = get_class($item);
			$id              = $item->getIdentifier();
			$item_from_cache = UnitOfWork::getInstance()->getFromCache($class_name, $id);

			if(!is_null($item_from_cache) && count($item_from_cache->toMap()) == count($item->toMap()))
				$item = $item_from_cache;
			else
				UnitOfWork::getInstance()->setToCache($item);

			$this->snapshot[spl_object_hash($item)] = $item;
		}

		foreach($this->snapshot as $key => $item){
			$this->items[$key] = $item;
		}
		$this->query = $query;
		UnitOfWork::getInstance()->loadCollection($this);
	}

	/**
	 * @return array
	 */
	public function getComponentInfo() {
		$info               = array();
		$info['ownerObj']   = $this->owner;
		$info['ownerClass'] = get_class($this->owner);
		$info['childClass'] = $this->component_set->dataClass();
		$info['type']       = $this->type;
		if($this->component_set instanceof UnsavedRelationList){
			// try to get info from components ...
			if($info['type']==='many-to-many'){
				list($parentClass, $componentClass, $parentField, $localField, $tableName) = $this->owner->many_many($this->association_name);
				$info['tableName']   = $tableName;
				$info['localField']  = $localField;
				$info['joinField']   = $parentField;
			}
			else{
				$info['joinField'] = $this->owner->getRemoteJoinField($this->association_name, $type = 'has_many');
			}
		}
		else{
			$info['joinField']  = $this->component_set->getForeignKey();
			//join table for 'many-to-many' relationship
			if($info['type']==='many-to-many'){
				$info['tableName']   = $this->component_set->getJoinTable();
				$info['localField']  = $this->component_set->getLocalKey();
			}
		}
		return $info;
	}

	function getIdList() {
		return $this->component_set->getIdList();
	}

	function add($item, $extraFields = null) {
		$identifier               = spl_object_hash($item);
		$this->items[$identifier] = $item;
		if(!is_null($extraFields)){
			if(!array_key_exists($identifier,$this->extra_fields)){
				$this->extra_fields[$identifier] = array();
			}
			//get extra fields by object
			$extra_fields = $this->extra_fields[$identifier];
			foreach($extraFields as $name => $value){
				if(!array_key_exists($name,$extra_fields))
					$extra_fields[$name] = array();
				array_push($extra_fields[$name],$value);
			}
			$this->extra_fields[$identifier] = $extra_fields;
		}
		$this->is_dirty = true;
	}

	function updateExtraFields($item, array $extraFields){
		$identifier     = spl_object_hash($item);
		//get extra fields by object
		$extra_fields = $this->extra_fields[$identifier];
		foreach($extraFields as $name => $value){
			$extra_fields[$name] = array();//reset it
			array_push($extra_fields[$name],$value);
		}
		$this->extra_fields[$identifier]         = $extra_fields;
		$this->updated_extra_fields[$identifier] = $item;
		$this->is_dirty = true;
	}

	function hasExtraFields($item){
		if(array_key_exists(spl_object_hash($item),$this->extra_fields)){
			return true;
		}
		return false;
	}

	function getExtraFields($item){
		if(array_key_exists(spl_object_hash($item),$this->extra_fields)){
			return $this->extra_fields[spl_object_hash($item)];
		}
		return false;
	}

	function addMany($items) {
		foreach($items as $item){
			$this->add($item);
		}
	}

	function remove($item) {
		if(array_key_exists(spl_object_hash($item),$this->items)){
			unset($this->items[spl_object_hash($item)]);
		}
		if(array_key_exists(spl_object_hash($item),$this->extra_fields)){
			unset($this->extra_fields[spl_object_hash($item)]);
		}
		$this->is_dirty = true;
	}

	function isDirty(){
		return $this->is_dirty;
	}

	public function getDeleteDiff()
	{
		return array_udiff_assoc(
			$this->snapshot,
			$this->items,
			function($a, $b) { return $a === $b  ? 0 : 1; }
		);
	}

	public function getInsertDiff()
	{
		return array_udiff_assoc(
			$this->items,
			$this->snapshot,
			function($a, $b) { return $a === $b   ? 0 : 1; }
		);
	}

	public function getUpdateDiff()
	{
		return $this->updated_extra_fields;
	}

	function removeAll() {
		foreach($this->items as $item){
			$this->remove($item);
		}
		$this->is_dirty = true;
	}

	public function toArray($index = null) {
		return $this->items;
	}

	public function getIterator() {
		return new ArrayIterator($this->items);
	}

	public function getQuery(){
		return $this->query;
	}

	public function getDirties(){
		$to_insert = $this->getInsertDiff();
		$remaining =  array_udiff_assoc(
			$this->items,
			$to_insert,
			function($a, $b) { return $a === $b   ? 0 : 1; }
		);
		$dirties = array();
		if(count($remaining)==0) return $dirties;
		foreach($remaining as $r){
			if($r->isChanged())
				array_push($dirties,$r);
		}
		return $dirties;
	}

	/**
	 * Returns a where clause that filters the members of this relationship to
	 * just the related items.
	 *
	 * @param $id (optional) An ID or an array of IDs - if not provided, will use the current ids as per getForeignID
	 */
	protected function foreignIDFilter($id = null)
	{
		// TODO: Implement foreignIDFilter() method.
	}
}