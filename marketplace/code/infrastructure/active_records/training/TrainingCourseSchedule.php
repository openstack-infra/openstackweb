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
 * Class TrainingCourseSchedule
 * @active_record
 */
class TrainingCourseSchedule
	extends DataObject
	implements ICourseLocation {


    static $db = array(
        'City'    => 'Text',
        'State'   => 'Text',
        'Country' => 'Text',
    );

    static $has_one = array(
        'Course' => 'TrainingCourse',
    );

    static $has_many = array(
        'Times' => 'TrainingCourseScheduleTime'
    );

    function canCreate($member=null) {
        if (!Permission::check(ITraining::MarketPlacePermissionSlug)) {
            return false;
        }
        return true;
    }

    function canEdit($member=null) {
        if (!Permission::check(ITraining::MarketPlacePermissionSlug)) {
            return false;
        }
        return true;
    }

    function canDelete($member=null) {
        if (!Permission::check(ITraining::MarketPlacePermissionSlug)) {
            return false;
        }
        return true;
    }

	public function getCountry()
	{
		return $this->getField('Country');
	}

	public function setCountry($country)
	{
		$this->setField('Country',$country);
	}

	public function getState()
	{
		return $this->getField('State');
	}

	public function setState($state)
	{
		$this->setField('State',$state);
	}

	public function getCity()
	{
		return $this->getField('City');
	}

	public function setCity($city)
	{
		$this->setField('City',$city);
	}

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @return ICourse
	 */
	public function getAssociatedCourse()
	{
		return AssociationFactory::getInstance()->getMany2OneAssociation($this,'Course','Schedules')->getTarget();
	}

	/**
	 * @param ICourse $course
	 * @return void
	 */
	public function setAssociatedCourse(ICourse $course)
	{
		AssociationFactory::getInstance()->getMany2OneAssociation($this,'Course','Schedules')->setTarget($course);
	}

	/**
	 * @return IScheduleTime[]
	 */
	public function getDates()
	{
		return AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Times')->toArray();
	}

	/**
	 * @param IScheduleTime $date
	 * @return void
	 */
	public function addDate(IScheduleTime $date)
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Times')->add($date);
	}

	/**
	 * @return void
	 */
	public function clearDates()
	{
		AssociationFactory::getInstance()->getOne2ManyAssociation($this,'Times')->removeAll();
	}
}