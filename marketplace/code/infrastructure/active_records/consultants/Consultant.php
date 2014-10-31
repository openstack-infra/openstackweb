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
 * Class Consultant
 */
final class Consultant
	extends RegionalSupportedCompanyService
	implements IConsultant
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $has_many = array(
		'Offices'         => 'Office',
		'PreviousClients' => 'ConsultantClient',
	);

	static $many_many = array(
		'SpokenLanguages'                   => 'SpokenLanguage',
		'ConfigurationManagementExpertises' => 'ConfigurationManagementType',
		'ExpertiseAreas'                    => 'OpenStackComponent',
		'ServicesOffered'                   => 'ConsultantServiceOfferedType',
	);

	static $many_many_extraFields = array(
		'ServicesOffered' => array(
			'RegionID' => "Int",
		),
		'SpokenLanguages' => array(
			'Order' => 'Int',
		),
	);

	/**
	 * @return IOffice[]
	 */
	public function getOffices()
	{
		$query = new QueryObject(new Office);
		$query->addOrder(QueryOrder::asc('Order'));
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Offices',$query)->toArray();
	}

	/**
	 * @param IOffice $office
	 * @return void
	 */
	public function addOffice(IOffice $office)
	{
		$new_order = 0;
		$offices = $this->getOffices();
		if(count($offices)>0){
			$last_one  = end($offices);
			$new_order = $last_one->getOrder()+1;
		}
		$office->setOrder($new_order);
		$query = new QueryObject(new Office);
		$query->addOrder(QueryOrder::asc('Order'));
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Offices',$query)->add($office);
	}

	/**
	 * @return void
	 */
	public function clearOffices()
	{
		$query = new QueryObject(new Office);
		$query->addOrder(QueryOrder::asc('Order'));
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Offices',$query)->removeAll();
	}

	/**
	 * @return IConsultantClient[]
	 */
	public function getPreviousClients()
	{
		$query = new QueryObject(new ConsultantClient);
		$query->addOrder(QueryOrder::asc('Order'));
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'PreviousClients',$query)->toArray();
	}

	/**
	 * @param IConsultantClient $client
	 * @return void
	 */
	public function addPreviousClients(IConsultantClient $client)
	{
		$new_order = 0;
		$clients = $this->getPreviousClients();
		if(count($clients)>0){
			$last_one  = end($clients);
			$new_order = $last_one->getOrder()+1;
		}
		$client->setOrder($new_order);
		$query = new QueryObject(new ConsultantClient());
		$query->addOrder(QueryOrder::asc('Order'));
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'PreviousClients',$query)->add($client);
	}

	/**
	 * @return void
	 */
	public function clearClients()
	{
		$query = new QueryObject(new ConsultantClient);
		$query->addOrder(QueryOrder::asc('Order'));
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'PreviousClients',$query)->removeAll();
	}

	/**
	 * @return ISpokenLanguage[]
	 */
	public function getSpokenLanguages()
	{
		$query = new QueryObject(new SpokenLanguage);
		$query->addOrder(QueryOrder::asc('Order'));
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'SpokenLanguages',$query)->toArray();
	}

	/**
	 * @param ISpokenLanguage $language
	 * @return void
	 */
	public function addSpokenLanguages(ISpokenLanguage $language)
	{
		$query = new QueryObject(new SpokenLanguage);
		$query->addOrder(QueryOrder::asc('Order'));
		$languages = $this->getSpokenLanguages();
		$new_order = count($languages);
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'SpokenLanguages',$query)->add($language , array('Order' => $new_order));
	}

	/**
	 * @return void
	 */
	public function clearSpokenLanguages()
	{
		$query = new QueryObject(new SpokenLanguage);
		$query->addOrder(QueryOrder::asc('Order'));
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'SpokenLanguages',$query)->removeAll();
	}

	/**
	 * @return IConfigurationManagementType[]
	 */
	public function getConfigurationManagementExpertises()
	{
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'ConfigurationManagementExpertises')->toArray();
	}

	public function addConfigurationManagementExpertise(IConfigurationManagementType $expertise)
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'ConfigurationManagementExpertises')->add($expertise);
	}

	public function clearConfigurationManagementExpertises()
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'ConfigurationManagementExpertises')->removeAll();
	}

	/**
	 * @return IOpenStackComponent[]
	 */
	public function getExpertiseAreas()
	{
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'ExpertiseAreas')->toArray();
	}

	/**
	 * @param IOpenStackComponent $component
	 * @return void
	 */
	public function addExpertiseArea(IOpenStackComponent $component)
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'ExpertiseAreas')->add($component);
	}

	/**
	 * @return void
	 */
	public function clearExpertiseAreas()
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'ExpertiseAreas')->removeAll();
	}

	/**
	 * @return IConsultantServiceOfferedType[]
	 */
	public function getServicesOffered()
	{
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'ServicesOffered')->toArray();
	}

	/**
	 * @param IConsultantServiceOfferedType $service
	 * @param IRegion                       $region
	 * @return void
	 */
	public function addServiceOffered(IConsultantServiceOfferedType $service, IRegion $region)
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'ServicesOffered')->add($service, array('RegionID'=>$region->getIdentifier()));
	}

	/**
	 * @return void
	 */
	public function clearServicesOffered()
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'ServicesOffered')->removeAll();
	}

}