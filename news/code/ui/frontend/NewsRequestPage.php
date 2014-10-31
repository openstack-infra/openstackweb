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
 * Class NewsRequestPage_Controller
 */
final class NewsRequestPage_Controller extends Page_Controller {

    //Allow our form as an action
    static $allowed_actions = array(
        'NewsRequestForm',
        'saveNewsArticle',
    );

    /**
     * @var NewsRequestManager
     */
    private $manager;

	public function __construct(){
		parent::__construct();
		$this->news_repository = new SapphireNewsRepository();
        $this->manager = new NewsRequestManager(
            new SapphireNewsRepository,
            new SapphireSubmitterRepository,
            new NewsFactory,
            new NewsValidationFactory,
	        new SapphireFileUploadService(),
            SapphireTransactionManager::getInstance()
        );
	}

	public function logout(){
		$current_member = Member::currentUser();
		if($current_member){
			$current_member->logOut();
			return Controller::curr()->redirect("Security/login?BackURL=" . urlencode($_SERVER['HTTP_REFERER']));
		}
		return Controller::curr()->redirectBack();
	}

    public function index(){
        Requirements::javascript("news/code/ui/frontend/js/news.request.page.js");
        return $this->renderWith(array('NewsRequestPage','Page'));
    }

    public function NewsRequestForm() {
        $this->commonScripts();
        Requirements::css('news/code/ui/frontend/css/news.form.css');
        Requirements::javascript("news/code/ui/frontend/js/news.form.js");
        $data = Session::get("FormInfo.Form_NewsRequestForm.data");
        $article = null;
        $is_news_manager = (Member::currentUser() && Member::currentUser()->isNewsManager());

        if (isset($this->requestParams['articleID']) && $is_news_manager) {
            $article_id = $this->requestParams['articleID'];
            $article = $this->news_repository->getNewsByID($article_id);
        }

        $form = new NewsRequestForm($this, 'NewsRequestForm',$article, $is_news_manager, false);
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

    private function commonScripts(){
        Requirements::css("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.css");
        Requirements::javascript("themes/openstack/javascript/datetimepicker/jquery.datetimepicker.js");

        Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
        /*Requirements::javascript("themes/openstack/javascript/jquery.validate.custom.methods.js");
        Requirements::javascript(Director::protocol()."ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js");
        Requirements::javascript("themes/openstack/javascript/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js");
        Requirements::javascript("themes/openstack/javascript/jquery.ui.datepicker.validation.package-1.0.1/jquery.ui.datepicker.validation.js");*/

    }

    function saveNewsArticle($data, Form $form){

        // Check to make sure image uploaded is not too big
        if ($data["Image"]["size"] > 1000000) {
            $form->addErrorMessage("Image", 'The image you have attached is too big. It must be less than 1MB in size.', "bad");
            Session::set("FormInfo.Form_NewsRequestForm.data", $data);
            return Director::redirect('/news-add/?error=1');
        }
        if ($data["Document"]["size"] > 1000000) {
            $form->addErrorMessage("Image", 'The image you have attached is too big. It must be less than 1MB in size.', "bad");
            Session::set("FormInfo.Form_NewsRequestForm.data", $data);
            return Director::redirect('/news-add/?error=1');
        }

        try{
            if ($data['newsID']) {
                $this->manager->updateNews($data);
            } else {
                $this->manager->postNews($data);
            }

            Session::clear("FormInfo.Form_NewsRequestForm.data");
            return Director::redirect('/news-add/?saved=1');
        }
        catch(EntityValidationException $ex1){
            $messages = $ex1->getMessages();
            $msg = $messages[0];
            $form->addErrorMessage('Headline',$msg['message'] ,'bad');
            SS_Log::log($msg['message'] ,SS_Log::ERR);
            // Load errors into session and post back
            Session::set("FormInfo.Form_NewsRequestForm.data", $data);
            return $this->redirectBack();
        }
        catch(Exception $ex){
            $form->addErrorMessage('Headline','Server Error','bad');
            SS_Log::log($ex->getMessage(), SS_Log::ERR);
            // Load errors into session and post back
            Session::set("FormInfo.Form_NewsRequestForm.data", $data);
            return $this->redirectBack();
        }
    }

    //Check for just saved
    function Saved(){
        return $this->request->getVar('saved');
    }

    function Error(){
        return $this->request->getVar('error');
    }
} 