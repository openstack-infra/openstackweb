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

class AffiliationsMigrationTask extends MigrationTask
{

    protected $title = "Affiliation Migration";

    protected $description = "Migrates all user organizations to new affiliation DB schema";

    function up()
    {
        echo "Starting Migration Proc ...<BR>";
        //check if migration already had ran ...
        $migration = Migration::get()->filter('Name', $this->title)->First();
        if (!$migration) {
            //if not create migration and run it...
            $migration = new Migration();
            $migration->Name = $this->title;
            $migration->Description = $this->description;
            $migration->Write();
            //run migration proc

            //get migration data from cvs file,
            //this cvs file contains all Orgs that should be splitted on several
            //current affiliations
            $ds = new CvsDataSourceReader("\t");
            $cur_path = Director::baseFolder();
            $ds->Open($cur_path . "/openstack/code/migrations/data/orgs.csv");
            $headers = $ds->getFieldsInfo();

            $split_organizations = array();
            //and stored those ones on a hash
            try {
                while (($row = $ds->getNextRow()) !== FALSE) {
                    $id = $row[$headers["ID"]];
                    $split = $row[$headers["Split"]];
                    $name = $row[$headers["Name"]];
                    if ($split == 'True' || $split == 'SEMICOLON') {
                        $split_organizations[$id] = array(
                            "Split" => $split,
                            "Name" => $name
                        );
                    }
                }
            } catch (Exception $e) {
                $status = 0;
            }
            echo sprintf("Multiple Organization Count %d </BR>", count($split_organizations));
            echo 'Procesing Members.... </BR>';
            $count = DB::query("SELECT COUNT(ID) From Member Where OrgID IS NOT NULL;")->value();
            echo sprintf("%d Members to process </BR>", $count);
            $page = 0;
            $page_size = 1000;
            $page_count = ceil($count / $page_size);

            for ($page = 0; $page <= $page_count; $page++) {
                echo sprintf("Procesing Member Page  %d Of %d ...</BR>", $page, $page_count);
                $start_from = ($page) * $page_size;
                //get current org
                $res = DB::query("SELECT Member.ID AS MemberID, Org.ID AS OrgID, Org.Name AS OrgName , Org.IsStandardizedOrg, Member.JobTitle, Member.Role From Member INNER JOIN Org ON Org.ID=Member.OrgID  Where OrgID IS NOT NULL LIMIT {$start_from}, {$page_size};");
                $multi_org_id = null;
                foreach ($res as $record) {
                    $curOrgID = $record["OrgID"];
                    $curMemberID = $record["MemberID"];
                    $curRole = $record["Role"];
                    $curJobTitle = $record["JobTitle"];

                    //is multiple current affiliations?
                    if (isset($split_organizations[$curOrgID])) {
                        $multi_org_id = $curOrgID;
                        $org_aux = $split_organizations[$curOrgID];
                        $org_name = $org_aux["Name"];
                        $mode = $org_aux["Split"];
                        $affiliations = array();

                        switch ($mode) {
                            case "True":
                                $affiliations = explode(",", $org_name);
                                break;
                            case "SEMICOLON":
                                $affiliations = explode(";", $org_name);
                                break;
                        }
                        foreach ($affiliations as $a) {
                            $a = Convert::raw2sql(trim($a));
                            $org_id = DB::query("SELECT ID From Org WHERE Name = '{$a}' AND ID <> {$curOrgID} ORDER BY ID ASC LIMIT 1")->value();
                            if (is_null($org_id))
                                $org_id = DB::query("SELECT ID From Org WHERE  Name Like '%{$a}%' AND ID <> {$curOrgID} ORDER BY ID ASC LIMIT 1")->value();
                            if (is_null($org_id)) {
                                //create Org
                                $org_id = $this->writeOrg($a);
                            }
                            //create affiliation
                            $this->writeAffiliation(null, null, null, null, true, $curMemberID, $org_id);

                            //check if we have it on history repeated to get the from date

                            $res2 = DB::query("SELECT NewAffiliation,OldAffiliation,Created,LastEdited FROM AffiliationUpdate WHERE MemberID={$curMemberID} AND NewAffiliation=OldAffiliation AND OldAffiliation='{$curOrgID}' ORDER BY Created ASC;");
                            if($res2){
                                foreach ($res2 as $record2) {
                                    $this->updateAffiliationStartDate($record2['Created'],$curMemberID,$org_id);
                                    break;
                                }
                            }
                        }

                    } else {
                        //single current affiliation
                        //create affiliation
                        $this->writeAffiliation(null, null, $curJobTitle, $curRole, true, $curMemberID, $curOrgID);
                    }

                    //now build affiliation history ...
                    if(!is_null($multi_org_id)){
                        $res3 = DB::query("SELECT NewAffiliation,OldAffiliation,Created,LastEdited FROM AffiliationUpdate WHERE MemberID={$curMemberID} AND NewAffiliation<>'{$multi_org_id}' AND OldAffiliation<>'{$multi_org_id}'   ORDER BY Created ASC;");
                    }
                    else{
                        $res3 = DB::query("SELECT NewAffiliation,OldAffiliation,Created,LastEdited FROM AffiliationUpdate WHERE MemberID={$curMemberID} ORDER BY Created ASC;");
                    }

                    foreach ($res3 as $record3) {
                        $old_org = Convert::raw2sql(trim($record3["OldAffiliation"]));
                        $new_org = Convert::raw2sql(trim($record3["NewAffiliation"]));

                        $created = $record3["Created"];

                        $new_org_id = $this->getOrgId($new_org);
                        $old_org_id = $this->getOrgId($old_org);

                        if(is_null($new_org_id)) continue;

                        //create affiliation
                        if ($new_org_id == $old_org_id || is_null($old_org_id)) {
                            //check if current
                            $count = DB::query("SELECT COUNT(ID) FROM Affiliation WHERE MemberID={$curMemberID} AND OrganizationID={$new_org_id} AND Current=1;")->value();
                            if ($count > 0){
                                $this->updateAffiliationStartDate($created,$curMemberID,$new_org_id);
                                continue;
                            }
                            //check if does not not exists already
                            $count = DB::query("SELECT COUNT(ID) FROM Affiliation WHERE MemberID={$curMemberID} AND OrganizationID={$new_org_id}")->value();
                            if ($count > 0) continue;
                            //new one
                            $this->writeAffiliation($created, null, null, null, false, $curMemberID, $new_org_id);
                        }
                        else{
                            //try to update old affiliation
                            $this->updateAffiliationEndDate($created,$curMemberID,$old_org_id);
                            //check if current
                            $count = DB::query("SELECT COUNT(ID) FROM Affiliation WHERE MemberID={$curMemberID} AND OrganizationID={$new_org_id} AND Current=1;")->value();
                            if ($count > 0) {
                                $this->updateAffiliationStartDate($created,$curMemberID,$new_org_id);
                                continue;
                            }
                            $this->writeAffiliation($created, null, null, null, false, $curMemberID, $new_org_id);
                        }
                    }
                }
            }
        }
        else{
            echo "Migration Already Ran! <BR>";
        }
        echo "Migration Done <BR>";
    }


