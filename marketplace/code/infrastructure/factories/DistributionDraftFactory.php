<?php

/**
 * Class DistributionDraftFactory
 */
final class DistributionDraftFactory extends OpenStackImplementationDraftFactory {

	/**
	 * @param string           $name
	 * @param string           $overview
	 * @param ICompany         $company
	 * @param bool             $active
	 * @param IMarketPlaceType $marketplace_type
	 * @param null|string      $call_2_action_url
	 * @return ICompanyService
	 */
	public function buildCompanyService($name, $overview, ICompany $company, $active, IMarketPlaceType $marketplace_type, $call_2_action_url = null, $live_id = null)
	{
		$distribution = new DistributionDraft;
		$distribution->setName($name);
		$distribution->setOverview($overview);
		$distribution->setCompany($company);
		if($active)
			$distribution->activate();
		else
			$distribution->deactivate();
		$distribution->setMarketplace($marketplace_type);
		$distribution->setCall2ActionUri($call_2_action_url);
        $distribution->setLiveServiceId($live_id);
		return $distribution;
	}

    /**
     * @param $id
     * @return ICompanyService
     */
    public function buildCompanyServiceById($id)
    {
        $distribution     = new DistributionDraft;
        $distribution->ID = $id;
        return $distribution;
    }

}