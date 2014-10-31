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
 * Class QueryObject
 */
final class QueryObject {

	private $and_conditions = array();
	private $or_conditions = array();
	private $order_conditions = array();
	private $alias = array();
	private $base_entity;

	public function __construct(IEntity $base_entity = null){
		$this->base_entity = $base_entity;
	}

	/**
	 * @param QueryCriteria $condition
	 * @return QueryObject
	 */
	public function addAddCondition(QueryCriteria $condition){
		array_push($this->and_conditions,$condition);
		return $this;
	}

	/**
	 * @param QueryCriteria $condition
	 * @return QueryObject
	 */
	public function addOrCondition(QueryCriteria $condition){
		array_push($this->or_conditions, $condition);
		return $this;
	}

	public function addOrCompound(QueryCriteria $condition1, QueryCriteria $condition2, QueryCriteria $condition3=null){
		$compound = array($condition1,$condition2);
		if(!is_null($condition3))
			array_push($compound, $condition3);
		array_push($this->and_conditions, array('op' => 'OR', 'conditions' => $compound));
		return $this;
	}

	/**
	 * @param QueryOrder $order
	 * @return QueryObject
	 */
	public function addOrder(QueryOrder $order){
		array_push($this->order_conditions, $order);
		return $this;
	}

	public function __toString(){
		$query = '';

		foreach($this->and_conditions as $condition){
			if(!empty($query))
				$query.= 'AND';
			if(!is_array($condition))
				$query .= (string)$condition;
			else{
				$op = @$condition['op'];
				switch($op){
					case 'OR':{
						$query .= ' (';
						$sub_conditions = @$condition['conditions'];
						foreach($sub_conditions as $cnd){
							$query .= (string)$cnd. ' OR';
						}
						$query = trim($query,'OR');
						$query .= ') ';
					}
					break;
				}
			}
		}

		foreach($this->or_conditions as $condition){
			if(!empty($query))
				$query.= 'OR';
			$query .= (string)$condition;
		}
		return $query;
	}

	public function addAlias(QueryAlias $alias){
		array_push($this->alias,$alias);
		return $this;
	}

	public function getOrder(){
		$res = array();
		foreach($this->order_conditions as $condition){
				$res[$condition->getField()] = $condition->getDir();
		}
		return $res;
	}

	public function getAlias(){
		$join = array();
		foreach($this->alias as $alias){
			$child            = $alias->getName();
			$has_many         = Config::inst()->get(get_class($this->base_entity), 'has_many');
			$class_name       = ClassInfo::baseDataClass($this->base_entity);

			if(!is_null($has_many)){
				$has_many_classes = array_flip($has_many);
				if(array_key_exists($child,$has_many_classes)){
					$tableClasses = ClassInfo::dataClassesFor($child);
					$baseClass    = array_shift($tableClasses);
					$joinField    = $this->base_entity->getRemoteJoinField($has_many_classes[$child], 'has_many');
					$join[$baseClass] = $baseClass.'.'.$joinField.' = '.$class_name.'.ID';
					$this->addAddCondition(QueryCriteria::equal("{$baseClass}.ClassName",$child));
				}
			}


			$has_many_many   = Config::inst()->get(get_class($this->base_entity), 'many_many');
			if(!is_null($has_many_many)){
				$has_many_many_classes = array_flip($has_many_many);
				if(array_key_exists($child, $has_many_many_classes)){
					$base_entity_name = get_class($this->base_entity);
					$component        = $has_many_many_classes[$child];
					$joinTable        = "{$base_entity_name}_{$component}";
					$parentField      = $base_entity_name . "ID";
					$childField       =  $child . "ID";
					$join[$joinTable] = $joinTable.'.'.$parentField.' = '.$class_name.'.ID';
					$join[$child]     = $child.'.ID = '.$joinTable.'.'.$childField;
				}
			}


			$has_one   = Config::inst()->get(get_class($this->base_entity), 'has_one');
			if(!is_null($has_one)){
				$has_one_classes = array_flip($has_one);
				if(array_key_exists($child,$has_one_classes)){

					$join[$child] = $child.'.ID = '.$class_name.'.'.$has_one_classes[$child].'ID';
				}
			}

			$belongs_many_many   = Config::inst()->get(get_class($this->base_entity), 'belongs_many_many');
			if(!is_null($belongs_many_many)){
				$belongs_many_many_classes = array_flip($belongs_many_many);
				if(array_key_exists($child,$belongs_many_many_classes)){
					$child_many_many = Config::inst()->get($child, 'many_many');
					$child_many_many_classes = array_flip($child_many_many);
					$component_name = $child_many_many_classes[$class_name];
					list($parentClass, $componentClass, $child_join_field, $join_field, $join_table) = Singleton($child)->many_many($component_name);
					$join[$join_table] = $join_table.'`.'.$join_field.' = `'.$class_name.'`.ID';
					$join[$child]      = $child.'`.ID = `'.$join_table.'`.'.$child_join_field;
				}
			}
			if($alias->hasSubAlias()){
				$join = array_merge($join, $alias->subAlias());
			}
		}
		return $join;
	}

	/**
	 * Clear conditions
	 */
	public function clear(){
		$this->and_conditions   = array();
		$this->or_conditions    = array();
		$this->order_conditions = array();
		$this->alias            = array();
	}
} 