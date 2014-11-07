<?php

/**
 * Class CloudServiceOfferedDraft
 */
class CloudServiceOfferedDraft
	extends OpenStackImplementationApiCoverageDraft
	implements ICloudServiceOffered
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');


	static $many_many = array(
		'PricingSchemas' => 'PricingSchemaType',
	);

	/**
	 * @return IPricingSchemaType[]
	 */
	public function getPricingSchemas()
	{
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'PricingSchemas')->toArray();
	}

	/**
	 * @param IPricingSchemaType $pricing_schema
	 * @return void
	 */
	public function addPricingSchema(IPricingSchemaType $pricing_schema)
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'PricingSchemas')->add($pricing_schema);
	}

	public function clearPricingSchemas(){
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'PricingSchemas')->removeAll();
	}
}