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
 * Class CCLATeamManager
 */
final class CCLATeamManager {

	/**
	 * @var ITeamInvitationRepository
	 */
	private $invitation_repository;
	/**
	 * @var ITransactionManager
	 */
	private $tx_manager;

	/**
	 * @var ITeamRepository
	 */
	private $team_repository;

	/**
	 * @var ITeamInvitationFactory
	 */
	private $invitation_factory;

	/**
	 * @var ICCLAValidatorFactory
	 */
	private $validator_factory;

	/**
	 * @var ICLAMemberRepository
	 */
	private $member_repository;

	/**
	 * @var ITeamFactory
	 */
	private $team_factory;

	public function __construct(ITeamInvitationRepository $invitation_repository,
	                            ICLAMemberRepository      $member_repository,
	                            ITeamInvitationFactory    $invitation_factory,
	                            ITeamFactory              $team_factory,
	                            ICCLAValidatorFactory     $validator_factory,
								ITeamRepository           $team_repository,
	                            ITransactionManager       $tx_manager){

		$this->invitation_repository = $invitation_repository;
		$this->tx_manager            = $tx_manager;
		$this->team_repository       = $team_repository;
		$this->member_repository     = $member_repository;
		$this->invitation_factory    = $invitation_factory;
		$this->validator_factory     = $validator_factory;
		$this->team_factory          = $team_factory;
	}

	/**
	 * @param array                 $data
	 * @param ITeamInvitationSender $invitation_sender
	 * @return ITeamInvitation
	 */
	public function sendInvitation(array $data, ITeamInvitationSender $invitation_sender){

		$team_repository       = $this->team_repository;
		$invitation_factory    = $this->invitation_factory;
		$validator_factory     = $this->validator_factory;
		$member_repository     = $this->member_repository;
		$invitation_repository = $this->invitation_repository;

		return $this->tx_manager->transaction(function() use($data, $invitation_repository,  $team_repository, $invitation_factory , $validator_factory, $member_repository, $invitation_sender){
			$validator = $validator_factory->buildValidatorForTeamInvitation($data);
			if ($validator->fails()) {
				throw new EntityValidationException($validator->messages());
			}

			$team = $team_repository->getById((int)$data['team_id']);
			if(!$team) throw new NotFoundEntityException('Team',sprintf('id %s',$data['team_id']));

			$member = false;
			//is a already selected ICLA/CCLA Member
			if(isset($data['member_id'])){
				$member = $member_repository->getById((int)$data['member_id']);
				if(!$member) throw new NotFoundEntityException('Member',sprintf('id %s',$data['member_id']));
			}
			else {
				$member = $member_repository->findByEmail(trim($data['email']));
				if($member && !$member->hasSignedCLA())
					throw new MemberNotSignedCCLAException('This user has not yet signed the ICLA. Please ensure they have followed the appropriate steps outlined here: https://wiki.openstack.org/wiki/How_To_Contribute#Contributor_License_Agreement');
			}

			if($member && ($team->isMember($member) || $team->isInvite($member)))
				throw new TeamMemberAlreadyExistsException('Member Already exists on Team!');

			$invitation = $invitation_factory->buildInvitation(new InvitationDTO($data['first_name'], $data['last_name'], $data['email'], $team, $member ));

			$invitation_repository->add($invitation);

			$invitation_sender->sendInvitation($invitation);

			return $invitation;

		});
	}

	public function verifyInvitations($member_id, ITeamInvitationSender $invitation_sender){

		$member_repository     = $this->member_repository;
		$invitation_repository = $this->invitation_repository;

		return $this->tx_manager->transaction(function() use($member_id, $invitation_repository, $member_repository, $invitation_sender){

			$member = $member_repository->getById($member_id);
			if(!$member) throw new NotFoundEntityException('Member',sprintf('id %s',$member_id));

			foreach($invitation_repository->findByInviteEmail($member->Email) as $invitation){
				$invitation->updateInvite($member);
				$invitation_sender->sendInvitation($invitation);
			}
		});

	}

