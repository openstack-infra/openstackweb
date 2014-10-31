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
 * Class TrainingDirectoryPage
 */
class TrainingDirectoryPage extends MarketPlaceDirectoryPage
{

    static $allowed_children = "none";

}

/**
 * Class TrainingDirectoryPage_Controller
 */
class TrainingDirectoryPage_Controller extends MarketPlaceDirectoryPage_Controller {

	private static $allowed_actions = array('handleIndex');

	static $url_handlers = array(
		'$Company!/$Slug' => 'handleIndex',
	);

	/**
	 * @var TrainingFacade $training_facade
	 */
	private $training_facade;

	/**
	 * @var IQueryHandler
	 */
	private $course_location_query;
	/**
	 * @var IQueryHandler
	 */
	private $course_level_query;

	function init(){
        parent::init();
        //JS
	    Requirements::css("themes/openstack/javascript/jquery-ui-1.10.3.custom/css/smoothness/jquery-ui-1.10.3.custom.min.css");
		Requirements::css("themes/openstack/css/chosen.css", "screen,projection");

	    Requirements::javascript("themes/openstack/javascript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js");
		Requirements::javascript("themes/openstack/javascript/chosen.jquery.min.js");
        Requirements::javascript("marketplace/code/ui/frontend/js/training.directory.page.js");
	    Requirements::customScript("jQuery(document).ready(function($) {
            $('#training','.marketplace-nav').addClass('current');
        });");

	    $this->training_facade = new TrainingFacade(
		    $this,
		    new TrainingManager(new SapphireTrainingServiceRepository,
			new SapphireMarketPlaceTypeRepository,
			new TrainingAddPolicy,
			new TrainingShowPolicy,
			new SessionCacheService,
			new MarketplaceFactory,
			SapphireTransactionManager::getInstance()),
		    new SapphireCourseRepository(new MarketplaceFactory)
	    );

		$this->course_location_query = new TrainingCoursesLocationQueryHandler;
		$this->course_level_query    = new TrainingCoursesLevelQueryHandler;

	    Requirements::customScript($this->GATrackingCode());
    }

	/**
	 * @return ArrayList|TrainingViewModel
	 */
	public function getTrainings(){
		return $this->training_facade->getTrainings();
	}

	public function handleIndex() {
		$params = $this->request->allParams();
		if(isset($params["Company"])){
			//render instance ...
			return $this->training();
		}
	}


	public function training()
    {
        try {
	        Requirements::css("marketplace/code/ui/frontend/css/training.detail.css");
	        $params              = $this->request->allParams();
	        $company_url_segment = Convert::raw2sql($params["Company"]);
	        $training_id         = Convert::raw2sql(@$params["Slug"]);
	        $training            = $this->training_facade->getCompanyTraining($training_id,$company_url_segment);
            return $this->Customise($training)->renderWith(array('TrainingDirectoryPage_training','TrainingDirectoryPage','MarketPlacePage'));;

        } catch (Exception $ex) {
            return $this->httpError(404, 'Sorry that Training could not be found!-');
        }
    }

    /*
     * top 20 "upcoming courses" for all companies
     */
    function getUpcomingCourses($limit = 20){
	     return $this->training_facade->getUpcomingCourses($limit);
    }

	public function LocationCombo(){
		$source = array();
		$result = $this->course_location_query->handle(new OpenStackImplementationNamesQuerySpecification(DateTimeUtils::getCurrentDate()));
		foreach($result->getResult() as $dto){
			$source[$dto->getValue()] =  $dto->getValue();
		}
		$ddl = new DropdownField('location-term',$title=null,$source);
		$ddl->setEmptyString('-- Show All --');
		return $ddl;
	}

	public function LevelCombo(){
		$source = array();
		$result = $this->course_level_query->handle(new OpenStackImplementationNamesQuerySpecification(''));
		foreach($result->getResult() as $dto){
			$source[$dto->getValue()] =  $dto->getValue();
		}
		$ddl = new DropdownField('level-term',$title=null,$source);
		$ddl->setEmptyString('-- Show All --');
		return $ddl;
	}
}