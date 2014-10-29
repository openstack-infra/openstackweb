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
 * Class SangriaPageStandardizeOrgNamesExtension
 */
final class SangriaPageStandardizeOrgNamesExtension extends Extension {

	var $orgs_cached = array();

	public function onBeforeInit(){

		Config::inst()->update(get_class($this), 'allowed_actions', array(
			'StandardizeOrgNames',
			'MarkOrgStandardized',
			'RemoveDuplicateOrg'));

		Config::inst()->update(get_class($this->owner), 'allowed_actions', array(
			'StandardizeOrgNames',
			'MarkOrgStandardized',
			'RemoveDuplicateOrg'));
	}

	// Org Standardization
	function Orgs() {
		$orgs = DataObject::get("Org","Name");
		return $orgs;
	}

	function NonStandardizedOrgs() {
		$orgs = DataObject::get("Org","IsStandardizedOrg = 0","Name",null,150);
		return $orgs;
	}

	function StandardizedOrgs() {
		global $orgs_cached;

		if( count($orgs_cached) > 0 ) {
			return $orgs_cached;
		} else {
			$orgs = DataObject::get("Org","IsStandardizedOrg = 1","Name");
			$orgs_cached = $orgs;
			return $orgs;
		}
	}

	function MarkOrgStandardized() {
		if(isset($_GET['orgId']) && is_numeric($_GET['orgId'])) {
			$orgId = $_GET['orgId'];
		}

		$org = DataObject::get_by_id("Org",$orgId);
		$org->IsStandardizedOrg = 1;
		$org->write();
		Controller::curr()->redirectBack();
	}

	function RemoveDuplicateOrg() {
		if(isset($_POST['oldOrgIds']) && is_array($_POST['oldOrgIds'])) {
			$oldOrgIds = $_POST['oldOrgIds'];
		}

		foreach( $oldOrgIds as $oldId => $newId) {
			if( $newId == "STANDARDIZE") {
				$org = DataObject::get_by_id("Org",$oldId);
				$org->IsStandardizedOrg = 1;
				$org->write();
			} else if( $newId != 0 ) {
				$oldOrg = DataObject::get_by_id("Org",$oldId);
				$newOrg = DataObject::get_by_id("Org",$newId);

				// Update all members with new Org
				DB::query("UPDATE `Affiliation` SET `OrganizationID` = ".$newId." WHERE `OrganizationID` = ".$oldId);

				// Remove old Org
				DB::query("DELETE FROM `Org` WHERE `ID` = ".$oldId);
			}
		}

		Controller::curr()->redirectBack();
	}

} 