	/**
	 * @param int $team_id
	 * @param int $id
	 * @param string $status
	 * @throws NotFoundEntityException
	 */
	public function resignMembership($team_id, $id, $status){
		$team_repository       = $this->team_repository;
		$member_repository     = $this->member_repository;
		$invitation_repository = $this->invitation_repository;

		$this->tx_manager->transaction(function() use($team_id,$id , $status ,$team_repository, $member_repository, $invitation_repository){
			$team = $team_repository->getById($team_id);
			if(!$team) throw new NotFoundEntityException('Team',sprintf('id %s',$team_id));

			switch($status){
				case 'member':{
					$member = $member_repository->getById($id);
					if(!$member) throw new NotFoundEntityException('Member',sprintf('id %s',$id));
					$team->removeMember($member);
					$team->removeInvitation($invitation_repository->findByInviteEmailAndTeam($member->Email, $team));
				}
				break;
				default:{
					$invitation = $invitation_repository->getById($id);
					if(!$invitation) throw new NotFoundEntityException('TeamInvitation',sprintf('id %s',$id));
					$team->removeInvitation($invitation);
				}
				break;
			}
		});
	}

	/**
	 * @param string     $token
	 * @param ICLAMember $member
	 * @return ITeam
	 */
	public function confirmInvitation($token, ICLAMember $member){
		$invitation_repository = $this->invitation_repository;

		return $this->tx_manager->transaction(function() use($token, $member, $invitation_repository){
			$invitation = $invitation_repository->findByConfirmationToken($token);
			if(!$invitation)
				throw new NotFoundEntityException('TeamInvitation', sprintf('token %s',$token));
			if($invitation->getMember()->getIdentifier() !== $member->getIdentifier())
				throw new InvitationBelongsToAnotherMemberException;
			$invitation->doConfirmation($token);
			$invitation->getTeam()->addMember($invitation->getMember());
			return $invitation->getTeam();
		});
	}

	/**
	 * @param array $team_data
	 * @return ITeam
	 */
	public function registerTeam(array $team_data){
		$validator_factory     = $this->validator_factory;
		$team_repository       = $this->team_repository;
		$team_factory          = $this->team_factory;

		return $this->tx_manager->transaction(function() use($team_data, $validator_factory, $team_repository, $team_factory){

			$validator = $validator_factory->buildValidatorForTeam($team_data);
			if ($validator->fails()) {
				throw new EntityValidationException($validator->messages());
			}

			$team = $team_factory->buildTeam($team_data);

			if($team_repository->getByNameAndCompany($team->getName(),$team->getCompany()->getIdentifier()))
				throw new TeamAlreadyExistsException;

			$team_repository->add($team);

			return $team;
		});
	}

	/**
	 * @param int $team_id
	 * @param array $data
	 * @return ITeam
	 */
	public function updateTeam($team_id, $data){
		$validator_factory     = $this->validator_factory;
		$team_repository       = $this->team_repository;
		$team_factory          = $this->team_factory;

		return $this->tx_manager->transaction(function() use($team_id, $data , $validator_factory, $team_repository, $team_factory){

			$team = $team_repository->getById($team_id);

			if(!$team)
				throw new NotFoundEntityException('Team', sprintf(' id %s',$team_id ));

			$old_team = $team_repository->getByNameAndCompany($team->getName(),$team->getCompany()->getIdentifier());
			if($old_team->getIdentifier()!=$team_id)
				throw new TeamAlreadyExistsException;

			$team->updateName($data['name']);

			return $team;
		});
	}

	/**
	 * @param int $team_id
	 */
	public function removeTeam($team_id){

		$team_repository       = $this->team_repository;
		$this->tx_manager->transaction(function() use($team_id,  $team_repository){

			$team = $team_repository->getById($team_id);

			if(!$team)
				throw new NotFoundEntityException('Team', sprintf(' id %s',$team_id ));

			$team->clearMembers();
			$team->clearInvitations();

			$team_repository->delete($team);

		});
	}
} 