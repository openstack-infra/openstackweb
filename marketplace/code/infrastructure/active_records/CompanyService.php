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
 * Class CompanyService
 * @remark base class
 */
class CompanyService
	extends DataObject
	implements ICompanyService {

	private static $create_table_options = array('MySQLDatabase' => 'ENGINE=InnoDB');

	private static $db = array(
		'Name'           => 'Varchar(255)',
		'Slug'           => 'Varchar(255)',
		'Overview'       => 'HTMLText',
		'Call2ActionUri' => 'Text',
		'Active'         => 'Boolean',
	);

	private static $has_one = array(
		'Company'          => 'Company',
		'MarketPlaceType'  => 'MarketPlaceType',
		'EditedBy'         => 'Member',
	);

	private static $indexes = array(
		'Company_Name_Class' => array('type'=>'unique', 'value'=>'Name,CompanyID,ClassName')
	);

	static $has_many = array(
		'Resources'  => 'CompanyServiceResource',
		'Videos'     => 'MarketPlaceVideo',
	);

	protected function onBeforeWrite() {
		//generate slug...
		$this->Slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->getName())));
		$current_user = Member::currentUser();
		if($current_user){
			$this->EditedByID = $current_user->ID;
		}
		parent::onBeforeWrite();
	}

	/**
	 * @return IMarketPlaceType
	 */
	protected function getDefaultMarketPlaceType(){
		return null;
	}

    public function isDraft()
    {
        return false;
    }

	public function setCompany(ICompany $company)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Company')->setTarget($company);
	}

	public function getCompany()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Company')->getTarget();
	}

	public function setMarketplace(IMarketPlaceType $marketplace)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'MarketPlaceType')->setTarget($marketplace);
	}

	public function getMarketplace(){
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'MarketPlaceType')->getTarget();
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
		$this->setField('Name',trim($name));
	}

	public function getOverview()
	{
		return $this->getField('Overview');
	}

	public function setOverview($overview)
	{
		$this->setField('Overview',trim($overview));
	}

	public function getCall2ActionUri()
	{
		return $this->getField('Call2ActionUri');
	}

	public function setCall2ActionUri($call_2_action_uri)
	{
		$this->setField('Call2ActionUri', trim($call_2_action_uri));
	}

	/**
	 * @return bool
	 */
	public function isActive()
	{
		return (bool)$this->getField('Active');
	}

	/**
	 * @return void
	 */
	public function activate()
	{
		$this->setField('Active',true);
	}

	/**
	 * @return void
	 */
	public function deactivate()
	{
		$this->setField('Active',false);
	}

	public function addResource(ICompanyServiceResource $resource){

		$new_order = 0;
		$resources = $this->getResources();
		if(count($resources)>0){
			$last_one  = end($resources);
			$new_order = $last_one->getOrder()+1;
		}
		$resource->setOrder($new_order);
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Resources')->add($resource);
	}

	public function getResources(){
		$query = new QueryObject($this);
		$query->addOrder(QueryOrder::asc('Order'));
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Resources',$query)->toArray();
	}

	/**
	 * @param array $new_sort
	 */
	public function sortResources(array $new_sort)
	{
		foreach($this->getResources() as $resource){
			$new_order = array_search($resource->getIdentifier(),$new_sort);
			if(!$new_order) continue;
			$resource->setOrder($new_order);
		}
	}

	/**
	 * @param IMarketPlaceVideo $video
	 * @return void
	 */
	public function addVideo(IMarketPlaceVideo $video)
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Videos')->add($video);
	}

	/**
	 * @return IMarketPlaceVideo[]
	 */
	public function getVideos()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Videos')->toArray();
	}

	public function clearVideos()
	{

		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Videos')->removeAll();
	}

	public function clearResources()
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Resources')->removeAll();
	}

	/**
	 * @return string
	 */
	public function getSlug()
	{
		return $this->getField('Slug');
	}
}