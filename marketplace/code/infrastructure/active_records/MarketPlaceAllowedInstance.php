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
 * Class MarketPlaceAllowedInstance
 */
final class MarketPlaceAllowedInstance extends DataObject implements  IMarketPlaceAllowedInstance {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'MaxInstances' => 'Int',
	);

	static $indexes = array(
		'Type' => array('type'=>'unique', 'value'=>'MarketPlaceTypeID,CompanyID')
	);

	static $has_one = array(
		'MarketPlaceType'  => 'MarketPlaceType',
		'Company' => 'Company',
	);


	public static $summary_fields = array(
		'MaxInstances',
		'MarketPlaceType.Name',
		'Company.Name',
	);

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	public function getMaxInstances()
	{
		return (int)$this->getField('MaxInstances');
	}

	public function setMaxInstances($max_instances)
	{
		$this->setField('MaxInstances',$max_instances);
	}


	/**
	 * @param IMarketPlaceType $type
	 * @return void
	 */
	public function setType(IMarketPlaceType $type)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'MarketPlaceType')->setTarget($type);
	}

	/**
	 * @return IMarketPlaceType
	 */
	public function getType()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'MarketPlaceType')->getTarget();
	}


	/**
	 * @param ICompany $company
	 * @return void
	 */
	public function setCompany(ICompany $company)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Company')->setTarget($company);
	}

	/**
	 * @return ICompany
	 */
	public function getCompany()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Company')->getTarget();
	}
} 