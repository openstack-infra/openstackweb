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
require_once(Director::baseFolder().'/marketplace/code/utils/helpers.php');

//extensions
Object::add_extension('Group', 'SecurityGroupDecorator');
Object::add_extension('Company', 'MarketPlaceCompany');
Object::add_extension('Member', 'MarketPlaceAdminMember');
Object::add_extension('Project', 'TrainingCourseRelatedProject');

//Admin UI
Object::add_extension('MarketPlaceType', 'MarketPlaceTypeAdminUI');
Object::add_extension('TrainingService', 'TrainingServiceAdminUI');
Object::add_extension('TrainingCourse', 'TrainingCourseAdminUI');
Object::add_extension('TrainingCourseSchedule', 'TrainingCourseScheduleAdminUI');
Object::add_extension('GuestOSType', 'GuestOSTypeAdminUI');
Object::add_extension('HyperVisorType', 'HyperVisorTypeAdminUI');
Object::add_extension('PricingSchemaType', 'PricingSchemaTypeAdminUI');
Object::add_extension('SpokenLanguage', 'SpokenLanguageAdminUI');
Object::add_extension('Region', 'RegionAdminUI');
Object::add_extension('ConfigurationManagementType', 'ConfigurationManagementTypeAdminUI');
Object::add_extension('SupportChannelType', 'SupportChannelTypeAdminUI');
Object::add_extension('MarketPlaceVideoType', 'MarketPlaceVideoTypeAdminUI');
Object::add_extension('OpenStackComponent', 'OpenStackComponentAdminUI');
Object::add_extension('OpenStackApiVersion', 'OpenStackApiVersionAdminUI');
Object::add_extension('OpenStackRelease', 'OpenStackReleaseAdminUI');
Object::add_extension('OpenStackReleaseSupportedApiVersion', 'OpenStackReleaseSupportedApiVersionAdminUI');
Object::add_extension('MarketPlaceAllowedInstance', 'MarketPlaceAllowedInstanceAdminUI');
