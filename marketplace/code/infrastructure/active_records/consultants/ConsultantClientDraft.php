<?php
/**
 * Class ConsultantClientDraft
 */
class ConsultantClientDraft
	extends DataObject
	implements IConsultantClient
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Name'  => 'Varchar',
		'Order' => 'Int',
	);

	static $has_one = array(
		'Consultant' => 'ConsultantDraft'
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