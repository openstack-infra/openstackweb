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
 * Class FoundationMembershipRevocationSpecification
 */
final class FoundationMembershipRevocationSpecification {

	/**
	 * @param IFoundationMember   $member
	 * @param int                 $latest_elections_qty
	 * @param int                 $necessary_votes
	 * @param IElectionRepository $elections_repository
	 * @param IVoteRepository     $vote_repository
	 * @return bool
	 */
	public function mustSendNotification(IFoundationMember $member, $latest_elections_qty, $necessary_votes, IElectionRepository $elections_repository, IVoteRepository $vote_repository){
		// must be a foundation member, otherwise, do not sent anything
		if(!$member->isFoundationMember()) return false;

		$elections = $elections_repository->getLatestNElections($latest_elections_qty);

		if(count($elections) == 0) return false;

		$elections_id       = array();
		$latest_election    = $elections[0];
		$latest_election_id = $latest_election->getIdentifier();
		foreach($elections as $election)
			array_push($elections_id, $election->getIdentifier());

		// must not have pending revocation notifications , otherwise, do not sent anything
		if($member->hasPendingRevocationNotifications($latest_election_id)) return false;

		return $vote_repository->getVotesCountByMemberAndElections($member->getIdentifier(), $elections_id) < $necessary_votes;

	}

	/**
	 * @param  int                 $latest_elections_qty
	 * @param  int                 $necessary_votes
	 * @param  IElectionRepository $elections_repository
	 * @param  int                 $offset
	 * @param  int                 $limit
	 * @return bool|string
	 */
	public function sql($latest_elections_qty, $necessary_votes, IElectionRepository $elections_repository, $offset, $limit){

		$elections = $elections_repository->getLatestNElections($latest_elections_qty);

		if(count($elections) == 0) return false;

		$elections_id       = array();
		$latest_election    = $elections[0];
		$latest_election_id = $latest_election->getIdentifier();
		foreach($elections as $election)
			array_push($elections_id, $election->getIdentifier());
		$elections_id = implode(',',$elections_id);

		if($latest_election->startDate() == new DateTime('2014-01-13')) { // until January 2014
			$sql = <<<SQL
					-- members that did not vote on any latest election
			select M.ID from Member M
			inner join Group_Members gm on gm.MemberID = M.ID
			inner join `Group` g on g.ID = gm.GroupID and g.Code = 'foundation-members'
			inner join LegalAgreement la on la.MemberID =  M.ID and la.LegalDocumentPageID = 422 and la.Created <= '2012-08-15 23:59:59'
			where not exists (
				select V.ID
				from Vote V
				inner join Election E on V.ElectionID = E.ID
				where E.ID in ({$elections_id})  and V.VoterID = M.ID-- latest elections
			)
			and not exists(select id from FoundationMemberRevocationNotification rn where rn.RecipientID = M.ID and rn.Action = 'None') -- there is not any pending notification
			and not exists(select id from FoundationMemberRevocationNotification rn where rn.RecipientID = M.ID and rn.Action = 'Renew' and rn.LastElectionID = {$latest_election_id}) -- there are not rewnews for the current election
 			limit {$offset},{$limit};
SQL;
			return $sql;
		}
		else if($latest_election->startDate() > new DateTime('2014-01-13')){ // newer elections -- moving forward for all future elections after Jan. 2015
			$early_election  = $elections_repository->getEarliestElectionSince(2);
			$close_date      = $early_election->endDate();
			$sql = <<<SQL
					-- members that did not vote on any latest election
			select M.ID from Member M
			inner join Group_Members gm on gm.MemberID = M.ID
			inner join `Group` g on g.ID = gm.GroupID and g.Code = 'foundation-members'
			inner join LegalAgreement la on la.MemberID =  M.ID and la.LegalDocumentPageID = 422 and la.Created <= date_add('{$close_date}', interval -180 day)
			where not exists (
				select V.ID
				from Vote V
				inner join Election E on V.ElectionID = E.ID
				where E.ID in ({$elections_id})  and V.VoterID = M.ID-- latest elections
			)
		 	and not exists(select id from FoundationMemberRevocationNotification rn where rn.RecipientID = M.ID and rn.Action = 'None') -- there is not any pending notification
			and not exists(select id from FoundationMemberRevocationNotification rn where rn.RecipientID = M.ID and rn.Action = 'Renew' and rn.LastElectionID = {$latest_election_id}) -- there are not rewnews for the current election
 			limit {$offset},{$limit};
SQL;
			return $sql;
		}
		return false;
	}
} 