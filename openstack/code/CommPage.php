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
class CommPage extends Page {
	
	static $has_many = array(
		'CommMembers' => 'CommMember'
	);
	
	public function getCMSFields()
	{
		$fields = parent::getCMSFields();
		
		$manager = new GridField(
			'CommMembers',
			'CommMember',
			$this->CommMembers()
		);	
		$fields->addFieldToTab("Root.CommMembers", $manager);
		
		return $fields;
	}
	
	public function Children(){
		return $this->CommMembers();
	}

}

class CommPage_Controller extends Page_Controller {
	
	//add our 'show' function as an allowed URL action
	static $allowed_actions = array(
		'show'
	);
	
	//Show the CommMember detail page using the CommPage_show.ss template
	function show() 
	{		
		if($CommMember = $this->getCommMember())
		{
			$Data = array(
				'CommMember' => $CommMember
			);
			
			//return our $Data to use on the page
			return $this->Customise($Data);
		}
		else
		{
			//Comm member not found
			return $this->httpError(404, 'Sorry that member of the community could not be found');
		}
	}
	
	//Get the current commMember from the URL, if any
	public function getCommMember()
	{
		$Params = $this->getURLParams();
		
		if(is_numeric($Params['ID']) && $CommMember = CommMember::get()->byID( $Params['ID']))
		{		
			return $CommMember;
		}
	}
	
	//Return our custom breadcrumbs
	public function Breadcrumbs() {
		
		//Get the default breadcrumbs
		$Breadcrumbs = parent::Breadcrumbs();
		
		if($CommMember = $this->getCommMember())
		{
			//Explode them into their individual parts
			$Parts = explode(SiteTree::$breadcrumbs_delimiter, $Breadcrumbs);
	
			//Count the parts
			$NumOfParts = count($Parts);
			
			//Change the last item to a link instead of just text
			$Parts[$NumOfParts-1] = ("<a href=\"" . $this->Link() . "\">" . $this->Title . "</a>");
			
			//Add our extra piece on the end
			$Parts[$NumOfParts] = $CommMember->Name; 
	
			//Return the imploded array
			$Breadcrumbs = implode(SiteTree::$breadcrumbs_delimiter, $Parts);			
		}

		return $Breadcrumbs;
	}		
}