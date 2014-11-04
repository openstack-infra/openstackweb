<?php

/**
 * Class CompanyServiceResourceDraft
 */
class CompanyServiceResourceDraft extends DataObject implements ICompanyServiceResource {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Name'      => 'Varchar',
		'Uri'       => 'Text',
		'Order'     => 'Int',
	);


	static $has_one = array(
		'Owner' => 'CompanyServiceDraft',
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