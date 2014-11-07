<?php

/**
 * Class PrivateCloudDraftFactory
 */
final class PrivateCloudDraftFactory extends CloudDraftFactory {
	/**
	 * @param string           $name
	 * @param string           $overview
	 * @param ICompany         $company
	 * @param bool             $active
	 * @param IMarketPlaceType $marketplace_type
	 * @param null|string      $call_2_action_url
	 * @return ICompanyService
	 */
	public function buildCompanyService($name, $overview, ICompany $company, $active, IMarketPlaceType $marketplace_type, $call_2_action_url = null,  $live_id = null)
	{
		$private_cloud = new PrivateCloudServiceDraft;
		$private_cloud->setName($name);
		$private_cloud->setOverview($overview);
		$private_cloud->setCompany($company);
		if($active)
			$private_cloud->activate();
		else
			$private_cloud->deactivate();
		$private_cloud->setMarketplace($marketplace_type);
		$private_cloud->setCall2ActionUri($call_2_action_url);
        $private_cloud->setLiveServiceId($live_id);
		return $private_cloud;
	}

	/**
	 * @param $id
	 * @return ICompanyService
	 */
	public function buildCompanyServiceById($id)
	{
		$private_cloud     = new PrivateCloudServiceDraft;
		$private_cloud->ID = $id;
		return $private_cloud;
	}
}