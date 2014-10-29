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
 * Class SangriaPageInvolvementTypeExtension
 */
final class SangriaPageInvolvementTypeExtension extends Extension {

	public function onBeforeInit(){
		Config::inst()->update(get_class($this), 'allowed_actions', array(
			'AddInvolvementType',
			'AddInvolvementTypeForm',
			'GenerateAutoLoginHashes',
		));

		Config::inst()->update(get_class($this->owner), 'allowed_actions', array(
			'AddInvolvementType',
			'AddInvolvementTypeForm',
			'GenerateAutoLoginHashes',
		));
	}

	// Involvement Types
	function InvolvementTypes() {
		return DataObject::get("InvolvementType");
	}

	function AddInvolvementTypeForm() {
		return new AddInvolvementTypeForm($this->owner, 'AddInvolvementTypeForm');
	}

	function GenerateAutoLoginHashes() {
		$startVal = 0;

		if (isset($_GET["startID"]) && intval($_GET["startID"]) > 0 ) {
			$startVal = intval($_GET["startID"]);
		}

		$members = Member::get()->filter(array('SubscribedToNewsletter' => 1, 'ID:GreaterThan' => $startVal))->order('ID')->leftJoin('Group_Members', "`Member`.`ID` = `Group_Members`.`MemberID` AND Group_Members.GroupID = 5 ");
		foreach( $members as $member ) {
			$token = $member->generateAutologinTokenAndStoreHash(14);
			echo "\"".$member->ID."\",\"".$member->Email."\",\"".$member->FirstName."\",\"".$member->Surname."\",\"".urldecode($token)."\"<br/>";
			flush();
		}
	}
} 