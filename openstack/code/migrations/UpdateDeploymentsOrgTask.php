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

class UpdateDeploymentsOrgTask extends MigrationTask
{

    protected $title = "Update Deployment Organization";

    protected $description = "Update the OrgID in Deployment Table with the Member Organizatoin";

    function up()
    {
        echo "Starting Migration Proc ...<BR>";
	    $migration = Migration::get()->filter('Name',$this->title)->first();
        if (!$migration) {
            $migration = new Migration();
            $migration->Name = $this->title;
            $migration->Description = $this->description;
            $migration->Write();
           
            //run migration
            $query = 
            "UPDATE Deployment, (
                SELECT Org.ID AS OrgID, Deployment.ID AS DepID
                FROM Org, Deployment, DeploymentSurvey, Member
                WHERE
                Member.ID = DeploymentSurvey.MemberID AND
                Org.ID = Member.OrgID AND
                Deployment.DeploymentSurveyID = DeploymentSurvey.ID
            ) AS t1
            SET Deployment.OrgID = t1.OrgID
            WHERE t1.DepID = Deployment.ID";
            DB::query($query);
            
            
        }
        else{
            echo "Migration Already Ran! <BR>";
        }
        echo "Migration Done <BR>";
    }

    function down()
    {

    }
}