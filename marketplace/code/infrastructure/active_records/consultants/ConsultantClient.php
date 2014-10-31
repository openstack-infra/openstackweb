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
 * Class Client
 */
class ConsultantClient
	extends DataObject
	implements IConsultantClient
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Name'  => 'Varchar',
		'Order' => 'Int',
	);

	static $has_one = array(
		'Consultant' => 'Consultant'
	);

	static $indexes = array(
		'Name_Owner' => array('type'=>'unique', 'value'=>'Name,ConsultantID')
	);

	/**
	* @return void
	*/
	public function getOrder()
	{
		return (int)$this->getField('Order');
	}

	/**
	 * @param int $order
	 * @return void
	 */
	public function setOrder($order)
	{
		$this->setField('Order',$order);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getField('Name');
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->setField('Name',$name);
	}

	/**
	 * @return IConsultant
	 */
	public function getConsultant()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Consultant','PreviousClients')->getTarget();
	}

	/**
	 * @param IConsultant $consultant
	 * @return void
	 */
	public function setConsultant(IConsultant $consultant)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Consultant','PreviousClients')->setTarget($consultant);
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}
}