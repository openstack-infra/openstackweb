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
 * Class SangriaPageSurveyDetailsExtension
 */

final class SangriaPageSurveyDetailsExtension  extends Extension {

	public function onBeforeInit(){

		Config::inst()->update(get_class($this), 'allowed_actions', array(
			'SetCaseStudy',
			'SurveyDetails',
		));

		Config::inst()->update(get_class($this->owner), 'allowed_actions', array(
			'SetCaseStudy',
			'SurveyDetails',
		));
	}

	function SetCaseStudy() {
		if(isset($_GET['ID']) && is_numeric($_GET['ID'])) {
			$UserStory = $_GET['ID'];
		}else{
			die();
		}

		$setCaseStudy = ($_GET['Set'] == 1)? 1 : 0;
		$story = SiteTree::get_by_id("UserStory",$UserStory);

		$story->ShowCaseStudy = $setCaseStudy;
		$story->write();
		$story->publish("Live","Stage");

		$this->owner->setMessage('Success', 'Case Study updated for <b>' . $story->Title . '</b>');

		Controller::curr()->redirectBack();
	}

	function SurveyDetails(){
		$params     = $this->owner->request->allParams();
		$survey_id  = intval(Convert::raw2sql($params["ID"]));;
		$survey = DeploymentSurvey::get()->byID($survey_id);
		if($survey)
			return $this->owner->Customise($survey)->renderWith(array('SangriaPage_SurveyDetails','SangriaPage','SangriaPage'));
		return $this->owner->httpError(404, 'Sorry that Deployment Survey could not be found!.');
	}

} 