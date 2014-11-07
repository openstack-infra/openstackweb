<?php

abstract class RegionalSupportedCompanyServiceDraftFactory implements IRegionalSupportedCompanyServiceFactory {
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

} 