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
 * Class VotersDataIngestionTask
 * Ingest Voter Data only if they are Foundation Members and Exists on DB
 */
final class VotersDataIngestionTask extends CliController {

	function process(){

		set_time_limit(0);

		try{

			$election_input_path = Director::baseFolder().'/'. ELECTION_VOTERS_INGEST_PATH;
			$files               = scandir($election_input_path);
			$manager             = new ElectionManager(new SapphireElectionRepository,
										   new SapphireFoundationMemberRepository,
										   new SapphireVoteRepository,
										   new SapphireVoterFileRepository,
										   new VoteFactory,
										   new VoterFileFactory,
										   new ElectionFactory,
										   SapphireTransactionManager::getInstance());

			foreach($files as $file_name){
				if($this->isCSV($file_name) && list($election_id,$open_date,$close_date) = $this->isValidElectionFileName($file_name)){
					try{
						echo printf('processing file %s'.PHP_EOL,$file_name);
						list($count, $not_processed) = $manager->ingestVotersForElection($election_input_path.'/'.$file_name, $election_id,$open_date,$close_date);
						echo printf('file %s - processed %d rows - not processed %d rows'.PHP_EOL, $file_name, $count, count($not_processed));
						
						if(count($not_processed) > 0){
							echo 'not processed details ... '.PHP_EOL;
							echo var_dump($not_processed).PHP_EOL;
						}

						echo printf('deleting file %s ...'.PHP_EOL,$file_name);
						unlink($election_input_path.'/'.$file_name);
					}
					catch(Exception $ex){
						SS_Log::log($ex,SS_Log::ERR);
						echo $ex->getMessage();
					}
				}
			}
			return 'OK';
		}
		catch(Exception $ex){
			SS_Log::log($ex,SS_Log::ERR);
			echo $ex->getMessage();
		}
	}

	private function isCSV($file_name){
		$file_parts = pathinfo($file_name);
		return @$file_parts['extension'] == 'csv';
	}

	private function isValidElectionFileName($file_name){
		$pattern     = '@voters_(\d+)_(\d{8,8})_(\d{8,8})@i';
		$res         = preg_match($pattern, $file_name,$results);
		$open_date   = (string)$results[2];
		$close_date  = (string)$results[3];
		$open_date   = new DateTime($open_date);
		$close_date  = new DateTime($close_date);
		return $res == 1 &&  $open_date <= $close_date ? array((int)$results[1], $open_date, $close_date):false;
	}

} 