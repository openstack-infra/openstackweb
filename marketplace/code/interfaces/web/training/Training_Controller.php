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
 * Class Training_Controller
 */
class Training_Controller extends AbstractController {

	/**
	 * @var array
	 */
	static $url_handlers = array(
		'POST search'   => 'search',
		'GET topics'    => 'topics',
	);

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'search',
		'topics',
	);

	/**
	 * @var IQueryHandler
	 */
	private $course_topics_query;
	/**
	 * @var TrainingFacade $training_facade
	 */
	private $training_facade;

	function init()	{
		parent::init();
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
		$this->course_topics_query = new TrainingCoursesTopicQueryHandler;
	}

	/**
	 * @return string
	 */
	function search(){
		$output = '';
		if(!$this->isJson()){
			return $this->httpError(500,'Content Type not allowed');
		}

		try{
			$search_params = json_decode($this->request->getBody(),true);

			$trainings     = $this->training_facade->getTrainings(
				$search_params['topic_term'],
				$search_params['location_term'],
				$search_params['level_term']);

			foreach ($trainings as $training) {
				$output .= $training->renderWith('TrainingDirectoryPage_CompanyTrainingFilteredCourses', array(
					'FilteredCourses' => $training->getCourses(),
					'TrainingURL'     => $this->Link() . $training->getCompany()->URLSegment . "/" . $training->getId(),
					'DetailsURL'      => $this->Link()
				));
			}
		}
		catch(Exception $ex){
			return $this->httpError(500,'Server Error');
		}
		return empty($output) ? $this->httpError(404,'') : $output;
	}

	/**
	 * @param string $action
	 * @return string
	 */
	public function Link($action = null){
		$page       = TrainingDirectoryPage::get()->first();
		if(is_null($page)) return '';
		$controller = ModelAsController::controller_for($page);
		if(is_null($controller)) return '';
		return $controller->Link($action);
	}
	/**
	 * @param SS_HTTPRequest $request
	 * @return string
	 */
	function topics(SS_HTTPRequest $request){
		$params = $request->getVars();
		$result = $this->course_topics_query->handle(new OpenStackImplementationNamesQuerySpecification($params["term"]));
		$res    = array();
		foreach($result->getResult() as $dto){
			array_push($res,array('label' => $dto->getLabel(),'value' => $dto->getValue()));
		}
		return json_encode($res);
	}
}