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
 * Class Company
 */
class Company extends DataObject implements PermissionProvider {

    function providePermissions() {
        return array(
            "ADD_COMPANY" =>array(
                'name' => 'Add Companies',
                'category' => 'Company Management',
                'help' => 'Allows to add a new company',
                'sort' => 0
            ),
            "DELETE_COMPANY" => array(
                'name' => 'Delete Companies',
                'category' => 'Company Management',
                'help' => 'Allows to delete a company',
                'sort' => 0
            ),
            "EDIT_COMPANY" => array(
                'name' => 'Edit Companies',
                'category' => 'Company Management',
                'help' => 'Allows to edit a company',
                'sort' => 0
            ),
            "MANAGE_COMPANY_PROFILE" => array(
                'name' => 'Manage Company Profile',
                'category' => 'Company Management',
                'help' => 'Allows to manage a company profile',
                'sort' => 0
            ),
            "MANAGE_COMPANY_LOGOS" => array(
                'name' => 'Manage Company Logo',
                'category' => 'Company Management',
                'help' => 'Allows to manage a company Logo',
                'sort' => 0
            ),
        );
    }

	private static $db = array(
		'Name'          => 'Text',
		'URL'           => 'Text',
       	'DisplayOnSite' => 'Boolean',
		'Featured'      => 'Boolean',
		'City'          => 'Varchar(255)',
		'State'         => 'Varchar(255)',
		'Country'       => 'Varchar(255)',
		'Description'   => 'HTMLText',
		'Industry'      => 'Text',
		'Products'      => 'HTMLText',
		'Contributions' => 'HTMLText',
		'ContactEmail'  => 'Text',
		'MemberLevel'   => "Enum('Platinum, Gold, StartUp, Corporate, Mention, None','None')",
		'AdminEmail'    => 'Text',
		'URLSegment'    => 'Text',
        'Color'         => 'Text',
		//marketplace updates
		'Overview'         => 'HTMLText',
		'Commitment'       => 'HTMLText',
		'CommitmentAuthor' => 'Varchar(255)',
	);

	private static $defaults = array(
        "Color" => 'C34431',
    );

    public function getCompanyColor(){
        return empty($this->Color)?"C34431":$this->Color;
    }

    public function getCompanyColorRGB() {
        $rgb_color = "rgb(195,68,49)";
        if (!empty($this->Color)) {
            if(strlen($this->Color) == 3) {
                $r = hexdec(substr($this->Color,0,1).substr($this->Color,0,1));
                $g = hexdec(substr($this->Color,1,1).substr($this->Color,1,1));
                $b = hexdec(substr($this->Color,2,1).substr($this->Color,2,1));
            } else {
                $r = hexdec(substr($this->Color,0,2));
                $g = hexdec(substr($this->Color,2,2));
                $b = hexdec(substr($this->Color,4,2));
            }
            $rgb_color = 'rgb('.$r.','.$g.','.$b.')';
        }

        return $rgb_color;
    }
	
	static $has_one = array(

		'CompanyListPage' => 'CompanyListPage',
		'Logo'            => 'BetterImage',
        'BigLogo'         => 'BetterImage',
     	'Submitter'       => 'Member',
		'CompanyAdmin'    => 'Member'
	);

	private static $has_many = array(
		'Photos'    => 'BetterImage',
        'Contracts' => 'Contract',
		'Services'  => 'CompanyService',
 	);

    private static $many_many = array(
        'Administrators' => 'Member'
    );

    //Administrators Security Groups
    static $many_many_extraFields = array(
        'Administrators' => array(
            'GroupID' => "Int",
        ),
    );

	private static $singular_name = 'Company';
	private static $plural_name = 'Companies';


    private static $belongs_many_many = array(
        'SponsorsPages' => 'SponsorsPage'
    );

	private static $summary_fields = array(
	    'Name'        => 'Company',
	    'MemberLevel' => 'MemberLevel'
	);
	
