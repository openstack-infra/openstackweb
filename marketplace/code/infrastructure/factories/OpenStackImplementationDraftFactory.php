<?php

abstract class OpenStackImplementationDraftFactory
	extends RegionalSupportedCompanyServiceDraftFactory
	implements IOpenStackImplementationFactory {
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