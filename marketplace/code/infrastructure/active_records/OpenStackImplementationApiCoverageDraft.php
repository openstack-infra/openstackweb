<?php

/**
 * Class OpenStackImplementationApiCoverageDraft
 */
class OpenStackImplementationApiCoverageDraft
	extends DataObject
	implements IOpenStackImplementationApiCoverage {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'CoveragePercent' => 'Int',
	);

	static $has_one = array(
		'Implementation'             => 'OpenStackImplementationDraft',
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