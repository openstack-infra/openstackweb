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
 * Class RevocationNotificationAction_Controller
 */
final class RevocationNotificationAction_Controller extends ContentController {
	/**
	 * @var array
	 */
	static $url_handlers = array(
		'GET $ACTION_TOKEN/action' => 'takeAction',
		'GET $ACTION_TOKEN/renew'  => 'renewFoundationMembership',
		'GET $ACTION_TOKEN/revoke' => 'revokeFoundationMembership',
		'GET $ACTION_TOKEN/delete' => 'deleteFoundationMembership',
	);

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'takeAction',
		'renewFoundationMembership',
		'revokeFoundationMembership',
		'deleteFoundationMembership',
		'logout',
	);

	/**
	 * @var IFoundationMemberRevocationNotificationRepository
	 */
	private $notification_repository;

	/**
	 * @var RevocationNotificationManager
	 */
	private $notification_manager;

	public function __construct(){
		parent::__construct();
		$this->notification_repository = new SapphireFoundationMemberRevocationNotificationRepository();
		$this->notification_manager    = new RevocationNotificationManager(new SapphireFoundationMemberRepository,
			new SapphireFoundationMemberRevocationNotificationRepository,
			new SapphireElectionRepository,
			new RevocationNotificationFactory,
			SapphireTransactionManager::getInstance());

		Requirements::css('elections/css/revocation.action.page.css');
	}

	public function logout(){
		$current_member = Member::currentUser();
		if($current_member){
			$current_member->logOut();
			return Controller::curr()->redirect("Security/login?BackURL=" . urlencode($_SERVER['HTTP_REFERER']));
		}
		return Controller::curr()->redirectBack();
	}

	/**
	 * @return string|void
	 */
	public function takeAction(){
		$token = $this->request->param('ACTION_TOKEN');
		try{
			$current_member = Member::currentUser();
			if(is_null($current_member))
				return Controller::curr()->redirect("Security/login?BackURL=" . urlencode($_SERVER['REQUEST_URI']));

			$notification = $this->notification_repository->getByHash($token);
			if(!$notification){
				return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
			}

			if($notification->recipient()->getIdentifier() != $current_member->ID){
				return $this->renderWith(array('RevocationNotificationActionPage_belongs_2_another_user','Page'), array('UserName' => $notification->recipient()->Email));
			}

			if($notification->isExpired()){
				return $this->renderWith(array('RevocationNotificationActionPage_expired','Page'), array(
					'RevocationDate' => $notification->actionDate()->format('F j'),
					'UserName' => $notification->recipient()->Email));
			}

			if(!$notification->isValid()){
				return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
			}

			return $this->renderWith(array('RevocationNotificationActionPage','Page') , array('Token' => $token, 'Notification' => $notification ) );
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
		}
	}

	public function renewFoundationMembership(){
		$token = $this->request->param('ACTION_TOKEN');
		try{
			$current_member = Member::currentUser();
			if(is_null($current_member))
				return Controller::curr()->redirect("Security/login?BackURL=" . urlencode($_SERVER['REQUEST_URI']));

			$notification = $this->notification_repository->getByHash($token);
			if(!$notification){
				return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
			}
			if($notification->recipient()->getIdentifier() != $current_member->ID)
			{
				return $this->renderWith(array('RevocationNotificationActionPage_belongs_2_another_user','Page'), array('UserName' => $notification->recipient()->Email));
			}
			if($notification->isExpired()){
				return $this->renderWith(array('RevocationNotificationActionPage_expired','Page'), array(
					'RevocationDate' => $notification->actionDate()->format('F j'),
					'UserName' => $notification->recipient()->Email));
			}
			if(!$notification->isValid()){
				return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
			}
			$this->notification_manager->renewNotification($notification);
			return $this->renderWith(array('RevocationNotificationActionPage_renew','Page'));
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
		}
	}

	public function revokeFoundationMembership(){
		$token = $this->request->param('ACTION_TOKEN');
		try{
			$current_member = Member::currentUser();
			if(is_null($current_member))
				return Controller::curr()->redirect("Security/login?BackURL=" . urlencode($_SERVER['REQUEST_URI']));

			$notification = $this->notification_repository->getByHash($token);
			if(!$notification){
				return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
			}
			if($notification->recipient()->getIdentifier() != $current_member->ID)
			{
				return $this->renderWith(array('RevocationNotificationActionPage_belongs_2_another_user','Page'), array('UserName' => $notification->recipient()->Email));
			}
			if($notification->isExpired()){
				return $this->renderWith(array('RevocationNotificationActionPage_expired','Page'), array(
					'RevocationDate' => $notification->actionDate()->format('F j'),
					'UserName' => $notification->recipient()->Email));
			}
			if(!$notification->isValid()){
				return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
			}
			$this->notification_manager->revokeNotification($notification);
			return $this->renderWith(array('RevocationNotificationActionPage_revoke','Page'));
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
		}
	}

	public function deleteFoundationMembership(){
		$token = $this->request->param('ACTION_TOKEN');
		try{
			$current_member = Member::currentUser();
			if(is_null($current_member))
				return Controller::curr()->redirect("Security/login?BackURL=" . urlencode($_SERVER['REQUEST_URI']));

			$notification = $this->notification_repository->getByHash($token);
			if(!$notification){
				return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
			}
			if($notification->recipient()->getIdentifier() != $current_member->ID)
			{
				return $this->renderWith(array('RevocationNotificationActionPage_belongs_2_another_user','Page'), array('UserName' => $notification->recipient()->Email));
			}
			if($notification->isExpired()){
				return $this->renderWith(array('RevocationNotificationActionPage_expired','Page'), array(
					'RevocationDate' => $notification->actionDate()->format('F j'),
					'UserName' => $notification->recipient()->Email));
			}
			if(!$notification->isValid()){
				return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
			}
			$this->notification_manager->deleteAccount($notification);
			return $this->renderWith(array('RevocationNotificationActionPage_delete','Page'));
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			return $this->renderWith(array('RevocationNotificationActionPage_error','Page'));
		}
	}

} 