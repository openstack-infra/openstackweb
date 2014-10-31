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
 * Class SapphireOpenStackReleaseSupportedApiVersionRepository
 */
final class SapphireOpenStackReleaseSupportedApiVersionRepository
	extends SapphireRepository
	implements IOpenStackReleaseSupportedApiVersionRepository
{

	public function __construct(){
		parent::__construct(new OpenStackReleaseSupportedApiVersion);
	}

	/**
	 * @param int $release_id
	 * @param int $component_id
	 * @param int $api_version_id
	 * @return IReleaseSupportedApiVersion
	 */
	public function getByReleaseAndComponentAndApiVersion($release_id, $component_id, $api_version_id)
	{
		$class = $this->entity_class;
		return $class::get()->filter(array('ReleaseID'=>$release_id,'OpenStackComponentID'=>$component_id,'ApiVersionID'=>$api_version_id))->first();
	}
}