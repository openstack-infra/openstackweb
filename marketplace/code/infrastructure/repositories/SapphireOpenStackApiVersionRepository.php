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
class SapphireOpenStackApiVersionRepository
extends SapphireRepository
implements  IOpenStackApiVersionRepository
{

	public function __construct(){
		parent::__construct(new OpenStackApiVersion);
	}

	/**
	 * @param int $version
	 * @param int $component_id
	 * @return IOpenStackApiVersion
	 */
	public function getByVersionAndComponent($version, $component_id)
	{
		return OpenStackApiVersion::get()->filter()->first(array('Version'=>$version,'OpenStackComponentID'=>$component_id));
	}

	/**
	 * @param int $id
	 * @param int $component_id
	 * @return IOpenStackApiVersion
	 */
	public function getByIdAndComponent($id, $component_id)
	{
		return OpenStackApiVersion::get()->filter(array('ID'=>$id,'OpenStackComponentID'=>$component_id ))->first();
	}

	/**
	 * @param $release_id
	 * @param $component_id
	 * @return IOpenStackApiVersion[]
	 */
	public function getByReleaseAndComponent($release_id, $component_id)
	{

		$ds = OpenStackReleaseSupportedApiVersion::get()->filter( array('OpenStackComponentID'=>$component_id ,'ReleaseID'=>$release_id));
		$list = array();
		if($ds){
			foreach($ds as $item){
				array_push($list, $item->ApiVersion());
			}
		}
		return $list;
	}
}