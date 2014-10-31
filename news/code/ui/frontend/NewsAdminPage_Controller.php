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
 * Class NewsPage_Controller
 */
final class NewsAdminPage_Controller extends Page_Controller {

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'logout',
        'setArticleRank',
        'deleteArticle',
        'removeArticle'
	);

    /**
     * @var ISapphireNewsRepository
     */
    private $news_repository;

    /**
     * @var NewsRequestManager
     */
    private $news_manager;


    function init() {
        parent::init();

        Requirements::css(Director::protocol()."code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css");
        Requirements::css('news/code/ui/frontend/css/news.admin.css');

        Requirements::javascript(Director::protocol()."code.jquery.com/ui/1.10.4/jquery-ui.min.js");
        Requirements::javascript('news/code/ui/frontend/js/news.admin.js');
    }

	public function __construct(){
		parent::__construct();
		$this->news_repository = new SapphireNewsRepository();
        $this->news_manager = new NewsRequestManager(
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

        $recent_news = $this->news_repository->getRecentNews();
        $standby_news = $this->news_repository->getStandByNews();

        return $this->renderWith(array('NewsAdminPage','Page'), array('RecentNews' => new ComponentSet($recent_news),
                                                                      'StandByNews' => new ComponentSet($standby_news)));
    }

    public function getSliderNews() {
        $output = '';
        $counter = 0;
        $slide_news = $this->news_repository->getSlideNews();

        foreach ($slide_news as $slide_article) {
            $counter++;
            $data = array('Id'=>$slide_article->Id,'Rank'=>$slide_article->Rank,'Link'=>$slide_article->Link,
                          'Image'=>$slide_article->Image,'Headline'=>$slide_article->Headline,'Summary'=>$slide_article->Summary);
            $output .= $slide_article->renderWith('NewsAdminPage_slider', $data);
        }

        for ($i=0;$i<(5-$counter);$i++) {
            $output .= '<li class="placeholder_empty">Drop<br> here</li>';
        }

        return $output;
    }

    public function getFeaturedNews() {
        $output = '';
        $counter = 0;
        $featured_news = $this->news_repository->getFeaturedNews();

        foreach ($featured_news as $featured_article) {
            $counter++;
            $data = array('Id'=>$featured_article->Id,'Rank'=>$featured_article->Rank,'Link'=>$featured_article->Link,
                          'Image'=>$featured_article->Image,'Headline'=>$featured_article->Headline,'Summary'=>$featured_article->Summary);
            $output .= $featured_article->renderWith('NewsAdminPage_featured', $data);
        }

        for ($i=0;$i<(6-$counter);$i++) {
            $output .= '<li class="placeholder_empty">Drop<br> here</li>';
        }

        return $output;
    }

    public function setArticleRank() {
        $article_id = intval($this->request->postVar('id'));
        $old_rank = intval($this->request->postVar('old_rank'));
        $new_rank = intval($this->request->postVar('new_rank'));
        $type = $this->request->postVar('type');
        $target = $this->request->postVar('target');
        $is_new = $this->request->postVar('is_new');

        if ($is_new == 1) {
            // new item coming in, add and reorder
            $this->news_manager->moveNewsArticle($article_id,$new_rank,$target);
            $this->news_manager->sortNewsArticles($article_id,$new_rank,$old_rank,true,false,$target);
        } elseif ($type == $target) {
            //sorting within section, reorder
            $this->news_manager->sortNewsArticles($article_id,$new_rank,$old_rank,false,false,$type);
            $this->news_manager->moveNewsArticle($article_id,$new_rank,$target);
        } else {
            //item removed, reorder
            $this->news_manager->sortNewsArticles($article_id,$new_rank,$old_rank,false,true,$type);
        }
    }

    public function deleteArticle() {
        $article_id = intval($this->request->postVar('id'));

        $this->news_repository->deleteArticle($article_id);
    }

    public function removeArticle() {
        $article_id = intval($this->request->postVar('id'));
        $type = $this->request->postVar('type');
        $old_rank = intval($this->request->postVar('old_rank'));

        $this->news_manager->moveNewsArticle($article_id,0,'standby');
        $this->news_manager->sortNewsArticles($article_id,0,$old_rank,false,true,$type);
    }

} 