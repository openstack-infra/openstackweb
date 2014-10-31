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
//decorators
Object::add_extension('Member', 'ICLAMemberDecorator');
Object::add_extension('Company', 'ICLACompanyDecorator');
Object::add_extension('SangriaPage_Controller', 'SangriaPageICLACompaniesExtension');
Object::add_extension('EditProfilePage_Controller', 'EditProfilePageICLAExtension');

PublisherSubscriberManager::getInstance()->subscribe('new_user_registered', function($member_id){
    //check if user has pending invitations
	$team_manager  = new CCLATeamManager(new SapphireTeamInvitationRepository,
		new SapphireCLAMemberRepository,
		new TeamInvitationFactory,
		new TeamFactory,
		new CCLAValidatorFactory,
		new SapphireTeamRepository,
		SapphireTransactionManager::getInstance());

	$team_manager->verifyInvitations($member_id, new TeamInvitationEmailSender(new SapphireTeamInvitationRepository));
});