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
 * Class SangriaPageEventExtension
 */
final class SangriaPageEventExtension extends Extension {

	private $repository;

	public function __construct(){
		parent::__construct();
		$this->repository = new SapphireEventRegistrationRequestRepository;
	}

	public function onBeforeInit(){
		Config::inst()->update(get_class($this), 'allowed_actions', array('ViewEventDetails'));
		Config::inst()->update(get_class($this->owner), 'allowed_actions', array('ViewEventDetails'));
	}

	public function EventRegistrationRequestForm() {
		$this->commonScripts();
		Requirements::css('events/css/event.registration.form.css');
		Requirements::javascript("events/js/event.registration.form.js");
		$data = Session::get("FormInfo.Form_EventRegistrationRequestForm.data");
		$form = new EventRegistrationRequestForm($this->owner, 'EventRegistrationRequestForm',false);
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

	public function onAfterInit(){

	}

	private function commonScripts(){
		Requirements::css("themes/openstack/css/chosen.css", "screen,projection");
		Requirements::css(THIRDPARTY_DIR . '/jquery-ui-themes/smoothness/jquery-ui.css');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-ui/jquery-ui.js');
		Requirements::css("events/css/sangria.page.view.event.details.css");
		Requirements::javascript("themes/openstack/javascript/chosen.jquery.min.js");
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::javascript("themes/openstack/javascript/jquery.ui.datepicker.validation.package-1.0.1/jquery.ui.datepicker.validation.js");
		Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");
		Requirements::javascript('marketplace/code/ui/admin/js/utils.js');
		Requirements::javascript('themes/openstack/javascript/jquery.cleanform.js');
	}

	public function ViewEventDetails(){
		$this->commonScripts();
		Requirements::javascript('events/js/admin/sangria.page.event.extension.js');
		return $this->owner->getViewer('ViewEventDetails')->process($this->owner);
	}

	public function getQuickActionsExtensions(&$html){
		$view = new SSViewer('SangriaPage_EventLinks');
		$html .= $view->process($this->owner);
	}

	public function getEventRegistrationRequest(){
		list($list,$size) = $this->repository->getAllNotPostedAndNotRejected(0,1000);
		return new ArrayList($list);
	}
} 