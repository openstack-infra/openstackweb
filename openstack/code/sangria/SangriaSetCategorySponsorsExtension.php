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

/***
 * Class SangriaSetCategorySponsorsExtension
 */
final class SangriaSetCategorySponsorsExtension extends Extension {

	public function onBeforeInit(){

		Config::inst()->update(get_class($this), 'allowed_actions',array(
			'SetCategorySponsors',
			'UpdateSponsor',
			'SetSponsorMarketplaces',
		));

		Config::inst()->update(get_class($this->owner), 'allowed_actions',array(
			'SetCategorySponsors',
			'UpdateSponsor',
			'SetSponsorMarketplaces',
		));
	}

	// Sponsors

	function SponsorsRequest(){
		return DataObject::get('Sponsor','Approved = 0');
	}

	function SponsorsApproved(){
		return DataObject::Get('Sponsor','Approved = 2');
	}

	function SponsorViaGet(){
		return DataObject::get_one('Sponsor','ID = ' . intval($_GET['sponsor_id']));
	}

	function SponsorsMarketplaces(){
		$list = new ArrayList();
		for ($i=1; $i<=5; $i++){
			$do = new DataObject();
			$do->Name = 'Market '.$i;
			$do->Key = $i;
			$list->push($do);
		}
		return $list;
	}

	/*function UpdateSponsor(){
	$sponsor = DataObject::get_one('Sponsor','ID = ' . intval($_GET['sponsor_id']));

	switch($_GET['action']){
		case 'approved':
			$sponsor->Approved = 1;
			break;
		case 'rejected':
			$sponsor->Approved = -1;
			break;
		default:
			throw new Exception('Method not allowed.');
			die();
			break;
	}

	$marketplaces = explode(',', $sponsor->Category);

	foreach($marketplaces as $m){

		$market = new SponsorMarketplace();
		$market->SponsorID = $sponsor->ID;
		$market->MarketplaceID = $m;
		$market->write();

	}

	$contract_template = DataObject::get_one('ContractTemplate', " Type = 'General' ");
	$contract_url = Director::baseFolder($contract_template->PDF()->URL).$contract_template->PDF()->URL;

	$ESLoader = new SplClassLoader('EchoSign', realpath(__DIR__.'/../../'));
	$ESLoader->register();

	$client = new SoapClient(EchoSign\API::getWSDL());
	$api = new EchoSign\API($client, 'PGRUY64K6T664Z');

	$file = EchoSign\Info\FileInfo::createFromFile($contract_url);

	$document = new EchoSign\Info\DocumentCreationInfo('This is a test contract', $file);

	$recipients = new EchoSign\Info\RecipientInfo;
	$recipients->addRecipient( $sponsor->LegalEmail );

	$document->setRecipients($recipients);

	$contract = new Contract();
	$contract->SponsorID = $sponsor->ID;
	$contract->write();

	$url = 'http://openstack.dev9.tipit.net/contract/UpdateStatus?id=' . $contract->ID;

	$document->setCallbackInfo($url);

	$result = $api->sendDocument($document);

	$contract->EchosignID = $result->documentKeys->DocumentKey->documentKey;

	$contract->write();


	$sponsor->write();
	mail($sponsor->Email,'Your Sponsor Request has been ' . $_GET['action'], 'Awesome!');

	$this->setMessage('Success', '<b>' . $sponsor->CompanyName . '</b>: Updated');

	Director::redirect('/sangria/ViewSponsorsRequest');

}*/

	function SetSponsorMarketplaces(){

		$sponsor_id = intval($_POST['SponsorID']);

		DB::query("DELETE FROM  `SponsorMarketplace` WHERE SponsorID = " . $sponsor_id . "");

		foreach($_POST['marketplaces'] as $markets){
			$market = new SponsorMarketplace();
			$market->SponsorID = $sponsor_id;
			$market->MarketplaceID = $markets;
			$market->write();
		}
		Controller::curr()->redirect('/sangria/SetCategorySponsors');
	}
} 