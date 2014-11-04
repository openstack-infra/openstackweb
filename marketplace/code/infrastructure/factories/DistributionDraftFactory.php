<?php

/**
 * Class DistributionDraftFactory
 */
final class DistributionDraftFactory extends OpenStackImplementationFactory {

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

    /**
     * @param IRegion                  $region
     * @param IRegionalSupportedCompanyService $service
     * @return IRegionalSupport
     */
    public function buildRegionalSupport(IRegion $region, IRegionalSupportedCompanyService $service){
        $regional_support = new RegionalSupportDraft;
        $regional_support->setRegion($region);
        $regional_support->setCompanyService($service);
        return $regional_support;
    }

    /**
     * @param int                      $coverage_percent
     * @param IReleaseSupportedApiVersion $release_supported_api_version
     * @param IOpenStackImplementation $implementation
     * @return IOpenStackImplementationApiCoverage
     */
    public function buildCapability($coverage_percent, IReleaseSupportedApiVersion $release_supported_api_version, IOpenStackImplementation $implementation)
    {
        $capability = new OpenStackImplementationApiCoverageDraft;
        $capability->setCoveragePercent($coverage_percent);
        $capability->setReleaseSupportedApiVersion($release_supported_api_version);
        $capability->setImplementation($implementation);
        return $capability;
    }
}