	function getCMSFields() {

        $_REQUEST["CompanyId"] = $this->ID;

        $large_logo  = new CustomUploadField('BigLogo', 'Large Company Logo');
		$large_logo->setFolderName('companies/main_logo');
		$large_logo->setAllowedFileCategories('image');

        $small_logo  = new CustomUploadField('Logo', 'Small Company Logo');
		$small_logo->setAllowedFileCategories('image');
        //logo validation rules
        $large_logo_validator = new Upload_Image_Validator();
        $large_logo_validator->setAllowedExtensions(array('jpg','png','jpeg'));
        $large_logo_validator->setAllowedMaxImageWidth(500);
        $large_logo->setValidator($large_logo_validator);

        $small_logo_validator = new Upload_Image_Validator();
        $small_logo_validator->setAllowedExtensions(array('jpg','png','jpeg'));
        $small_logo_validator->setAllowedMaxImageWidth(200);
        $small_logo->setValidator($small_logo_validator);


        $fields =  new FieldList(new TabSet(
            $name = "Root",
            new Tab(
                $title='Company',
                new HeaderField("Company Data"),
                new TextField('Name','Company Name'),
                new TextField('URLSegment','Unique page name for this company profile (ie: company-name)'),
                new TextField ('URL','Company Web Address (URL)'),
                $level = new DropDownField(
                    'MemberLevel',
                    'OpenStack Foundation Member Level',
                    $this->dbObject('MemberLevel')->enumValues()
                ),
                new ColorField("Color","Company Color"),
	            new CheckboxField ('DisplayOnSite','List this company on openstack.org'),
                new CheckboxField ('Featured','Include this company in featured companies area'),
                new LiteralField('Break','<hr/>'),
                $this->canEditLogo()? $large_logo: new LiteralField('Space','<br/>'),
                $this->canEditLogo()? $small_logo: new LiteralField('Space','<br/>'),
                new TextField('Industry', 'Industry (<4 Words)'),
                new HtmlEditorField('Description', 'Company Description'),
                new HtmlEditorField('Contributions', 'How you are contributing to OpenStack (<150 words)'),
                new HtmlEditorField('Products', 'Products/Services Related to OpenStack (<100 words)'),
	            new HtmlEditorField('Overview', 'Company Overview'),
	            new TextField('CommitmentAuthor','Commitment Author (Optional)'),
	            new HtmlEditorField('Commitment', "OpenStack Commitment"),
                new LiteralField('Break','<hr/>'),
                new TextField('ContactEmail', 'Best Contact email address (optional)')
            )
        ));

		$level->setEmptyString('-- Choose One --');

		if($this->ID > 0){

			$admin_list = $this->Administrators()->sort('ID');
			$query      = $admin_list->dataQuery();

			$query->groupby('MemberID');

			$admin_list = $admin_list->setDataQuery($query);

			$admins = new GridField('Administrators', 'Company Administrators',
				$admin_list,
				GridFieldConfig_RelationEditor::create(10)
			);
			$admins->getConfig()->removeComponentsByType('GridFieldEditButton');
			$admins->getConfig()->removeComponentsByType('GridFieldAddNewButton');

			$admins->getConfig()->getComponentByType('GridFieldDataColumns')->setDisplayFields(
				array(
					'FirstName'             => 'First Name',
					'Surname'               => 'Last Name',
					'Email'                 => 'Email',
					'DDLAdminSecurityGroup' => 'Security Group'
				));

			$contracts = new GridField("Contracts", "Contracts", $this->Contracts(), GridFieldConfig_RecordEditor::create(10));

			$fields->addFieldsToTab('Root.Administrators',
				array(
					new HeaderField("Companies Administrators"),
					$admins
				)
			);

			$fields->addFieldsToTab('Root.Contracts',
				array(
					new HeaderField("Companies Contracts"),
					$contracts
				)
			);
		}

	    return $fields;
	}

	function getCMSValidator()
	{
		return $this->getValidator();
	}

	function getValidator()
	{
		if(isset($_REQUEST["action_doDelete"]))
			return new RequiredFields();

		$validator_fields  = new RequiredFields(array('Name','URLSegment','URL','Overview','Commitment','Logo'));

		return $validator_fields;
	}

