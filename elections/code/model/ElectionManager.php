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
 * Class ElectionManager
 */
final class ElectionManager {

	/**
	 * @var IEntityRepository
	 */
	private $election_repository;

	/**
	 * @var
	 */
	private $foundation_member_repository;

	/**
	 * @var IEntityRepository
	 */
	private $vote_repository;

	/**
	 * @var IVoterFileRepository
	 */
	private $voter_file_repository;

	/**
	 * @var IVoteFactory
	 */
	private $vote_factory;

	/**
	 * @var IVoterFileFactory
	 */
	private $voter_file_factory;

	/**
	 * @var ITransactionManager
	 */
	private $tx_manager;

	/**
	 * @var IElectionFactory
	 */
	private $election_factory;

	/**
	 * @param IEntityRepository    $election_repository
	 * @param IEntityRepository    $foundation_member_repository
	 * @param IEntityRepository    $vote_repository
	 * @param IVoterFileRepository $voter_file_repository
	 * @param IVoteFactory         $vote_factory
	 * @param IVoterFileFactory    $voter_file_factory
	 * @param IElectionFactory     $election_factory
	 * @param ITransactionManager  $tx_manager
	 */
	public function __construct(IEntityRepository    $election_repository,
	                            IEntityRepository    $foundation_member_repository,
								IEntityRepository    $vote_repository,
								IVoterFileRepository $voter_file_repository,
	                            IVoteFactory         $vote_factory,
	                            IVoterFileFactory    $voter_file_factory,
								IElectionFactory     $election_factory,
								ITransactionManager   $tx_manager){

		$this->election_repository          = $election_repository;
		$this->foundation_member_repository = $foundation_member_repository;
		$this->vote_repository              = $vote_repository;
		$this->voter_file_repository        = $voter_file_repository;
		$this->voter_file_factory           = $voter_file_factory;
		$this->vote_factory                 = $vote_factory;
		$this->election_factory             = $election_factory;
		$this->tx_manager                   = $tx_manager;
	}


	/**
	 * @param string   $filename
	 * @param int      $election_id
	 * @param DateTime $open_date
	 * @param DateTime $close_date
	 * @return array
	 */
	public function ingestVotersForElection($filename, $election_id, DateTime $open_date, DateTime $close_date){

		$election_repository          = $this->election_repository;
		$foundation_member_repository = $this->foundation_member_repository;
		$vote_factory                 = $this->vote_factory;
		$vote_repository              = $this->vote_repository;
		$voter_file_factory           = $this->voter_file_factory;
		$voter_file_repository        = $this->voter_file_repository;
		$election_factory             = $this->election_factory;


		return $this->tx_manager->transaction(function() use ($filename, $election_id,$open_date,$close_date, $election_repository, $foundation_member_repository, $vote_factory, $vote_repository, $voter_file_factory, $voter_file_repository, $election_factory){

			if($voter_file_repository->getByFileName($filename))
				throw new EntityAlreadyExistsException('VoterFile',sprintf('filename = %s',$filename));


			$election =  $election_repository->getById($election_id);
			if(!$election){
				$election = $election_factory->build($election_id, $open_date, $close_date);
				$election_repository->add($election);
			}
			$reader   = new CSVReader($filename);

			$line   = false;
			$header = $reader->getLine();
			$count  = 0;
			$not_processed = array();
			while($line = $reader->getLine()){
				$first_name = $line[1];
				$last_name  = $line[2];
				$member_id  = (int)$line[3];
				$member = $foundation_member_repository->getById($member_id);
				if(!$member)
					$member = $foundation_member_repository->getByCompleteName($first_name,$last_name);
				if($member && $member->isFoundationMember()){
					$vote = $vote_factory->buildVote($election, $member);
					$vote_repository->add($vote);
					$count++;
				}
				else{
					array_push($not_processed,  array( 'id' => $member_id, 'first_name' => $first_name,'last_name' =>$last_name));
				}
			}

			$voter_file = $voter_file_factory->build($filename);
			$voter_file_repository->add($voter_file);

			return array($count,$not_processed);
		});
	}
} 