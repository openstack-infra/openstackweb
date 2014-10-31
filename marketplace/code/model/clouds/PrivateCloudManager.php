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
/**
 * Class PrivateCloudManager
 */
final class PrivateCloudManager extends CloudManager {

	/**
	 * @return IMarketPlaceType
	 * @throws NotFoundEntityException
	 */
	protected function getMarketPlaceType(){
		$marketplace_type =  $this->marketplace_type_repository->getByType(IPrivateCloudService::MarketPlaceType);
		if(!$marketplace_type)
			throw new NotFoundEntityException('MarketPlaceType',sprintf("type %s ",IPrivateCloudService::MarketPlaceType));
		return $marketplace_type;
	}
}