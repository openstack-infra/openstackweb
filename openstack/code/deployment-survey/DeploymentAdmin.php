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
 * Class DeploymentAdmin
 */
final class DeploymentAdmin extends ModelAdmin {
    
    public static $managed_models = array(
        'Deployment',
        'DeploymentSurvey'
    );

	public $showImportForm = false;
    static $url_segment    = 'deployments';
    static $menu_title     = 'Deployments';

	/**
	 * @param string $collection_controller_class Override for controller class
	 */
	//public static $collection_controller_class = "DeploymentAdmin_CollectionController";
}

/**
 * Class DeploymentAdmin_CollectionController
 */
/*final class DeploymentAdmin_CollectionController extends ModelAdmin_CollectionController{
	public function CreateForm() {
		if($this->modelClass==='DeploymentSurvey')
			return false;
		return parent::CreateForm();
	}
}*/