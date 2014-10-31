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
 * Class OpenStackImplementation
 */
class OpenStackImplementation
	extends RegionalSupportedCompanyService
	implements IOpenStackImplementation {

	static $many_many = array(
		'HyperVisors'     => 'HyperVisorType',
		'Guests'          => 'GuestOSType',
	);

	static $has_many = array(
		'RegionalSupports' => 'RegionalSupport',
		'Capabilities'     => 'OpenStackImplementationApiCoverage'
	);

	/**
	 * @return IHyperVisorType[]
	 */
	public function getHyperVisors()
	{
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'HyperVisors')->toArray();
	}

	/**
	 * @param IHyperVisorType $hypervisor
	 * @return void
	 */
	public function addHyperVisor(IHyperVisorType $hypervisor)
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'HyperVisors')->add($hypervisor);
	}

	/**
	 * @return IGuestOSType[]
	 */
	public function getGuests()
	{
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'Guests')->toArray();
	}

	/**
	 * @param IGuestOSType $guest
	 * @return void
	 */
	public function addGuest(IGuestOSType $guest)
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'Guests')->add($guest);
	}


	/**
	 * @return array|IOpenStackImplementationApiCoverage[]
	 */
	public function getCapabilities()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Capabilities')->toArray();
	}

	/**
	 * @param IOpenStackImplementationApiCoverage $capability
	 * @return void
	 */
	public function addCapability(IOpenStackImplementationApiCoverage $capability)
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Capabilities')->add($capability);
	}

	public function clearCapabilities()
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Capabilities')->removeAll();
	}

	public function clearHypervisors()
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'HyperVisors')->removeAll();
	}

	public function clearGuests()
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'Guests')->removeAll();
	}

}