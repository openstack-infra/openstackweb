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
 * Class OpenStackComponent
 */
class OpenStackComponent extends DataObject implements IOpenStackComponent {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Name'                 => 'Varchar',
		'CodeName'             => 'Varchar',
		'Description'          => 'Text',
		'SupportsVersioning'   => 'Boolean',
		'SupportsExtensions'   => 'Boolean',
	);

	static $has_many = array(
		'Versions' => 'OpenStackApiVersion',
	);

	static $belongs_many_many = array(
		"Releases" => "OpenStackRelease",
	);

	static $indexes = array(
		'Name'     => array('type'=>'unique', 'value'=>'Name'),
		'CodeName' => array('type'=>'unique', 'value'=>'CodeName')
	);

	protected function onBeforeWrite() {
		parent::onBeforeWrite();
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	public function getName()
	{
		return $this->getField('Name');
	}

	public function setName($name)
	{
		$this->setField('Name',$name);
	}

	public function getCodeName()
	{
		return $this->getField('CodeName');
	}

	public function setCodeName($codename)
	{
		$this->setField('CodeName',$codename);
	}

	public function getDescription()
	{
		return $this->getField('Description');
	}

	public function setDescription($description)
	{
		$this->setField('Description',$description);
	}

	/**
	 * @return IOpenStackApiVersion[]
	 */
	public function getVersions()
	{
		if(!$this->getSupportsVersioning())
			throw new Exception('Component does not supports api versioning');
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Versions')->toArray();
	}

	/**
	 * @param IOpenStackApiVersion $new_version
	 * @return void
	 */
	public function addVersion(IOpenStackApiVersion $new_version)
	{
		if(!$this->getSupportsVersioning())
			throw new Exception('Component does not supports api versioning');
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Versions')->add($new_version);
	}

	/**
	 * @param int $version_id
	 * @return bool
	 */
	public function hasVersion($version_id)
	{
		foreach($this->getVersions() as $version){
			if($version->getIdentifier()==$version_id)
				return true;
		}
		return false;
	}

	/**
	 * @return IOpenStackRelease[]
	 */
	public function getSupportedReleases()
	{
		return $this->getManyManyComponents('Releases','','OpenStackRelease.ReleaseDate')->toArray();
	}

	public function getSupportsVersioning()
	{
		return (bool)$this->getField('SupportsVersioning');
	}

	public function setSupportsVersioning($supports_versioning)
	{
		$this->setField('SupportsVersioning',$supports_versioning);
	}

	public function clearVersions()
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Versions')->removeAll();
	}

	/**
	 * @return bool
	 */
	public function getSupportsExtensions()
	{
		return (bool)$this->getField('SupportsExtensions');
	}

	/**
	 * @param bool $supports_extensions
	 * @return void
	 */
	public function setSupportsExtensions($supports_extensions)
	{
		$this->setField('SupportsExtensions',$supports_extensions);
	}
}