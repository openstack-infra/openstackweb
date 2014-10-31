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
class LegalAgreement extends DataObject {
 
    static $db = array(
        'Signature' => 'Varchar(255)'
    );
    
    static $has_one = array (
    	'LegalDocumentPage' => 'LegalDocumentPage',
        'Member' => 'Member'
    );

    function DocumentName() {
    	$LegalDocumentPage = LegalDocumentPage::get()->byID($this->LegalDocumentPageID);
    	return $LegalDocumentPage->Title;
    }

    function DocumentLink() {
    	$LegalDocumentPage =  LegalDocumentPage::get()->byID($this->LegalDocumentPageID);
    	return $LegalDocumentPage->Link();
    }

}