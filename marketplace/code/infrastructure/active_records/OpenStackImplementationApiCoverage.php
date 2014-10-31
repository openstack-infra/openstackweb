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
 * Class OpenStackImplementationApiCoverage
 */
class OpenStackImplementationApiCoverage
	extends DataObject
	implements IOpenStackImplementationApiCoverage {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'CoveragePercent' => 'Int',
	);

	static $has_one = array(
		'Implementation'             => 'OpenStackImplementation',
		'ReleaseSupportedApiVersion' => 'OpenStackReleaseSupportedApiVersion',
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
	public function getCoveragePercent()
	{
		return (int)$this->getField('CoveragePercent');
	}

	/**
	 * @param int $coverage
	 * @return void
	 */
	public function setCoveragePercent($coverage)
	{
		$this->setField('CoveragePercent',$coverage);
	}

	/**
	 * @return IOpenStackImplementation
	 */
	public function getImplementation()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Implementation','Capabilities')->getTarget();
	}

	/**
	 * @param IOpenStackImplementation $implementation
	 * @return void
	 */
	public function setImplementation(IOpenStackImplementation $implementation)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Implementation','Capabilities')->setTarget($implementation);
	}

	/**
	 * @return IReleaseSupportedApiVersion
	 */
	public function getReleaseSupportedApiVersion()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'ReleaseSupportedApiVersion')->getTarget();
	}

	/**
	 * @param IReleaseSupportedApiVersion $release_supported_api_version
	 * @return void
	 */
	public function setReleaseSupportedApiVersion(IReleaseSupportedApiVersion $release_supported_api_version)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'ReleaseSupportedApiVersion')->setTarget($release_supported_api_version);
	}

	/**
	 * @return bool
	 */
	public function SupportsVersioning()
	{
		$supported_version = $this->getReleaseSupportedApiVersion();
		if(!$supported_version) return false;
		$component = $supported_version->getOpenStackComponent();
		if(!$component) return false;
		return $component->getSupportsVersioning();
	}
}