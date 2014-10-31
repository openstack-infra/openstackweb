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
 * Class SapphireTransactionManager
 */
final class SapphireTransactionManager implements ITransactionManager {

	/**
	 * @var ITransactionManager
	 */
	private static $instance;

	private function __construct(){}

	private function __clone(){}

	/**
	 * @return ITransactionManager
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance = new SapphireTransactionManager();
		}
		return self::$instance;
	}

	/**
	 * @var int
	 */
	private $transactions = 0;

	private function beginTransaction()
	{
		++$this->transactions;
		if ($this->transactions == 1)
		{
			//By default, autocommit mode is enabled in MySQL.
			DB::query("SET AUTOCOMMIT=0;");
			DB::query("START TRANSACTION;");
		}
	}

	private function commit(){
		if ($this->transactions == 1){
			UnitOfWork::getInstance()->commit();
			DB::query("COMMIT;");
			DB::query("SET AUTOCOMMIT=1;");
		}
		--$this->transactions;
	}

	private function rollBack(){
		if ($this->transactions == 1)
		{
			$this->transactions = 0;

			DB::query("ROLLBACK;");
			DB::query("SET AUTOCOMMIT=1;");
		}
		else
		{
			--$this->transactions;
		}
	}

	/**
	 * @param callable $callback
	 * @return mixed|void
	 * @throws Exception
	 */
	public function transaction(Closure $callback){
		$result = null;
		try{
			$this->beginTransaction();
			$result = $callback($this);
			$this->commit();
		}
		catch(Exception $ex){
			$this->rollBack();
			throw $ex;
		}
		return $result;
	}
}