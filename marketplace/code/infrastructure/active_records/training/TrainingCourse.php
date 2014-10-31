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
 * Class TrainingCourse
 * @active_record
 */
class TrainingCourse
	extends DataObject
	implements ICourse {

    private static $db = array(
        'Name'        => 'Text',
        'Paid'        => 'Boolean',
        'Description' => 'HTMLText',
	    //its for online only
	    'Link'        => 'Text',
        'Online'      => 'Boolean'
    );

	private static $has_one = array(
        'TrainingService' => 'TrainingService',
        'Type'            => 'TrainingCourseType',
        'Level'           => 'TrainingCourseLevel'
    );

	private static $has_many = array(
        'Schedules' => 'TrainingCourseSchedule'
    );

	private static $many_many = array (
        'Projects'      => 'Project',
        'Prerequisites' => 'TrainingCoursePrerequisite'
    );

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getField('Name');
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->getField('Description');
	}

	/**
	 * @return bool
	 */
	public function isPaid()
	{
		return (bool)$this->getField('Paid');
	}

	/**
	 * @return void
	 */
	public function Paid()
	{
		$this->setField('Paid',true);
	}

	/**
	 * @return void
	 */
	public function Free()
	{
		$this->setField('Paid',false);
	}

	/**
	 * @return bool
	 */
	public function isOnline()
	{
		return (bool)$this->getField('Online');
	}

	/**
	 * @return void
	 */
	public function Online(){
		$this->setField('Online',true);
	}

	/**
	 * @return void
	 */
	public function Offline(){
		$this->setField('Online',false);
	}


	/**
	 * @return int
	 */
	public function getIdentifier(){
		return (int)$this->getField('ID');
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->setField('Name',$name);
	}

	/**
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description)
	{
		$this->setField('Description',$description);
	}


	/**
	 * @return string
	 */
	public function getOnlineLink()
	{
		if($this->isOnline())
			return $this->getField('Link');
		return false;
	}

	/**
	 * @param string $link
	 * @return void
	 */
	public function setOnlineLink($link)
	{
		$this->setField('Link',$link);
	}


	/**
	 * @return ICourseRelatedProject[]
	 */
	public function getRelatedProjects()
	{
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'Projects')->toArray();
	}

	/**
	 * @param ICourseRelatedProject $new_project
	 * @return void
	 */
	public function addRelatedProject(ICourseRelatedProject $new_project)
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'Projects')->add($new_project);
	}

	/**
	 * @return void
	 */
	public function clearRelatedProjects()
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'Projects')->removeAll();
	}

	/**
	 * @return ITrainingCoursePrerequisite[]
	 */
	public function getCoursePreRequisites()
	{
		return AssociationFactory::getInstance()->getMany2ManyAssociation($this,'Prerequisites')->toArray();
	}

	/**
	 * @param ITrainingCoursePrerequisite $pre_requisite
	 * @return void
	 */
	public function addCoursePreRequisite(ITrainingCoursePrerequisite $pre_requisite)
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'Prerequisites')->add($pre_requisite);
	}

	/**
	 * @return void
	 */
	public function clearCoursePreRequisites()
	{
		AssociationFactory::getInstance()->getMany2ManyAssociation($this,'Prerequisites')->removeAll();
	}

	/**
	 * @return ICourseLocation[]
	 */
	public function getLocations()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Schedules')->toArray();
	}

	/**
	 * @param ICourseLocation $location
	 * @return void
	 */
	public function addLocation(ICourseLocation $location)
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Schedules')->add($location);
	}

	/**
	 * @return mixed
	 */
	public function clearLocations()
	{
		$locations = AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Schedules');
		foreach($locations as $location){
			$location->clearDates();
		}
		$locations->removeAll();
	}

	/**
	 * @return ITrainingCourseType
	 */
	public function getCourseType()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Type')->getTarget();
	}

	/**
	 * @param ITrainingCourseType $type
	 * @return void
	 */
	public function setCourseType(ITrainingCourseType $type)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Type')->setTarget($type);
	}

	/**
	 * @return ITrainingCourseLevel
	 */
	public function getCourseLevel()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Level')->getTarget();
	}

	/**
	 * @param ITrainingCourseLevel $level
	 * @return void
	 */
	public function setCourseLevel(ITrainingCourseLevel $level)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Level')->setTarget($level);
	}

	/**
	 * @return ITraining
	 */
	public function getTraining()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'TrainingService','Courses')->getTarget();
	}

	/**
	 * @param ITraining $training
	 * @return void
	 */
	public function setTraining(ITraining $training)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'TrainingService','Courses')->setTarget($training);
	}
}