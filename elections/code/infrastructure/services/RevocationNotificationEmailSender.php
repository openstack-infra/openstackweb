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
 * Class RevocationNotificationEmailSender
 */
final class RevocationNotificationEmailSender implements IRevocationNotificationSender  {


	/**
	 * @param IFoundationMember                                 $foundation_member
	 * @param IFoundationMemberRevocationNotification           $notification
	 * @param IFoundationMemberRevocationNotificationRepository $notification_repository
	 */
	public function send(IFoundationMember $foundation_member,
	                     IFoundationMemberRevocationNotification $notification,
	                     IFoundationMemberRevocationNotificationRepository $notification_repository)
	{
		$email = EmailFactory::getInstance()->buildEmail(REVOCATION_NOTIFICATION_EMAIL_FROM,
			$foundation_member->Email,
			REVOCATION_NOTIFICATION_EMAIL_SUBJECT);

		$email->setTemplate('RevocationNotificationEmail');

		do{
			$hash = $notification->generateHash();
		} while ($notification_repository->existsHash($hash));
		$link = sprintf('%s/revocation-notifications/%s/action', Director::protocolAndHost(), $hash);
		$email->populateTemplate(array(
			'TakeActionLink' => $link,
			'EmailFrom'      => REVOCATION_NOTIFICATION_EMAIL_FROM,
			'ExpirationDate' => $notification->expirationDate()->format('F j')
		));

		$email->send();
	}
}