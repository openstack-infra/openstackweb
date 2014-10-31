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
 * Class OpenStackReleaseSupportedApiVersion
 */
class OpenStackReleaseSupportedApiVersion
	extends DataObject
	implements IReleaseSupportedApiVersion
{

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $has_one = array(
		'OpenStackComponent'  => 'OpenStackComponent',
		'ApiVersion'          => 'OpenStackApiVersion',
		'Release'             => 'OpenStackRelease',
	);

	static $indexes = array(
		'Component_ApiVersion_Release' => array('type'=>'unique', 'value'=>'OpenStackComponentID,ApiVersionID,ReleaseID')
	);

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @param IOpenStackApiVersion $version
	 * @return void
	 */
	public function setApiVersion(IOpenStackApiVersion $version)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'ApiVersion')->setTarget($version);
	}

	/**
	 * @return IOpenStackApiVersion
	 */
	public function getApiVersion()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'ApiVersion')->getTarget();
	}

	/**
	 * @param IOpenStackRelease $release
	 * @return void
	 */
	public function setRelease(IOpenStackRelease $release)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Release','SupportedApiVersions')->setTarget($release);
	}

	/**
	 * @return IOpenStackRelease
	 */
	public function getRelease()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Release','SupportedApiVersions')->getTarget();
	}

	/**
	 * @param IOpenStackComponent $component
	 * @return void
	 */
	public function setOpenStackComponent(IOpenStackComponent $component)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'OpenStackComponent')->setTarget($component);
	}

	/**
	 * @return IOpenStackComponent
	 */
	public function getOpenStackComponent()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'OpenStackComponent')->getTarget();
	}
}