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
 * Defines Sangria Admin area
 */
class SangriaPage extends Page {
	private static $db = array(
	);

    private static $has_one = array(
	);
}

/**
 * Class SangriaPage_Controller
 */
final class SangriaPage_Controller extends Page_Controller {

	public static $submissionsCount = 0;
	public static $default_start_date;
	public static $default_end_date;
    public static $date_filter_query;

    static $allowed_actions = array(
        'GenerateAutoLoginHashes',
        // Manage User Stories and Deployments
	    'SurveyDetails',
        'SetCaseStudy',
	    'DeploymentSurveyDeploymentsFilters',
	    'FilterResults',
    );

    function init() {
        if(!Permission::check("SANGRIA_ACCESS")) Security::permissionFailure();
        parent::init();
	    Requirements::css(THIRDPARTY_DIR . '/jquery-ui-themes/smoothness/jquery-ui.css');
	    Requirements::javascript(THIRDPARTY_DIR . '/jquery-ui/jquery-ui.js');
	    Requirements::javascript('themes/openstack/javascript/jquery.tablednd.js');
	    Requirements::javascript('themes/openstack/javascript/querystring.jquery.js');
	    Requirements::css("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.css");
        Requirements::javascript("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.js");
        Requirements::css("themes/openstack/css/deployment.survey.page.css");
        Requirements::javascript("themes/openstack/javascript/deployment.survey.filters.js");

        self::$default_start_date = date('Y/m/d',strtotime('-1 months')).' 00:00';
        self::$default_end_date  = date('Y/m/d').' 23:59';
    }

    function providePermissions() {
        return array(
            "SANGRIA_ACCESS" => "Access the Sangria Admin"
        );
    }

    // DASHBOARD METRICS

    function IndividualMemberCount() {
	    return EntityCounterHelper::getInstance()->EntityCount('FoundationMember',function (){
		    $query =  new IndividualFoundationMemberCountQuery();
		    $res = $query->handle(null)->getResult();
		    return $res[0];
	    });
    }

	function CommunityMemberCount() {
		return EntityCounterHelper::getInstance()->EntityCount('CommunityMember',function (){
			$query =  new IndividualCommunityMemberCountQuery();
			$res = $query->handle(null)->getResult();
			return $res[0];
		});
	}

    function NewsletterMemberCount() {
	    return EntityCounterHelper::getInstance()->EntityCount('NewsletterMember',function (){
		    $query =  new FoundationMembersSubscribedToNewsLetterCountQuery();
		    $res = $query->handle(new FoundationMembersSubscribedToNewsLetterCountQuerySpecification)->getResult();
		    return $res[0];
	    });
    }

    function NewsletterPercentage() {
        return number_format(($this->NewsletterMemberCount() / $this->IndividualMemberCount())*100,2);
    }

    function UserStoryCount() {
	    return EntityCounterHelper::getInstance()->EntityCount('UserStory',function (){
		    $query =  new UserStoriesCountQuery();
		    $res = $query->handle(new UserStoriesCountQuerySpecification(true))->getResult();
		    return $res[0];
	    });
    }

    function UserLogoCount() {
	    return EntityCounterHelper::getInstance()->EntityCount('UserLogo',function (){
		    $query =  new UserStoriesCountQuery();
		    $res = $query->handle(new UserStoriesCountQuerySpecification(false))->getResult();
		    return $res[0];
	    });
    }

    function PlatinumMemberCount() {
        return EntityCounterHelper::getInstance()->EntityCount('PlatinumOrg',function (){
		    $query =  new CompanyCountQuery();
		    $res = $query->handle(new CompanyCountQuerySpecification('Platinum'))->getResult();
		    return $res[0];
	    });
    }

    function GoldMemberCount() {
 	    return EntityCounterHelper::getInstance()->EntityCount('GoldOrg',function (){
		    $query =  new CompanyCountQuery();
		    $res = $query->handle(new CompanyCountQuerySpecification('Gold'))->getResult();
		    return $res[0];
	    });
    }

