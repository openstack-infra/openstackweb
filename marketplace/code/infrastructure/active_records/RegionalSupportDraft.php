<?php

/**
 * Class RegionalSupportDraft
 */
class RegionalSupportDraft extends DataObject implements IRegionalSupport {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Order' => 'Int',
	);

	static $has_one = array(
		'Region' => 'Region',
		'Service' => 'RegionalSupportedCompanyServiceDraft'
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