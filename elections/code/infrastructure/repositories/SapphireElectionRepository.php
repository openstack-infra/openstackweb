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
 * Class SapphireElectionRepository
 */
final class SapphireElectionRepository extends SapphireRepository
	implements IElectionRepository  {
	public function __construct(){
		parent::__construct(new Election());
	}

	/**
	 * @param int $n
	 * @return IElection[]
	 */
	public function getLatestNElections($n)
	{
		$query = new QueryObject(new Election);
		$query->addOrder(QueryOrder::desc('ElectionsOpen'));
		list($list,$count) = $this->getAll($query,0,$n);
		return $list;
	}

	/**
	 * @param int $years
	 * @return IElection
	 */
	public function getEarliestElectionSince($years)
	{
		$sql = 'select * from Election where ElectionsClose >= date_add(now(), interval -'.$years.' year) ORDER BY ElectionsClose ASC LIMIT 0,1;';
		$res = DB::query($sql);
		// let Silverstripe work the magic
		$elections = singleton('Election')->buildDataObjectSet($res);
		return $elections->first();
	}
}