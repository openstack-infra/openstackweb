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
 * Class SangriaPageJobsExtension
 */
final class SangriaPageJobsExtension extends Extension {

	private $repository;

	public function __construct(){
		$this->repository = new SapphireJobRegistrationRequestRepository;
		parent::__construct();
	}

	public function onBeforeInit(){
		Config::inst()->update(get_class($this), 'allowed_actions', array('ViewJobsDetails'));
	}

	public function onAfterInit(){

	}

	private function commonScripts(){
		Requirements::css("themes/openstack/css/chosen.css", "screen,projection");
		Requirements::css("themes/openstack/javascript/jquery-ui-1.10.3.custom/css/smoothness/jquery-ui-1.10.3.custom.min.css");
		Requirements::javascript("themes/openstack/javascript/chosen.jquery.min.js");
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::javascript("themes/openstack/javascript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js");
		Requirements::javascript("themes/openstack/javascript/jquery.ui.datepicker.validation.package-1.0.1/jquery.ui.datepicker.validation.js");
		Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");
		Requirements::javascript('marketplace/code/ui/admin/js/utils.js');
		Requirements::javascript('themes/openstack/javascript/jquery.cleanform.js');
		Requirements::javascript('themes/openstack/javascript/pure.min.js');
	}

	public function ViewJobsDetails(){
		$this->commonScripts();
		Requirements::css("jobs/css/sangria.page.view.job.details.css");
		Requirements::javascript('jobs/js/admin/sangria.page.job.extension.js');
		return $this->owner->getViewer('ViewJobsDetails')->process($this->owner);
	}

	public function getJobRegistrationRequests(){
		list($list,$size) = $this->repository->getAllNotPostedAndNotRejected(0,1000);
		return new ArrayList($list);
	}

	public function getQuickActionsExtensions(&$html){
		$view = new SSViewer('SangriaPage_JobsLinks');
		$html .= $view->process($this->owner);
	}

	function JobRegistrationRequestForm(){
		$this->commonScripts();
		Requirements::javascript(Director::protocol()."maps.googleapis.com/maps/api/js?sensor=false");
		Requirements::javascript("marketplace/code/ui/admin/js/geocoding.jquery.js");
		Requirements::css('jobs/css/job.registration.form.css');
		Requirements::javascript("jobs/js/job.registration.form.js");
		$data = Session::get("FormInfo.Form_JobRegistrationRequestForm.data");
		$form = new JobRegistrationRequestForm($this->owner, 'JobRegistrationRequestForm',false);
		// we should also load the data stored in the session. if failed
		if(is_array($data)) {
			$form->loadDataFrom($data);
		}
		// Optional spam protection
		if(class_exists('SpamProtectorManager')) {
			SpamProtectorManager::update_form($form);
		}
		return $form;
	}
}