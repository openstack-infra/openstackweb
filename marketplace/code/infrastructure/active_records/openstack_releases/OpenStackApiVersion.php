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
 * Class OpenStackApiVersion
 */
class OpenStackApiVersion extends DataObject implements IOpenStackApiVersion {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Version' => 'Varchar',
		'Status'  => "Enum('Deprecated, Current, Beta, Alpha' , 'Deprecated')",
	);

	static $summary_fields = array(
		'Version' => 'Version',
		'Status'  => 'Status',
	);

	static $indexes = array(
		'Version_Component' => array('type'=>'unique', 'value'=>'Version,OpenStackComponentID'),
	);

	static $has_one = array(
		'OpenStackComponent' => 'OpenStackComponent',
	);

	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->OpenStackComponentID = $this->getReleaseComponent()->getIdentifier();
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @param string $version
	 * @return void
	 */
	public function setVersion($version)
	{
		$this->setField('Version',$version);
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->getField('Version');
	}

	/**
	 * @return IOpenStackComponent
	 */
	public function getReleaseComponent()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'OpenStackComponent','Versions')->getTarget();
	}

	/**
	 * @param IOpenStackComponent $new_component
	 * @return void
	 */
	public function setReleaseComponent(IOpenStackComponent $new_component)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'OpenStackComponent','Versions')->setTarget($new_component);
	}

	/**
	 * @return string
	 */
	public function getStatus()
	{
		return $this->getField('Status');
	}

	/**
	 * @param string $status
	 * @return void
	 */
	public function setStatus($status)
	{
		$this->setField('Status',$status);
	}
}