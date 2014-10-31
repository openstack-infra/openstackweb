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
class Contract extends DataObject {

    static $db = array(
        'ContractSigned'    => 'Boolean',
        'ContractStart'     => 'Date',
        'ContractEnd'       => 'Date',
        'EchosignID'        => 'Text',
        'Status'            => 'Text'
    );


    static $has_one = array(
        'Company'          => 'Company',
		'ContractTemplate' => 'ContractTemplate',
    );


    public function getTitle(){
        return "Training Contract - ".$this->Company()->Name." - From ".$this->ContractStart." To ".$this->ContractEnd;
    }

    public static $summary_fields = array(
        'Company.Name',
        'ContractTemplate.Name'
    );

    public function getCMSFields()
    {
        $fields = new FieldList();
        $fields->push(new CheckboxField("ContractSigned","Contract Signed"));
        $contract_start = new DateField("ContractStart", "Contract Start");
        $fields->push(new TextField("EchosignID","Echosign ID"));
        $contract_start->setConfig('showcalendar', true);
        $fields->push($contract_start);
        $contract_end = new DateField("ContractEnd", "Contract End");
        $contract_end->setConfig('showcalendar', true);
        $fields->push($contract_end);

	    $companies = Company::get();

	    if($companies){
        $fields->push(new DropdownField(
            'CompanyID',
            'Company',
	        $companies->map("ID", "Name", "Please Select a Company")));
	    }

	    $templates = ContractTemplate::get();

	    if($templates){
	        $fields->push(new DropdownField(
	            'ContractTemplate',
	            'Template',
		        $templates->map("ID", "Name", "Please Select a Contract Template")));
	    }
        return $fields;
    }


    function getCMSValidator()
    {
        return $this->getValidator();
    }

    function getValidator()
    {
        $validator = new RequiredFields(array('ContractStart','ContractEnd'));
        return $validator;
    }

	/**
	 * Override
	 */
	function onAfterWrite(){
		parent::onAfterWrite();
		$this->updateStartAndEndDates();
	}

	/**
	 * update ContractStart and Contract End date based on signed date
	 * and contract template duration
	 */
	private function updateStartAndEndDates(){

	}

}

class Contract_Controller extends Page_Controller{

    static $allowed_actions = array(
        'UpdateStatus'
    );

    public function UpdateStatus(){

        $contract = DataObject::get_one('Contract','ID = ' . $_GET['id'] . " and EchosignID = '" . $_GET['documentKey'] . "'");

        $contract->Status = $_GET['status'];
        $contract->write();

        mail('patriciotarantino@gmail.com','Contract Update' . $contract->EchosignID, json_encode($_GET) );

        die();

        $ESLoader = new SplClassLoader('EchoSign', realpath(__DIR__.'/../../'));
        $ESLoader->register();

        $client = new SoapClient(EchoSign\API::getWSDL());
        $api = new EchoSign\API($client, 'PGRUY64K6T664Z');

        $data = $api->getDocumentInfo($contract->EchosignID);

        mail('patriciotarantino@gmail.com','Contract Update' . $contract->EchosignID, json_encode($data) );

    }

}