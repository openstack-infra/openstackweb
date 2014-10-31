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
 * Class TrainingCoursePrerequisite
 */
class TrainingCoursePrerequisite
	extends DataObject
	implements ITrainingCoursePrerequisite
{

    public static $singular_name="Course Prerequisite";

    static $db = array(
        'Name' => 'Text',
    );

    public static $belongs_many_many = array (
        'TrainingCourse' => 'TrainingCourse',
    );

    function getCMSValidator()
    {
        return $this->getValidator();
    }

    function getValidator()
    {
        $validator= new RequiredFields(array('Name'));
        return $validator;
    }

    public function getCMSFields() {
        $fields = new FieldList();
        $fields->push(new LiteralField("Course Prerequisite","<h2>Course Prerequisite</h2>"));
        $fields->push(new TextField("Name","Name"));
        return $fields;
    }

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->getField('ID');
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getField('Name');
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->setField('Name',$name);
	}
}