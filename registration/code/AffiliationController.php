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
class AffiliationController extends Page_Controller
{


    static $allowed_actions = array(
        'SaveAffiliation',
        'DeleteAffiliation',
        'GetAffiliation',
        'ListAffiliations',
        'ListOrganizations',
        'AffiliationsCount'
    );

    function init()
    {
        parent::init();
    }

    //Affiliation CRUD

    public function SaveAffiliation() {
	    try{
	        $newAffiliation = json_decode(file_get_contents("php://input"));
	        //server side validation
	        $org_name = Convert::raw2sql($newAffiliation->OrgName);
	        $org_name = trim($org_name);
	        $dbAffiliation = (isset($newAffiliation->Id) && is_numeric($newAffiliation->Id) && $newAffiliation->Id > 0) ? Affiliation::get()->byID($newAffiliation->Id) : new Affiliation();
	        //Check for a logged in member
	        if ($CurrentMember = Member::currentUser()) {
	            self::Save($dbAffiliation, $newAffiliation, $org_name, $CurrentMember);
	            echo json_encode('OK');
	            exit();
	        }
	    }
	    catch(Exception $ex){
		    echo json_encode('ERROR');
	    }
    }

    public static function Save(Affiliation $dbAffiliation, $newAffiliation, $org_name, Member $CurrentMember){
	    $org_name = Convert::raw2sql($org_name);
        // attempt to retrieve Org by the submitted name
        $org = Org::get()->filter('Name', $org_name)->First();

        if (!$org) {
            // no org matched, create a new org of that name and associate it
            $org = new Org();
            $org->Name = $org_name;
            $org->write();
            //register new request
            $new_request = new OrganizationRegistrationRequest();
            $new_request->MemberID = $CurrentMember->ID;
            $new_request->OrganizationID = $org->ID;
            $new_request->write();
        }

        $config = HTMLPurifier_Config::createDefault();
        // Remove any CSS or inline styles
        $config->set('CSS.AllowedProperties', array());
        $purifier = new HTMLPurifier($config);
	    if(!empty($newAffiliation->EndDate) && $newAffiliation->Current == 1){
		    $today    = new DateTime($newAffiliation->ClientToday);
		    $end_date = new DateTime($newAffiliation->EndDate);
		    if($end_date<$today)
			    throw new Exception('Current Affiliation: End Date must me greater than today!.');
	    }
        $dbAffiliation->OrganizationID = $org->ID;
        $dbAffiliation->MemberID = $CurrentMember->ID;
        $dbAffiliation->StartDate = $newAffiliation->StartDate;
        $dbAffiliation->EndDate = !empty($newAffiliation->EndDate) ? $newAffiliation->EndDate : null;
        $dbAffiliation->Current = $newAffiliation->Current == 1 ? true : false;
	    if(empty($newAffiliation->EndDate)){
		    $dbAffiliation->Current = true;
	    }
	    //$dbAffiliation->JobTitle = $purifier->purify($newAffiliation->JobTitle);
        //$dbAffiliation->Role      = $purifier->purify($newAffiliation->Role);
        $dbAffiliation->write();
    }

    public function ListAffiliations()
    {
        //Check for a logged in member
        if ($CurrentMember = Member::currentUser()) {
            $affiliations = array();

            $results = DB::query("SELECT A.ID,A.StartDate,A.EndDate,O.Name AS OrgName
                                  FROM Affiliation A
                                  INNER JOIN Org O on O.ID=A.OrganizationID
                                  WHERE A.MemberID = {$CurrentMember->ID} ORDER BY A.StartDate ASC, A.EndDate ASC");

            if (!is_null($results) && $results->numRecords() > 0) {
                for ($i = 0; $i < $results->numRecords(); $i++) {
                    $record = $results->nextRecord();
                    array_push($affiliations, array(
                        "Id" => $record['ID'],
                        "OrgName" => $record['OrgName'],
                        "EndDate" => is_null($record['EndDate']) ? '' : $record['EndDate'],
                        "StartDate" => $record['StartDate'],
                    ));
                }
            }
            echo json_encode($affiliations);
            exit();
        }
        echo json_encode('ERROR');
    }

    public function GetAffiliation($request)
    {
        if ($CurrentMember = Member::currentUser()) {

            $params = $request->allParams();
            $affilliation_id = $params["ID"];
            $affilliation_id = Convert::raw2sql($affilliation_id);

            $results = DB::query("SELECT A.ID,A.StartDate,A.EndDate,A.JobTitle,A.Role,A.Current,O.Name AS OrgName
                                  FROM Affiliation A
                                  INNER JOIN Org O on O.ID=A.OrganizationID
                                  WHERE A.ID = {$affilliation_id} ");
            if (!is_null($results) && $results->numRecords() > 0) {
                $affiliationDB = $results->nextRecord();
                $affiliation = new StdClass;
                $affiliation->Id = $affiliationDB['ID'];
                $affiliation->OrgName = $affiliationDB['OrgName'];
                $affiliation->StartDate = $affiliationDB['StartDate'];
                $affiliation->EndDate = is_null($affiliationDB['EndDate']) ? '' : $affiliationDB['EndDate'];
                //$affiliation->JobTitle = $affiliationDB['JobTitle'];
                //$affiliation->Role = $affiliationDB['Role'];
                $affiliation->Current = $affiliationDB['Current'];
                echo json_encode($affiliation);
                exit();
            }
        }
        echo json_encode('ERROR');
    }

    public function DeleteAffiliation($request)
    {
        if ($CurrentMember = Member::currentUser()) {
            $params = $request->allParams();
            $affilliation_id = $params["ID"];
            $affilliation_id = Convert::raw2sql($affilliation_id);
            $affiliationDB   = Affiliation::get()->byID($affilliation_id);
            if ($affiliationDB) {
                $affiliationDB->delete();
            }
            echo json_encode('OK');
            exit();
        }
        echo json_encode('ERROR');
    }

    public function ListOrganizations($request)
    {
        $organizations = array();
        $params = $request->getVars();
        $term = $params["term"];
        $term = Convert::raw2sql($term);

        $results = DB::query("SELECT O.Name
                                      FROM Org O
                                      WHERE Name LIKE '%{$term}%' ORDER BY Name ASC LIMIT 10");

        if (!is_null($results) && $results->numRecords() > 0) {
            for ($i = 0; $i < $results->numRecords(); $i++) {
                $record = $results->nextRecord();
                array_push($organizations, array(
                    "label" => $record['Name'],
                    "value" => $record['Name'],
                ));

            }

        }

        return json_encode($organizations);
    }

    public function AffiliationsCount()
    {
        if ($CurrentMember = Member::currentUser()) {
            $count = DB::query("SELECT COUNT(*) FROM Affiliation WHERE MemberID = {$CurrentMember->ID} ")->value();
            echo json_encode($count);
            exit();
        }
        echo json_encode('ERROR');
    }
}