    //Generate Yes/No for DOM / Complex Table Field
	public function DisplayNice() {
	   return $this->DisplayOnSite ? 'Yes' : 'No';
	}

	function onBeforeWrite() {
		parent::onBeforeWrite();

		// Assign an Admin using the provided email address
		if ($this->AdminEmail) {
			$EmailAddress = Convert::raw2sql($this->AdminEmail);
			$Member = Member::get()->filter('Email',$EmailAddress)->first();
			if ($Member) {
				$this->CompanyAdminID = $Member->ID;
			}
		} else {
			$this->CompanyAdminID = "";
		}

		// If there is no URLSegment set, generate one from Title
        if((!$this->URLSegment || $this->URLSegment == 'new-comapny') && $this->Title != 'New Company') 
        {
            $this->URLSegment = SiteTree::generateURLSegment($this->Title);
        } 
        else if($this->isChanged('URLSegment')) 
        {
            // Make sure the URLSegment is valid for use in a URL
            $segment = preg_replace('/[^A-Za-z0-9]+/','-',$this->URLSegment);
            $segment = preg_replace('/-+/','-',$segment);
              
            // If after sanitising there is no URLSegment, give it a reasonable default
            if(!$segment) {
                $segment = "product-$this->ID";
            }
            $this->URLSegment = $segment;
        }
  
        // Ensure that this object has a non-conflicting URLSegment value.
        $count = 2;
        while($this->LookForExistingURLSegment($this->URLSegment)) 
        {
            $this->URLSegment = preg_replace('/-[0-9]+$/', null, $this->URLSegment) . '-' . $count;
            $count++;
        }				

	}

	//Test whether the URLSegment exists already on another Product
    function LookForExistingURLSegment($URLSegment)
    {
	    return Company::get()->filter(array('URLSegment' => $URLSegment , 'ID:not'=>  $this->ID))->first();
    }

	function AdminName() {
		$Admin = Member::get()->byID($this->CompanyAdminID);
		if ($Admin) {
			return $Admin->FirstName ." ". $Admin->Surname;
		} else {
			return "(no member assigned)";
		}
	}

    function EditLink() {
    	$CompaniesPage = CompanyListPage::get()->first();
    	return $CompaniesPage->Link()."edit/".$this->ID;
    }

    //Generate the link for this product
    function ShowLink()
    {
        $CompaniesPage = CompanyListPage::get()->first();
        if ($this->Description) {
    		return $CompaniesPage->Link()."profile/".$this->URLSegment;
    	} else {
    		return $this->URL;
    	}
    }

	function IsExternalUrl(){
		return !$this->Description? true:false;
	}

    public function getInputSubmitPageUrl(){
        $type  = null;
        $pageId = Convert::raw2sql($_REQUEST["PageId"]);
        if(isset($pageId)){
            $sqlQuery = new SQLQuery();
            $sqlQuery->setSelect("SubmitPageUrl");
            $sqlQuery->setFrom("SponsorsPage_Companies");
            $sqlQuery->setWhere("CompanyID={$this->ID} AND SponsorsPageID={$pageId}");
            $type = $sqlQuery->execute()->value();
        }
        return new TextField("SubmitPageUrl_{$this->ID}","SubmitPageUrl_{$this->ID}",$type,255);
    }

    //helper function to create Drop Down for Sponsorship type
    public function getDDLSponsorshipType(){
        $type  = null;
        $pageId = Convert::raw2sql($_REQUEST["PageId"]);

        if(isset($pageId)){
            $sqlQuery = new SQLQuery();
            $sqlQuery->ssetSelect("SponsorshipType");
            $sqlQuery->setFrom("SponsorsPage_Companies");
            $sqlQuery->ssetWhere("CompanyID={$this->ID} AND SponsorsPageID={$pageId}");
            $type = $sqlQuery->execute()->value();
        }
        return new DropdownField("SponsorshipType_{$this->ID}", "SponsorshipType_{$this->ID}", array(
            'Headline'  => 'Headline',
            'Premier'   => 'Premier',
            'Event'     => 'Event',
            'Startup'   => 'Startup',
            'InKind'    => 'In Kind',
            'Spotlight' => 'Spotlight',
            ''          => '--NONE--'),$type);
    }

