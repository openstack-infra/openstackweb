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
 * Class MarketPlaceVideo
 */
class MarketPlaceVideo extends DataObject implements IMarketPlaceVideo {

	static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	static $db = array(
		'Name'         => 'Text',
		'Description'  => 'Text',
		'YouTubeID'    => 'Text',
		//seconds
		'Length'       => 'int',
	);

	static $has_one = array(
		'Type'  => 'MarketPlaceVideoType',
		'Owner' => 'CompanyService',
	);

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @param IMarketPlaceVideoType $type
	 * @return void
	 */
	public function setType(IMarketPlaceVideoType $type)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Type')->setTarget($type);
	}

	/**
	 * @return IMarketPlaceVideoType
	 */
	public function getType()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Type')->getTarget();
	}

	public function getName()
	{
		return $this->getField('Name');
	}

	public function setName($name)
	{
		$this->setField('Name',$name);
	}

	public function getDescription()
	{
		return $this->getField('Description');
	}

	public function setDescription($description)
	{
		$this->setField('Description',$description);
	}

	public function getLength()
	{
		return (int)$this->getField('Length');
	}

	public function setLength($length)
	{
		$this->setField('Length',$length);
	}

	public function setYouTubeId($you_tube_id)
	{
		$this->setField('YouTubeID',$you_tube_id);
	}

	public function getYouTubeId()
	{
		return $this->getField('YouTubeID');
	}

	/**
	 * @return ICompanyService
	 */
	public function getOwner()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Owner','Videos')->getTarget();
	}

	/**
	 * @param ICompanyService $owner
	 * @return void
	 */
	public function setOwner(ICompanyService $owner)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Owner','Videos')->setTarget($owner);
	}

	public function getFormattedLength()
	{
		$len = $this->getLength();
		return sprintf('%02d', floor($len / 60)).sprintf(':%02d', (int) $len % 60);
	}
}