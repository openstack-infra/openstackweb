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
 * Class ICLAManagerTest
 */
final class ICLAManagerTest extends SapphireTest {

	/**
	 * @expectedException UnauthorizedRestfullAPIException
	 */
	public function testprocessICLAGroupUnauthorizedRestfullAPIException(){

		$manager = new ICLAManager (
			new GerritAPI('https://review.openstack.org', 'smarcet', ''),
			new SapphireBatchTaskRepository,
			new SapphireCLAMemberRepository,
			new BatchTaskFactory,
			SapphireTransactionManager::getInstance()
		);

		$manager->processICLAGroup('a49e4febb69477d0aa5737038c1802dd6cab67c5',10);

		$this->setExpectedException('UnauthorizedRestfullAPIException');
	}


	public function testprocessICLAGroup(){

		$manager = new ICLAManager (
			new GerritAPI('https://review.openstack.org', 'smarcet', 'TwxKcgZurLX6'),
			new SapphireBatchTaskRepository,
			new SapphireCLAMemberRepository,
			new BatchTaskFactory,
			SapphireTransactionManager::getInstance()
		);

		$manager->processICLAGroup('a49e4febb69477d0aa5737038c1802dd6cab67c5',10);

	}
} 