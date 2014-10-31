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
 * Class SecurityGroupDecorator
 */
class SecurityGroupDecorator extends  DataExtension implements ISecurityGroup {

	public function getIdentifier()
	{
		return (int)$this->owner->getField('ID');
	}

	public function getTitle()
	{
		return $this->owner->getField('title');
	}

	public function setTitle($title)
	{
		$this->owner->setField('Title',$title);
		$this->setSlug(str_replace(' ', '-', strtolower($title)));
	}

	public function getSlug()
	{
		return $this->owner->getField('Code');
	}

	public function setSlug($slug)
	{
		$this->owner->setField('Code',$slug);
	}

	public function getDescription()
	{
		return $this->owner->getField('Description');
	}

	public function setDescription($description)
	{
		$this->owner->setField('Description',$description);
	}
}