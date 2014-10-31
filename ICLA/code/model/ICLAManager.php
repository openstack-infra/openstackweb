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
 * Class ICLAManager
 */
final class ICLAManager {

	const ICLAGroupTaskName = 'ICLAGroupTask';

	const UpdateLastCommittedDateTaskName = 'UpdateLastCommittedDateTaskName ';

	/**
	 * @var IGerritAPI
	 */
	private $gerrit_api;

	/**
	 * @var IBatchTaskRepository
	 */
	private $batch_repository;

	/**
	 * @var ICLAMemberRepository
	 */
	private $member_repository;

	/**
	 * @var ITransactionManager
	 */
	private $tx_manager;

	/**
	 * @var IBatchTaskFactory
	 */
	private $batch_task_factory;

	/**
	 * @param IGerritAPI           $gerrit_api
	 * @param IBatchTaskRepository $batch_repository
	 * @param ICLAMemberRepository $member_repository
	 * @param IBatchTaskFactory    $batch_task_factory
	 * @param ITransactionManager  $tx_manager
	 */
	public function __construct(IGerritAPI $gerrit_api,
	                            IBatchTaskRepository $batch_repository,
	                            ICLAMemberRepository $member_repository,
	                            IBatchTaskFactory    $batch_task_factory,
								ITransactionManager  $tx_manager){

		$this->gerrit_api         = $gerrit_api;
		$this->batch_repository   = $batch_repository;
		$this->member_repository  = $member_repository;
		$this->batch_task_factory = $batch_task_factory;
		$this->tx_manager         = $tx_manager;
	}

	/**
	 * @param string $icla_group_id
	 * @param int $batch_size
	 * @return int
	 */
	public function processICLAGroup($icla_group_id, $batch_size){

		$batch_repository   = $this->batch_repository;
		$member_repository  = $this->member_repository;
		$gerrit_api         = $this->gerrit_api;
		$batch_task_factory = $this->batch_task_factory;

		return $this->tx_manager->transaction(function() use($icla_group_id, $batch_size, $member_repository, $batch_repository, $gerrit_api, $batch_task_factory) {

			$task                = $batch_repository->findByName(ICLAManager::ICLAGroupTaskName);
			$members_ids_on_icla = $member_repository->getAllGerritIds();

			if(!$task){
				// query gerrit service
				$icla_members_response = $gerrit_api->listAllMembersFromGroup($icla_group_id);

				$task = $batch_task_factory->buildBatchTask(ICLAManager::ICLAGroupTaskName, count($icla_members_response));
				$batch_repository->add($task);

				$task->updateResponse(json_encode($icla_members_response));
			}
			else if($task->lastRecordProcessed() == $task->totalRecords()){

				$icla_members_response = $gerrit_api->listAllMembersFromGroup($icla_group_id);
				if(count($icla_members_response) == count(json_decode($task->lastResponse(), true))) return;//nothing to process..
				$task->initialize(count($icla_members_response));
				$task->updateResponse(json_encode($icla_members_response));
			}

			$members = json_decode($task->lastResponse(), true);

			$updated_members = 0;

			for($i = 0; $i < $batch_size && ( ($task->lastRecordProcessed()) < $task->totalRecords() ); $i++){

				$index       = $task->lastRecordProcessed();
				$gerrit_info = $members[$index];
				$email       = @$gerrit_info['email'];
				$gerrit_id   = @$gerrit_info['_account_id'];

				if(!empty($email) && !empty($gerrit_id) ){
					if(!array_key_exists($gerrit_id, $members_ids_on_icla)){
						$member = $member_repository->findByEmail($email);
						if($member){
							$member->signICLA($gerrit_id);
							++$updated_members;
						}
					}
				}
				$task->updateLastRecord();
			}

			return $updated_members;
		});
	}


	public function updateLastCommittedDate($batch_size){

		$batch_repository   = $this->batch_repository;
		$member_repository  = $this->member_repository;
		$gerrit_api         = $this->gerrit_api;
		$batch_task_factory = $this->batch_task_factory;

		return $this->tx_manager->transaction(function() use($batch_size, $member_repository, $batch_repository, $gerrit_api, $batch_task_factory) {

			$task = $batch_repository->findByName(ICLAManager::UpdateLastCommittedDateTaskName);

			$last_index      = 0;
			$members         = array();
			$updated_members = 0;

			if($task){
				$last_index = $task->lastRecordProcessed();
				list($members,$total_size) = $member_repository->getAllICLAMembers($last_index, $batch_size);
				if($task->lastRecordProcessed() == $task->totalRecords()) $task->initialize($total_size);
			}
			else{
				list($members,$total_size) = $member_repository->getAllICLAMembers($last_index, $batch_size);
				$task = $batch_task_factory->buildBatchTask(ICLAManager::UpdateLastCommittedDateTaskName, $total_size);
				$batch_repository->add($task);
			}

			foreach($members as $member){
				$last_commit_date = $gerrit_api->getUserLastCommit($member->getGerritId());
				if(!is_null($last_commit_date) && $last_commit_date){
					$member->updateLastCommitedDate($last_commit_date);
					++$updated_members;
				}
				$task->updateLastRecord();
			}

			return $updated_members;
		});
	}
} 