    //helper function to create Drop Down for Logo Size
    public function getDDLLogoSize(){
        $size  = null;
        $pageId = Convert::raw2sql($_REQUEST["PageId"]);

        if(isset($pageId)){
            $sqlQuery = new SQLQuery();
            $sqlQuery->setSelect("LogoSize");
            $sqlQuery->setFrom("SponsorsPage_Companies");
            $sqlQuery->setWhere("CompanyID={$this->ID} AND SponsorsPageID={$pageId}");
            $size = $sqlQuery->execute()->value();
            if(is_null($size))
                $size='None';
        }

	    $sizes = array(
		    'Small'  => 'Small',
		    'Medium' => 'Medium',
		    'Large'  => 'Large',
		    'Big'    => 'Big',
		    'None'   => '--NONE--'
	    );

        return new DropdownField("LogoSize_{$this->ID}", "LogoSize_{$this->ID}",$sizes ,$size);
    }

    public function SidebarLogoPreview(){
        $img = $this->Logo();
        $img = $img->exists()?$img:$this->Logo();
        if(isset($img)  && Director::fileExists($img->Filename) && $img->exists()){
            $img = $img->SetWidth(100);
            return "<img alt='{$this->Name}_sidebar_logo' src='{$img->getURL()}' class='sidebar-logo-company'/>";
        }
        return 'missing';
    }


    public function SmallLogoPreview($width=210){
        $img = $this->Logo();
        $img=$img->SetWidth($width);
        if(isset($img) && Director::fileExists($img->Filename) && $img->exists()){
            return "<img alt='{$this->Name}_small_logo' src='{$img->getURL()}' class='small-logo-company'/>";
        }
        return 'missing';
    }

    public function MediumLogoPreview(){
        $img = $this->BigLogo();
        $img = $img->exists()?$img:$this->Logo();
        if(isset($img)  && Director::fileExists($img->Filename) && $img->exists()){
            $img= $img->SetWidth(210);
            return "<img alt='{$this->Name}_medium_logo' src='{$img->getURL()}' class='medium-logo-company'/>";
        }
        return 'missing';
    }

    public function LargeLogoPreview(){
        $img = $this->BigLogo();
        $img = $img->exists()?$img:$this->Logo();
        if(isset($img)  && Director::fileExists($img->Filename) && $img->exists()){
            $img= $img->SetWidth(300);
            return "<img alt='{$this->Name}_large_logo' src='{$img->getURL()}' class='large-logo-company'/>";
        }
        return 'missing';
    }

    public function BigLogoPreview(){
        $img = $this->BigLogo();
        if(isset($img) && Director::fileExists($img->Filename) && $img->exists()){
            $img=$img->SetWidth(300);
            return "<img alt='{$this->Name}_big_logo' src='{$img->getURL()}' class='big-logo-company'/>";
        }
        return 'missing';
    }

    public function SubmitLandPageUrl(){
        $url = $this->URL;
        $page = Director::get_current_page();
        $res = $page->getManyManyComponents("Companies","CompanyID={$this->ID}","ID")->First();
        $submit_url = $res->SubmitPageUrl;
        if(isset($submit_url) && $submit_url!='') return $submit_url;
        return $url;
    }

    public function SubmitLogo(){
        $page = Director::get_current_page();
        $res  = $page->getManyManyComponents("Companies","CompanyID={$this->ID}","ID")->First();
        $logo_size    = $res->LogoSize;
        $sponsor_type = $res->SponsorshipType;
        switch($logo_size){
            case 'Small':
                    return $this->SmallLogoPreview();
                break;
            case 'Medium':
                    return $this->MediumLogoPreview();
                break;
            case 'Large':
                return $this->LargeLogoPreview();
                break;
            case 'Big':
                    return $this->BigLogoPreview();
                break;
        }
        //if we dont have set the logo_size yet
        //then we use the default

        switch($sponsor_type){
            case 'Headline':
                return $this->BigLogoPreview();
                break;
            case 'Premier':
                return $this->LargeLogoPreview();
                break;
            case 'Event':
                return $this->SmallLogoPreview();
                break;
            case 'Startup':
                return $this->SmallLogoPreview();
                break;
            case 'InKind':
                return $this->SmallLogoPreview();
                break;
            case 'Spotlight':
                return $this->SmallLogoPreview();
                break;
        }

        return $this->SmallLogoPreview();
    }

