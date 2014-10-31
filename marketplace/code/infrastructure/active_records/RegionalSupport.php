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
 * Class RegionalSupport
 */
class RegionalSupport extends DataObject implements IRegionalSupport {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Order' => 'Int',
	);

	static $has_one = array(
		'Region' => 'Region',
		'Service' => 'RegionalSupportedCompanyService'
	);

	static $indexes = array(
		'Region_Service' => array('type'=>'unique', 'value'=>'RegionID,ServiceID')
	);

	static $many_many = array(
		'SupportChannelTypes' => 'SupportChannelType'
	);

	static $many_many_extraFields = array(
		'SupportChannelTypes' => array(
			'Data' => "Varchar",
		),
	);

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @return int
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
	 * @return IRegion
	 */
	public function getRegion()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Region')->getTarget();
	}

	/**
	 * @param IRegion $region
	 * @return void
	 */
	public function setRegion(IRegion $region)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Region')->setTarget($region);
	}

	public function getSupportChannelTypes()
	{
		$query = new QueryObject($this);
		$query->addOrder(QueryOrder::asc('SupportChannelTypeID'));
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'SupportChannelTypes', $query)->toArray();
	}

	/**
	 * @param ISupportChannelType $channel_type
	 * @param string              $data
	 * @return void
	 */
	public function addSupportChannelType(ISupportChannelType $channel_type, $data)
	{
		$query = new QueryObject($this);
		$query->addOrder(QueryOrder::asc('SupportChannelTypeID'));
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'SupportChannelTypes')->add($channel_type, array('Data'=>$data));
	}

	public function clearChannelTypes(){
		$query = new QueryObject($this);
		$query->addOrder(QueryOrder::asc('SupportChannelTypeID'));
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'SupportChannelTypes')->removeAll();
	}

	/**
	 * @return IRegionalSupportedCompanyService
	 */
	public function getCompanyService()
	{
		$query = new QueryObject($this);
		$query->addOrder(QueryOrder::asc('Order'));
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Service','RegionalSupports',$query)->getTarget();
	}

	/**
	 * @param IRegionalSupportedCompanyService $company_service
	 * @return void
	 */
	public function setCompanyService(IRegionalSupportedCompanyService $company_service)
	{
		$query = new QueryObject($this);
		$query->addOrder(QueryOrder::asc('Order'));
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Service','RegionalSupports',$query)->setTarget($company_service);
	}
}