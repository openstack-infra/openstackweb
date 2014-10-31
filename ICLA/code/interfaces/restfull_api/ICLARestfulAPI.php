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
 * Class ICLARestfulAPI
 */
final class ICLARestfulAPI
	extends AbstractRestfulJsonApi {

	/**
	 * @var ICLACompanyRepository
	 */
	private $company_repository;

	/**
	 * @var CCLACompanyService
	 */
	private $company_manager;

	/**
	 * @var ICLAMemberRepository
	 */
	private $member_repository;

	/**
	 * @var CCLATeamManager
	 */
	private $team_manager;

	/**
	 * @var ITeamInvitationRepository
	 */
	private $invitation_repository;

	public function __construct(){
		parent::__construct();

		$this->company_repository    = new SapphireICLACompanyRepository;
		$this->member_repository     = new SapphireCLAMemberRepository;
		$this->invitation_repository = new SapphireTeamInvitationRepository;
		$this->company_manager       = new CCLACompanyService($this->company_repository, SapphireTransactionManager::getInstance());

		$this->team_manager          = new CCLATeamManager(
			$this->invitation_repository,
			$this->member_repository,
			new TeamInvitationFactory,
			new TeamFactory,
			new CCLAValidatorFactory,
			new SapphireTeamRepository,
			SapphireTransactionManager::getInstance());

		//filters...

		$this_var = $this;
		$this->addBeforeFilter('signCompanyCCLA','check_sign',function ($request) use($this_var){
			if(!Permission::check("SANGRIA_ACCESS"))
				return $this_var->permissionFailure();
		});
		$this->addBeforeFilter('unsignCompanyCCLA','check_unsign',function ($request) use($this_var){
			if(!Permission::check("SANGRIA_ACCESS"))
				return $this_var->permissionFailure();
		});
		$this->addBeforeFilter('searchCCLAMembers','check_members_search',function() use($this_var){
			return $this_var->checkCCLAdmin();
		});
		$this->addBeforeFilter('addInvitation','check_add_invitation',function() use($this_var){
			return $this_var->checkCCLAdmin();
		});
		$this->addBeforeFilter('deleteInvitation','check_delete_invitation',function() use($this_var){
			return $this_var->checkCCLAdmin();
		});
	}

	/**
	 * @return SS_HTTPResponse
	 */
	public function checkCCLAdmin(){
		$member = Member::currentUser();
		if(!$member) return $this->permissionFailure();
		if(!$member->isCCLAAdmin()) return $this->permissionFailure();
	}

	const ApiPrefix = 'api/v1/ccla';

	protected function isApiCall()
	{
		$request = $this->getRequest();
		if(is_null($request)) return false;
		return  strpos(strtolower($request->getURL()),self::ApiPrefix) !== false;
	}

	/**
	 * @return bool
	 */
	protected function authorize()
	{
		return true;
		return;
	}

	/**
	 * @var array
	 */
	static $url_handlers = array(
		'PUT companies/$COMPANY_ID/sign'                => 'signCompanyCCLA',
		'DELETE companies/$COMPANY_ID/sign'             => 'unsignCompanyCCLA',
		'GET members'                                   => 'searchCCLAMembers',
		'POST invitations'                              => 'addInvitation',
		'DELETE teams/$TEAM_ID/memberships/$ID/$STATUS' => 'resignMembership',
		'POST teams'                                    => 'addTeam',
		'DELETE teams/$TEAM_ID'                         => 'deleteTeam',
		'PUT teams/$TEAM_ID'                            => 'updateTeamName',
	);

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'signCompanyCCLA',
		'unsignCompanyCCLA',
		'searchCCLAMembers',
		'addInvitation',
		'resignMembership',
		'confirmTeamInvitation',
		'addTeam',
		'deleteTeam',
		'updateTeamName',
	);

	public function addInvitation(){
		$data = $this->getJsonRequest();
		try{
			$entity = $this->team_manager->sendInvitation($data, new TeamInvitationEmailSender($this->invitation_repository));
			return $this->created($entity->getIdentifier());
		}
		catch(NotFoundEntityException $ex1){
			SS_Log::log($ex1,SS_Log::NOTICE);
			return $this->notFound($ex1->getMessage());
		}
		catch(EntityValidationException $ex2){
			SS_Log::log($ex2,SS_Log::NOTICE);
			return $this->validationError($ex2->getMessages());
		}
		catch(TeamMemberAlreadyExistsException $ex3){
			SS_Log::log($ex3,SS_Log::NOTICE);
			return $this->validationError(array( array('attribute'=>'error', 'message' => $ex3->getMessage())));
		}
		catch(MemberNotSignedCCLAException $ex4){
			SS_Log::log($ex4,SS_Log::NOTICE);
			return $this->validationError(array( array('attribute'=>'error', 'message' => $ex4->getMessage())));
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
		if (!$data) return $this->serverError();
	}

	public function resignMembership(){

		$id            = (int)$this->request->param('ID');
		$team_id       = (int)$this->request->param('TEAM_ID');
		$status        = $this->request->param('STATUS');

		try{
			$this->team_manager->resignMembership($team_id, $id, $status);
			return $this->deleted();
		}
		catch(NotFoundEntityException $ex1){
			SS_Log::log($ex1,SS_Log::NOTICE);
			return $this->notFound($ex1->getMessage());
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	public function signCompanyCCLA(){
		$company_id = (int)$this->request->param('COMPANY_ID');
		try{
			$res = $this->company_manager->signCCLA($company_id);
			return $this->ok(array('sign_date' => $res));
		}
		catch(NotFoundEntityException $ex1){
			SS_Log::log($ex1,SS_Log::NOTICE);
			return $this->notFound($ex1->getMessage());
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	public function unsignCompanyCCLA(){
		$company_id = (int)$this->request->param('COMPANY_ID');
		try{
			$this->company_manager->unsignCCLA($company_id);
			return $this->ok();
		}
		catch(NotFoundEntityException $ex1){
			SS_Log::log($ex1,SS_Log::NOTICE);
			return $this->notFound($ex1->getMessage());
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	public function searchCCLAMembers(){

		$params = $this->request->getVars();
		$field  = $params['field'];
		$term   = $params['term'];

		try{
			$res  = array();

			switch($field){
				case 'email':
					list($list, $size) = $this->member_repository->getAllIClaMembersByEmail($term,0,25);
				break;
				case 'fname':
					list($list, $size) = $this->member_repository->getAllIClaMembersByFirstName($term,0,25);
				break;
				case 'lname':
					list($list, $size) = $this->member_repository->getAllIClaMembersByLastName($term,0,25);
				break;
				default:
					return $this->validationError('criteria not supported!');
				break;
			}

			foreach($list as $member){
				$label = sprintf('%s - (%s) - %s',$member->getFullName(), $member->Email, $member->LastVisited);
				array_push($res, array( 'label'      => $label,
					                    'value'      => $member->getIdentifier(),
				                        'email'      => $member->Email,
				                        'first_name' => $member->FirstName,
				                        'last_name'  => $member->Surname));
			}
			return $this->ok($res);
		}
		catch(NotFoundEntityException $ex1){
			SS_Log::log($ex1,SS_Log::NOTICE);
			return $this->notFound($ex1->getMessage());
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	//Teams

	public function addTeam(){
		$data = $this->getJsonRequest();
		if (!$data) return $this->serverError();

		try{
			$entity = $this->team_manager->registerTeam($data);
			return $this->created($entity->getIdentifier());
		}
		catch(TeamAlreadyExistsException $ex1){
			SS_Log::log($ex1,SS_Log::NOTICE);
			return $this->validationError(array( array('attribute'=>'error', 'message' => 'Team Already exist on Company!')));
		}
		catch(EntityValidationException $ex2){
			SS_Log::log($ex2,SS_Log::NOTICE);
			return $this->validationError($ex2->getMessages());
		}

		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	public function updateTeamName(){
		$data = $this->getJsonRequest();
		if (!$data) return $this->serverError();
		$team_id  = (int)$this->request->param('TEAM_ID');
		try{
			 $this->team_manager->updateTeam($team_id, $data);
			return $this->updated();
		}
		catch(TeamAlreadyExistsException $ex1){
			SS_Log::log($ex1,SS_Log::NOTICE);
			return $this->validationError(array( array('attribute'=>'error', 'message' => 'Team Already exist on Company!')));
		}
		catch(EntityValidationException $ex2){
			SS_Log::log($ex2,SS_Log::NOTICE);
			return $this->validationError($ex2->getMessages());
		}
		catch(NotFoundEntityException $ex3){
			SS_Log::log($ex3,SS_Log::NOTICE);
			return $this->validationError(array( array('attribute'=>'error', 'message' => 'Team does not exist on Company!')));
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}

	public function deleteTeam(){
		$team_id  = (int)$this->request->param('TEAM_ID');
		try{
			$this->team_manager->removeTeam($team_id);
			return $this->deleted();
		}
		catch(EntityValidationException $ex2){
			SS_Log::log($ex2,SS_Log::NOTICE);
			return $this->validationError($ex2->getMessages());
		}
		catch(NotFoundEntityException $ex3){
			SS_Log::log($ex3,SS_Log::NOTICE);
			return $this->validationError(array( array('attribute'=>'error', 'message' => 'Team does not exist on Company!')));
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->serverError();
		}
	}
}