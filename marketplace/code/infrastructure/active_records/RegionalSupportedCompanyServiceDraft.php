<?php
/**
 * Class RegionalSupportedCompanyServiceDraft
 */
class RegionalSupportedCompanyServiceDraft
	extends CompanyServiceDraft
	implements IRegionalSupportedCompanyService
{

	static $has_many = array(
		'RegionalSupports' => 'RegionalSupportDraft',
	);

	/**
	 * @param bool $only_new_ones
	 * @return IRegionalSupport[]
	 */
	public function getRegionalSupports($only_new_ones = false)
	{
		$query = new QueryObject($this);
		$query->addOrder(QueryOrder::asc('Order'));
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'RegionalSupports',$query)->toArray();
	}

	/**
	 * @param IRegionalSupport $regional_support
	 * @return void
	 */
	public function addRegionalSupport(IRegionalSupport $regional_support)
	{
		$new_order = 0;
		$regional_supports = $this->getRegionalSupports();
		if(count($regional_supports)>0){
			$last_one  = end($regional_supports);
			$new_order = $last_one->getOrder()+1;
		}
		$regional_support->setOrder($new_order);
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'RegionalSupports')->add($regional_support);
	}


	public function clearRegionalSupports()
	{
		$regional_supports = AssociationFactory::getInstance()->getOne2ManyAssociation($this,'RegionalSupports');
		foreach($regional_supports as $regional_support){
			$regional_support->clearChannelTypes();
		}
		$regional_supports->removeAll();
	}
} 