	function onAfterWrite() {
		parent::onAfterWrite();
		//update all relationships with Administrators
		foreach($this->Administrators() as $member){
			if(isset($_REQUEST["AdminSecurityGroup_{$member->ID}"])){
				$groups_ids = $_REQUEST["AdminSecurityGroup_{$member->ID}"];
				if(is_array($groups_ids) && count($groups_ids)>0){
					DB::query("DELETE FROM Company_Administrators WHERE CompanyID={$this->ID} AND MemberID={$member->ID};");
					foreach($groups_ids as $group_id){
						$group_id = intval(Convert::raw2sql($group_id));
						DB::query("INSERT INTO Company_Administrators (GroupID,CompanyID,MemberID) VALUES ({$group_id},{$this->ID},{$member->ID});");
					}
				}
			}
		}
	}

    //Security checks

	public function canCreate($member = null) {
        $MemberID = Member::currentUserID();
        return  $this->CompanyAdminID == $MemberID  || Permission::check("ADD_COMPANY") || $this->PermissionCheck(array("ADD_COMPANY"));
    }

	public function canEdit($member = null) {
        $MemberID = Member::currentUserID();
        return  $this->CompanyAdminID == $MemberID  || Permission::check("EDIT_COMPANY") || $this->PermissionCheck(array("EDIT_COMPANY"));
    }

	public function canDelete($member = null) {
        $MemberID = Member::currentUserID();
        return  $this->CompanyAdminID == $MemberID  || Permission::check("DELETE_COMPANY") || $this->PermissionCheck(array("DELETE_COMPANY"));
    }


    /*
     * Helper method to check if current user has the permissions
     * passed by arg ($permission_2_check) on the company admin security group that is currently assigned
     */
    private function PermissionCheck(array $permission_2_check){
        //check groups
        $current_user_id        = intval(Member::currentUserID());
        $admins_groups_for_user = $this->getManyManyComponents("Administrators","MemberID={$current_user_id}","ID");
        if($admins_groups_for_user){//current user has some admin level
	        foreach($admins_groups_for_user as $admin_group){
	            $group_id = intval($admin_group->GroupID);
	            $permissions = Permission::get()->filter('GroupID',$group_id);
	            foreach($permissions as $p){
	                if(in_array($p->Code,$permission_2_check))
	                    return true;
	            }
	        }
        }
        return false;
    }

    function IsCompanyAdmin($memberId){
        $admin = $this->getManyManyComponents("Administrators","MemberID={$memberId}","ID")->First();
        if($admin){//current user has some admin level
            $group_id = $admin->GroupID;
            return $group_id>0;
        }
        return false;
    }

    function canEditProfile(){
        $MemberID = Member::currentUserID();
        return $this->CompanyAdminID == $MemberID || Permission::check("MANAGE_COMPANY_PROFILE") || $this->PermissionCheck(array("MANAGE_COMPANY_PROFILE"));
    }

    function canEditLogo(){
        $MemberID = Member::currentUserID();
        return $this->CompanyAdminID == $MemberID || Permission::check("MANAGE_COMPANY_LOGOS") || Permission::check("MANAGE_COMPANY_PROFILE") || $this->PermissionCheck(array("MANAGE_COMPANY_PROFILE",'MANAGE_COMPANY_LOGOS'));
    }

	/**
	 * @param int $memberId
	 * @return Group[]|null
	 */
	function getAdminGroupsByMember($memberId){
		$associations = $this->getManyManyComponents("Administrators","MemberID={$memberId}","ID");
		if($associations){
			$res = array();
			foreach($associations as $a){
				$g = Group::get()->byID((int)$a->GroupID);
				if($g) $res[$g->Code]=$g;
			}
			return $res;
		}
	    return null;
	}
}