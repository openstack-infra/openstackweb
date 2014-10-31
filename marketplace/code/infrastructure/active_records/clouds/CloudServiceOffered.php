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
 * Class CloudServiceOffered
 */
class CloudServiceOffered
	extends OpenStackImplementationApiCoverage
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