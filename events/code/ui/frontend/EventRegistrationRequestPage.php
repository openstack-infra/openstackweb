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
 * Class EventRegistrationRequestPage
 */
final class EventRegistrationRequestPage extends Page {

}

/**
 * Class EventRegistrationRequestPage_Controller
 */
final class EventRegistrationRequestPage_Controller extends Page_Controller {

	/**
	 * @var EventRegistrationRequestManager
	 */
	private $event_registration_request_manager;
	/**
	 * @var IEventRegistrationRequestFactory
	 */
	private $factory;

	//Allow our form as an action
	static $allowed_actions = array(
		'EventRegistrationRequestForm',
		'saveEventRegistrationRequest',
	);

	function init()	{
		parent::init();
		$google_geo_coding_api_key     = null;
		$google_geo_coding_client_id   = null;
		$google_geo_coding_private_key = null;
		if(defined('GOOGLE_GEO_CODING_API_KEY')){
			$google_geo_coding_api_key = GOOGLE_GEO_CODING_API_KEY;
		}
		else if (defined('GOOGLE_GEO_CODING_CLIENT_ID') && defined('GOOGLE_GEO_CODING_PRIVATE_KEY')){
			$google_geo_coding_client_id   = GOOGLE_GEO_CODING_CLIENT_ID;
			$google_geo_coding_private_key = GOOGLE_GEO_CODING_PRIVATE_KEY;
		}
		//managers
		$this->factory = new EventRegistrationRequestFactory;
		$this->event_registration_request_manager = new EventRegistrationRequestManager(
			new SapphireEventRegistrationRequestRepository,
			new SapphireEventRepository,
			$this->factory,
			new GoogleGeoCodingService(
				new SapphireGeoCodingQueryRepository,
				new UtilFactory,
				SapphireTransactionManager::getInstance(),
				$google_geo_coding_api_key,
				$google_geo_coding_client_id,
				$google_geo_coding_private_key),
			new SapphireEventPublishingService,
			new EventValidatorFactory,
			SapphireTransactionManager::getInstance()
		);
		//js files
		Requirements::css("themes/openstack/css/chosen.css", "screen,projection");
		Requirements::css(THIRDPARTY_DIR . '/jquery-ui-themes/smoothness/jquery-ui.css');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-ui/jquery-ui.js');
		Requirements::javascript("themes/openstack/javascript/chosen.jquery.min.js");
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
		Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
		Requirements::javascript("themes/openstack/javascript/jquery.ui.datepicker.validation.package-1.0.1/jquery.ui.datepicker.validation.js");
		Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");
		Requirements::javascript('events/js/event.registration.request.page.js');
	}

	public function EventRegistrationRequestForm() {
		$data = Session::get("FormInfo.Form_EventRegistrationRequestForm.data");
		Requirements::css('events/css/event.registration.form.css');
		Requirements::javascript("events/js/event.registration.form.js");
		$form = new EventRegistrationRequestForm($this, 'EventRegistrationRequestForm');
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


	//Save event registration form
	function saveEventRegistrationRequest($data, Form $form){
		// Check if the honeypot has been filled out
		if(@$data['username']) {
			SS_Log::log(sprintf('EventRegistrationRequestForm honeypot triggered (data: %s)',http_build_query($data)), SS_Log::NOTICE);
			return $this->httpError(403);
		}
		try{
			$this->event_registration_request_manager->registerEventRegistrationRequest($data);
			Session::clear("FormInfo.Form_EventRegistrationRequestForm.data");
			return $this->redirect($this->Link('?saved=1'));
		}
		catch(EntityValidationException $ex1){
			$messages = $ex1->getMessages();
			$msg = $messages[0];
			$form->addErrorMessage('City',$msg['message'] ,'bad');
			SS_Log::log($msg['message'] ,SS_Log::ERR);
			// Load errors into session and post back
			Session::set("FormInfo.Form_EventRegistrationRequestForm.data", $data);
			return $this->redirectBack();
		}
		catch(Exception $ex){
			$form->addErrorMessage('Title','Server Error','bad');
			SS_Log::log($ex->getMessage(), SS_Log::ERR);
			// Load errors into session and post back
			Session::set("FormInfo.Form_EventRegistrationRequestForm.data", $data);
			return $this->redirectBack();
		}
	}

	//Check for just saved
	function Saved()
	{
		return $this->request->getVar('saved');
	}
}