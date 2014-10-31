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
 * Class MarketPlaceAdminMember
 */
class MarketPlaceAdminMember extends DataExtension
	implements ITrainingAdministrator, IMarketPlaceAdmin {

	/**
	 * @return int
	 */
	public function getIdentifier()
	{
		return (int)$this->owner->getField('ID');
	}

	/**
	 * @return ICompany[]
	 */
	public function getAdministeredTrainingCompanies()
	{
		$res = array();
		$managed_companies = $this->owner->ManagedCompanies();
		foreach($managed_companies as $company){
			$group_id = $company->GroupID;
			if(!$group_id) continue;
			$group = Group::get()->byID($group_id);
			if($group->Code === ITraining::MarketPlaceGroupSlug){
				array_push($res,$company);
			}
		}
		return $res;
	}


	function getAdminPermissionSet(array &$res){
		$marketplace_types = MarketPlaceType::get();
		foreach($marketplace_types as $mp){
			$group = $mp->AdminGroup();
			if(!$group) continue;
			foreach($group->Permissions() as $p)
				array_push($res,$p->Code);
		}
	}

	/**
	 * @return bool
	 */
	public function isTrainingAdmin()
	{
		$managed_companies = $this->owner->ManagedCompanies();
		foreach($managed_companies as $company){
			$groups = $company->getAdminGroupsByMember($this->getIdentifier());
			if(is_null($groups) || count($groups)==0) continue;
			if(array_key_exists(ITraining::MarketPlaceGroupSlug, $groups)){
				return true;
			}
		}
		return false;
	}

	/**
	 * Test if current user can administrate a particular program
	 * use should be training admin of program owner company
	 * @param $training_id
	 * @return bool
	 */
	public function canEditTraining($training_id){
		$managed_companies = $this->owner->ManagedCompanies();
		foreach($managed_companies as $company){
			$groups = $company->getAdminGroupsByMember($this->getIdentifier());
			if(is_null($groups) || count($groups)==0) continue;
			if(array_key_exists(ITraining::MarketPlaceGroupSlug, $groups)){
				$query = new QueryObject;
				$query->addAddCondition(QueryCriteria::equal('ClassName','TrainingService'));
				$query->addAddCondition(QueryCriteria::equal('ID',(int)$training_id));
				$training = $company->Services((string)$query);
				if($training && count($training)>0)
					return true;
			}
		}
		return false;
	}

	/**
	 * @param string $type
	 * @param int    $company_id
	 * @return bool
	 */
	public function isMarketPlaceAdminOfCompany($type,$company_id) {
		if($this->isMarketPlaceSuperAdmin()) return true;
		if($this->isMarketPlaceTypeSuperAdmin($type)) return true;
		$managed_companies = $this->owner->ManagedCompanies(" CompanyID =  {$company_id} ");
		if(count($managed_companies)==0) return false;
		$company = $managed_companies->First();
		$groups = $company->getAdminGroupsByMember($this->getIdentifier());
		if(is_null($groups) || count($groups)==0) return false;
		if(!array_key_exists($type, $groups))return false;
		return true;
	}

	/**
	 * @return bool
	 */
	public function isMarketPlaceAdmin(){
		$res = false;
		if($this->isMarketPlaceSuperAdmin()) return true;
		$res = $res || $this->isMarketPlaceTypeAdmin(IDistribution::MarketPlaceGroupSlug);
		$res = $res || $this->isMarketPlaceTypeAdmin(IAppliance::MarketPlaceGroupSlug);
		$res = $res || $this->isMarketPlaceTypeAdmin(IPublicCloudService::MarketPlaceGroupSlug);
		$res = $res || $this->isMarketPlaceTypeAdmin(IConsultant::MarketPlaceGroupSlug);
		$res = $res || $this->isMarketPlaceTypeAdmin(IPrivateCloudService::MarketPlaceGroupSlug);
		return $res;
	}

	public function isMarketPlaceSuperAdmin() {
		return Permission::check("MARKETPLACE_ADMIN_ACCESS");
	}

	public function isMarketPlaceTypeSuperAdmin($type) {
		$permission_code = '';
		switch($type){
			case IDistribution::MarketPlaceGroupSlug:
			case IDistribution::MarketPlaceType:
			case IOpenStackImplementation::AbstractMarketPlaceType:{
				$permission_code = IDistribution::MarketPlacePermissionSlug;
			}
			break;
			case IAppliance::MarketPlaceGroupSlug:
			case IAppliance::MarketPlaceType:
			case IOpenStackImplementation::AbstractMarketPlaceType:
			{
				$permission_code = IAppliance::MarketPlacePermissionSlug;
			}
			break;
			case IPublicCloudService::MarketPlaceGroupSlug:
			case IPublicCloudService::MarketPlaceType:
				$permission_code = IPublicCloudService::MarketPlacePermissionSlug;
				break;
			case IPrivateCloudService::MarketPlaceGroupSlug:
			case IPrivateCloudService::MarketPlaceType:
				$permission_code = IPrivateCloudService::MarketPlacePermissionSlug;
				break;
			case IConsultant::MarketPlaceGroupSlug:
			case IConsultant::MarketPlaceType:
				$permission_code = IConsultant::MarketPlacePermissionSlug;
				break;
		}
		return Permission::check($permission_code);
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public function getManagedMarketPlaceCompaniesByType($type){
		$managed_companies = $this->owner->ManagedCompanies();
		$res = array();
		foreach($managed_companies as $company){
			$groups = $company->getAdminGroupsByMember($this->getIdentifier());
			if(is_null($groups) || count($groups)==0) continue;
			if(array_key_exists($type, $groups) && !array_key_exists($company->ID,$res)){
				$res[$company->ID]= $company;
			}
		}
		return $res;
	}



	/**
	 * @param string $type
	 * @return bool
	 */
	public function isMarketPlaceTypeAdmin($type){
		if($this->isMarketPlaceSuperAdmin()) return true;
		if($this->isMarketPlaceTypeSuperAdmin($type)) return true;
		$managed_companies = $this->owner->ManagedCompanies();
		foreach($managed_companies as $company){
			$groups = $company->getAdminGroupsByMember($this->getIdentifier());
			if(is_null($groups) || count($groups)==0) continue;
			if( array_key_exists($type, $groups)){
				return true;
			}
		}
		return false;
	}
}