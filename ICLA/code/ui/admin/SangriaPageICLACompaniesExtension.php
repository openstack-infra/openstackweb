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
 * Class SangriaPageICLACompaniesExtension
 */
final class SangriaPageICLACompaniesExtension extends Extension {

	private static $allowed_actions = array('ViewICLACompanies','exportCCLACompanies');
	/**
	 * @var ICLACompanyRepository
	 */
	private $company_repository;

	public function __construct(){
		parent::__construct();
		$this->company_repository = new SapphireICLACompanyRepository();
	}

	public function onBeforeInit(){
		Config::inst()->update(get_class($this->owner), 'allowed_actions', array('ViewICLACompanies','exportCCLACompanies'));
	}

	public function getQuickActionsExtensions(&$html){
		$view = new SSViewer('SangriaPage_ICLALinks');
		$html .= $view->process($this->owner);
	}

	public function ViewICLACompanies(){
		Requirements::css('ICLA/css/sangia.ccla.companies.css');
		Requirements::javascript('ICLA/js/sangia.ccla.companies.js');
		return $this->owner->getViewer('ViewICLACompanies')->process($this->owner);
	}

	public function getCompanies(){
		$query = new QueryObject;
		$query->addOrder(QueryOrder::asc('Name'));
		list($list,$size) = $this->company_repository->getAll($query,0,1000);
		return new ArrayList($list);
	}

	public function exportCCLACompanies(){
		//clean output buffer
		ob_end_clean();
		// file name for download
		$filename = "companies_ccla" . date('Ymd') . ".xls";
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: application/vnd.ms-excel");
		$query = new QueryObject;
		$query->addOrder(QueryOrder::asc('Name'));
		list($list,$size) = $this->company_repository->getAll($query,0,1000);
		$data = array();
		foreach($list as $company){
			$row = array();
			$row['CompanyName'] = $company->Name;
			$row['CCLADate']    = $company->isICLASigned()?  $company->CCLADate:'N/A';
			$row['CCLASigned']  = $company->isICLASigned()? 'True':'False';
			array_push($data, $row);
		}
		$flag = false;
		foreach($data as $row) {
			if(!$flag) {
				// display field/column names as first row
				echo implode("\t", array_keys($row)) . "\n";
				$flag = true;
			}
			array_walk($row, array($this,'cleanData'));
			echo implode("\t", array_values($row)) . "\n";
		}
	}

	function cleanData(&$str)
	{
		$str = preg_replace("/\t/", "\\t", $str);
		$str = preg_replace("/\r?\n/", "\\n", $str);
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}

}