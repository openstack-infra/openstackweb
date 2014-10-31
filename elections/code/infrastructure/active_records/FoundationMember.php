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
 * Class FoundationMember
 */
final class FoundationMember
	extends DataExtension
	implements IFoundationMember, ICommunityMember {

	static $has_many = array(
		'RevocationNotifications' => 'FoundationMemberRevocationNotification',
		'Votes'                   => 'Vote'
	);

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->owner->getField('ID');
	}


	public function convert2SiteUser(){
		$this->resign();
		$this->owner->addToGroupByCode(IFoundationMember::CommunityMemberGroupSlug);
	}

	public function isFoundationMember(){
		$res =  false;
		$res = $this->owner->inGroup(IFoundationMember::FoundationMemberGroupSlug);
		$legal_agreements = DataObject::get("LegalAgreement", " LegalDocumentPageID=422 AND MemberID =" . $this->owner->ID);
		$res = $res && $legal_agreements->count() > 0;
		return $res;
	}

	public function upgradeToFoundationMember(){
		if(!$this->isFoundationMember()){
			// Assign the member to be part of the foundation group
			$this->owner->addToGroupByCode(IFoundationMember::FoundationMemberGroupSlug);
			// Set up member with legal agreement for becoming an OpenStack Foundation Member
			$legalAgreement = new LegalAgreement();
			$legalAgreement->MemberID = $this->owner->ID;
			$legalAgreement->LegalDocumentPageID = 422;
			$legalAgreement->write();
			return true;
		}
		return false;
	}

	/**
	 * @param int $latest_election_id
	 * @return bool
	 */
	public function hasPendingRevocationNotifications($latest_election_id)
	{

	}

	/**
	 *
	 */
	public function resign(){
		// Remove member from Foundation group
		foreach($this->owner->Groups() as $g){
			$this->owner->Groups()->remove($g->ID);
		}

		// Remove member mamaged companies
		foreach($this->owner->ManagedCompanies() as $c){
			$this->owner->ManagedCompanies()->remove($c->ID);
		}
		// Remove Member's Legal Agreements
		$legal_agreements = $this->owner->LegalAgreements();
		if($legal_agreements)
			foreach($legal_agreements as $document) {
				$document->delete();
			}

		// Remove Member's Affiliations
		$affiliations = $this->owner->Affiliations();
		if($legal_agreements)
			foreach($affiliations as $affiliation) {
				$affiliation->delete();
			}
	}

	/**
	 * @return bool
	 */
	public function isCommunityMember()	{
		$group = $this->owner->inGroup(IFoundationMember::CommunityMemberGroupSlug);
		$is_speaker = DataObject::get_one('Speaker', 'MemberID = '. $this->owner->ID);
		$is_foundation_member = $this->isFoundationMember();
		return $group || $is_speaker || $is_foundation_member;
	}
}