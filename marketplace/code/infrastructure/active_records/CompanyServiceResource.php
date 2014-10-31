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
 * Class CompanyServiceResource
 */
class CompanyServiceResource extends DataObject implements ICompanyServiceResource {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Name'      => 'Varchar',
		'Uri'       => 'Text',
		'Order'     => 'Int',
	);


	static $has_one = array(
		'Owner' => 'CompanyService',
	);

	static $indexes = array(
		'Owner_Name' => array('type'=>'unique', 'value'=>'Name, OwnerID')
	);

	public function getName()
	{
		return $this->getField('Name');
	}

	public function setName($name)
	{
		return $this->setField('Name', substr(trim($name),0,250));
	}

	public function getUri()
	{
		return $this->getField('Uri');
	}

	public function setUri($uri)
	{
		return $this->setField('Uri', trim($uri));
	}

	/**
	 * @return ICompanyService
	 */
	public function getOwner()
	{
		$query = new QueryObject($this);
		$query->addOrder(QueryOrder::asc('Order'));
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Owner','Resources',$query)->getTarget();
	}

	/**
	 * @param ICompanyService $new_owner
	 */
	public function setOwner(ICompanyService $new_owner)
	{
		$query = new QueryObject($this);
		$query->addOrder(QueryOrder::asc('Order'));
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Owner','Resources',$query)->setTarget($new_owner);
	}
	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	public function getOrder()
	{
		return (int)$this->getField('Order');
	}

	public function setOrder($order)
	{
		return $this->setField('Order', (int)$order);
	}
}