    private function getOrgId($orgId){
        $res = null;
        if(!is_numeric($orgId)){
            $res = DB::query("SELECT ID From Org Where Name ='{$orgId}' ORDER BY ID ASC LIMIT 1;")->value();
            if (is_null($res))
                $res = DB::query("SELECT ID From Org Where Name Like '%{$orgId}%' ORDER BY ID ASC LIMIT 1;")->value();
        }
        else{
            $res = intval($orgId);
        }
        return $res;
    }

    private function writeAffiliation($start_date, $end_data, $job_title, $role, $current, $member_id, $org_id)
    {
        $role = Convert::raw2sql($role);
        $job_title = Convert::raw2sql($job_title);
        $current = $current==true?1:0;
        $query = "INSERT INTO  `Affiliation` (`ClassName`,`Created`,`LastEdited`,`StartDate`,`EndDate`,`JobTitle`,`Role`,`MemberID`,`OrganizationID`,`Current`)";
        $query .=" VALUES('Affiliation',now(),now(),";
        $query .= !is_null($start_date)?"'{$start_date}',":"NULL,";
        $query .= !is_null($end_data)?"'{$end_data}',":"NULL,";
        $query .= !is_null($job_title)?"'{$job_title}',":"NULL,";
        $query .= !is_null($role)?"'{$role}',":"NULL,";
        $query .= "{$member_id},{$org_id},{$current});";
        //echo sprintf("Query %s <BR>",$query);
        DB::query($query);
    }

    private function updateAffiliationEndDate($end_data,$member_id, $org_id)
    {
        $query = "UPDATE `Affiliation` SET  `EndDate`= '{$end_data}' WHERE MemberID = {$member_id} AND OrganizationID={$org_id} AND EndDate IS NULL";
        DB::query($query);
    }

    private function updateAffiliationStartDate($start_data,$member_id, $org_id)
    {
        $query = "UPDATE `Affiliation` SET  `StartDate`= '{$start_data}' WHERE MemberID = {$member_id} AND OrganizationID={$org_id} AND StartDate IS NULL";
        DB::query($query);
    }

    private function writeOrg($name){
        $name = Convert::raw2sql($name);
        $query= " INSERT INTO `Org` (`ClassName`,`Created`,`LastEdited`,`Name`,`IsStandardizedOrg`,`FoundationSupportLevel`,`OrgProfileID`) VALUES ('Org',now(),now(),'{$name}',0,'Platinum Member',0); ";
        DB::query($query);
        $query = "SELECT ID From Org WHERE Name='{$name}' LIMIT 1;";
        return DB::query($query)->value();
    }

    function down()
    {

    }
}