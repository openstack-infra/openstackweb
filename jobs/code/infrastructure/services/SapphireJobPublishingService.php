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
 * Class SapphireJobPublishingService
 */
final class SapphireJobPublishingService
implements IJobPublishingService
{
	/**
	 * @param IJob $job
	 * @throws NotFoundEntityException
	 */
	public function publish(IJob $job){
		$parent = JobHolder::get()->first();
		if(!$parent) throw new NotFoundEntityException('JobHolder','');
		$job->setParent($parent); // Should set the ID once the Holder is created...
		$job->write();
		$job->doPublish();
	}
} 