    function CorporateSponsorCount() {
        return EntityCounterHelper::getInstance()->EntityCount('CorporateOrg',function (){
		    $query =  new CompanyCountQuery();
		    $res = $query->handle(new CompanyCountQuerySpecification('Corporate'))->getResult();
		    return $res[0];
	    });
    }

    function StartupSponsorCount() {
        return EntityCounterHelper::getInstance()->EntityCount('StartupOrg',function (){
		    $query =  new CompanyCountQuery();
		    $res = $query->handle(new CompanyCountQuerySpecification('Startup'))->getResult();
		    return $res[0];
	    });
    }

    function SupportingOrganizationCount() {
        return EntityCounterHelper::getInstance()->EntityCount('MentionOrg',function (){
		    $query =  new CompanyCountQuery();
		    $res = $query->handle(new CompanyCountQuerySpecification('Mention'))->getResult();
		    return $res[0];
	    });
    }

    function TotalOrganizationCount() {
	    return EntityCounterHelper::getInstance()->EntityCount('TotalOrgs',function (){
		    $query =  new CompanyCountQuery();
		    $res = $query->handle(new CompanyCountQuerySpecification())->getResult();
		    return $res[0];
	    });
    }

    function NewsletterInternationalCount() {
	    return EntityCounterHelper::getInstance()->EntityCount('NewsletterInternationalCount',function (){
		    $query =  new FoundationMembersSubscribedToNewsLetterCountQuery();
		    $res = $query->handle(new FoundationMembersSubscribedToNewsLetterCountQuerySpecification('US'))->getResult();
		    return $res[0];
	    });
    }

    function NewsletterInternationalPercentage() {
        return number_format(($this->NewsletterInternationalCount()/$this->NewsletterMemberCount())*100,2);
    }

    function IndividualMemberCountryCount() {
        $Count = DB::query('select count(distinct(Member.Country)) from Member left join Group_Members on Member.ID = Group_Members.MemberID where Group_Members.GroupID = 5;')->value();
        return $Count;
    }

    function InternationalOrganizationCount() {
  	    return EntityCounterHelper::getInstance()->EntityCount('InternationalOrganization',function (){
		    $query =  new CompanyCountQuery();
		    $res = $query->handle(new CompanyCountQuerySpecification(null,'US'))->getResult();
		    return $res[0];
	    });
    }

    function OrgsInternationalPercentage() {
        return number_format(($this->InternationalOrganizationCount()/$this->TotalOrganizationCount())*100,2);
    }

    //Survey date filters constructor

    function DateFilters($action='') {
        $start_date = ($this->request->getVar('From')) ? $this->request->getVar('From') : self::$default_start_date;
        $end_date = ($this->request->getVar('To')) ? $this->request->getVar('To') : self::$default_end_date;
        $data = array("start_date"=>$start_date, "end_date"=>$end_date, "action"=>$action);
        return $this->renderWith("SurveyDateFilters",$data);
    }

	public function getSortDir($type, $read_only=false){
		$default = 'asc';
		$dir     = Session::get($type.'.sort.dir');
		if(empty($dir)){
			$dir = $default;
		}
		else{
			$dir = $dir=='asc'?'desc':'asc';
		}
		if(!$read_only)
			Session::set($type.'.sort.dir',$dir);
		return $dir;
	}

	function getSortIcon($type){
		return $this->getSortDir($type,true) == 'desc'?'&blacktriangledown;':'&blacktriangle;';
	}

    function UserStoriesPerIndustry($Industry){
        return DataObject::get("UserStory","UserStoriesIndustryID = " . $Industry);
    }

	function Countries(){
		return CountryCodes::asObject();
	}

	function getQuickActionsExtensions(){
		$html = '';
		$this->extend('getQuickActionsExtensions',$html);
		return $html;
	}

	public function DeploymentMatchingOrgs() {
		$filterWhereClause = self::generateFilterWhereClause();

		$results = DB::query("select o.Name from Deployment d join DeploymentSurvey s on (d.DeploymentSurveyID = s.ID) join Org o on (s.OrgID = o.ID) where 1=1".$filterWhereClause);
		$list = new DataObjectSet();

		for( $i=0; $i < $results->numRecords(); $i++) {
			$record = $results->nextRecord();
			$do = new ArrayList();
			$do->OrgName = $record["Name"];
			$list->push($do);
		}
		return $list;
	}

