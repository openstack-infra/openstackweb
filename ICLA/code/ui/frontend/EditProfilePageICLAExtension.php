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
 * Class EditProfilePageICLAExtension
 */
final class EditProfilePageICLAExtension extends Extension {

	/**
	 * @var ITeamRepository
	 */
	private $team_repository;

	public function __construct(){
		$this->team_repository = new SapphireTeamRepository;
	}

	public function onBeforeInit(){
		Config::inst()->update(get_class($this), 'allowed_actions', array('CCLATeamAdmin'));
	}

	public function getNavActionsExtensions(&$html){
		$view = new SSViewer('EditProfilePage_ICLANav');
		$html .= $view->process($this->owner);
	}

	public function CCLATeamAdmin(){
		Requirements::javascript('marketplace/code/ui/admin/js/utils.js');
		Requirements::customScript('var company_id = '.$this->getCompanyID().';');
		Requirements::javascript('ICLA/js/edit.profile.ccla.teams.js');
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");
		Requirements::css('ICLA/css/edit.profile.ccla.teams.css');
		return $this->owner->getViewer('CCLATeamAdmin')->process($this->owner);
	}

	public function getTeamsDLL($id='add_member_team'){
		$current_member = Member::currentUser();
		if(!$current_member) return false;
		$company = $current_member->getManagedCCLACompany();
		if(!$company) return false;
		$res = $this->team_repository->getByCompany($company->getIdentifier());
		$res = new ArrayList($res);
		$ddl = new DropdownField($id,null, $res->map("ID", "Name"));
		$ddl->setEmptyString('-- Select a Team --');
		return $ddl;
	}

	public function getTeamMembers(){
		$current_member = Member::currentUser();
		if(!$current_member) return false;
		$company = $current_member->getManagedCCLACompany();
		if(!$company) return false;
		$teams   = $this->team_repository->getByCompany($company->getIdentifier());
		$members = array();

		foreach($teams as $team){

			foreach($team->getMembers() as $team_member){
				array_push($members, new TeamMemberViewModel($team_member->FirstName, $team_member->Surname, $team_member->Email, $team->getIdentifier() ,$team->getName(),'member', $team_member->getIdentifier() ,$team_member->DateAdded));
			}

			foreach($team->getUnconfirmedInvitations() as $team_invitation){
				$info      = $team_invitation->getInviteInfo();
				$status    = $team_invitation->isInviteRegisteredAsUser()? 'needs-confirmation':'needs-registration';
				array_push($members, new TeamMemberViewModel($info->getFirstName(), $info->getLastName(), $info->getEmail(), $team->getIdentifier() , $team->getName(), $status, $team_invitation->getIdentifier(),$team_invitation->Created ));
			}
		}

		usort($members, array($this,'cmp_team_members'));

		return new ArrayList($members);
	}

	public function cmp_team_members($a, $b){
		$name1 = $a->getFirstName().' '.$a->getLastName();
		$name2 = $b->getFirstName().' '.$b->getLastName();
		if ($name1 == $name2) {
			return 0;
		}
		return ($name1 < $name2) ? -1 : 1;
	}

	public function getCompanyName(){
		$current_member = Member::currentUser();
		if(!$current_member) return false;
		$company = $current_member->getManagedCCLACompany();
		if(!$company) return false;
		return $company->Name;
	}

	public function getCompanyID(){
		$current_member = Member::currentUser();
		if(!$current_member) return false;
		$company = $current_member->getManagedCCLACompany();
		if(!$company) return false;
		return $company->ID;
	}
}