	public static function generateSelectListSummary($fieldName, $optionSet, $applyDateFilters=false) {
		$list = new ArrayList();

		$urlString = $_SERVER["REDIRECT_URL"]."?";
		$keyUrlString = "";
		$keyValue = "";

		foreach($_GET as $key=>$value) {
			if (preg_match("/Filter$/",$key)) {
				if ($key != $fieldName."Filter") {
					$urlString .= $key."=".$value."&";
				} else {
					$keyUrlString = $key."=".$value;
					$keyValue = $value;
				}
			}
		}

		foreach( $optionSet as $option => $label ) {

            $query = "select count(*) from Deployment where ".$fieldName." like '%".$option."%'".self::generateFilterWhereClause();
            $query .= ($applyDateFilters) ? ' AND '.self::$date_filter_query : '';

			$count = DB::query($query)->value();
			$do = new DataObject();

			$href = $urlString.$fieldName."Filter=".$option;

			if ($applyDateFilters) {
				$start_date = Controller::curr()->request->getVar('From');
				$end_date = Controller::curr()->request->getVar('To');
				if ($start_date && $end_date)
					$href .= "&From=".$start_date."&To=".$end_date;
			}

			$do->Value = "<a href='".$href."'>".$label."</a>";
			if( !empty($keyUrlString) && $keyValue != $option) {
				$do->Value .= " (<a href='".$urlString.$keyUrlString.",,".$option."'>+</a>) (<a href='".$urlString.$keyUrlString."||".$option."'>|</a>)";
			}
			$do->Count = $count;
			$list->push($do);
		}

		return $list;
	}

	public static function generateFilterWhereClause() {
		$filterWhereClause = "";

		foreach($_GET as $key=>$value) {
			if (preg_match("/Filter$/",$key)) {
				$orValues = split("\|\|", $value);
				$andValues = split("\,\,", $value);

				if (count($orValues) > 1) {
					$filterWhereClause .= " and (";
					for($i=0; $i<count($orValues); $i++) {
						if( $i>0 ) {
							$filterWhereClause .= " OR ";
						}
						$filterWhereClause .= preg_replace("/Filter$/","",$key)." like '%".$orValues[$i]."%'";
					}
					$filterWhereClause .= ")";
				} else if (count($andValues) > 1) {
					$filterWhereClause .= " and (";
					for($i=0; $i<count($andValues); $i++) {
						if( $i>0 ) {
							$filterWhereClause .= " AND ";
						}
						$filterWhereClause .= preg_replace("/Filter$/","",$key)." like '%".$andValues[$i]."%'";
					}
					$filterWhereClause .= ")";
				} else {
					$filterWhereClause .= " and ".preg_replace("/Filter$/","",$key)." like '%".$value."%'";
				}
			}
		}


		return $filterWhereClause;
	}

    public static function generateDateFilters($table_prefix = '' ) {
		$where_query = '';
		$start_date = Controller::curr()->request->getVar('From');
		$end_date = Controller::curr()->request->getVar('To');
		if(!empty($table_prefix))
			$table_prefix .= '.';
		if(isset($start_date) && isset($end_date)){
			$date_from = Convert::raw2sql(trim($start_date));
			$date_to   = Convert::raw2sql(trim($end_date));
			$start = new \DateTime($date_from);
			$start->setTime(00, 00, 00);
			$end   = new \DateTime($date_to);
			$end->setTime(23, 59, 59);
			$where_query .= " ( {$table_prefix}UpdateDate >= '{$start->format('Y-m-d H:i:s')}' AND {$table_prefix}UpdateDate <= '{$end->format('Y-m-d H:i:s')}' ) ";
		} else {
			$start_date = self::$default_start_date;
			$end_date   = self::$default_end_date;
			$where_query .= " ( {$table_prefix}UpdateDate >= '{$start_date}' AND {$table_prefix}UpdateDate <= '{$end_date}' ) ";
		}

		self::$date_filter_query = $where_query;
	}
}