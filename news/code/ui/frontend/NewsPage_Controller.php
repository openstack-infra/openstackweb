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
 * Class NewsPage_Controller
 */
final class NewsPage_Controller extends Page_Controller {

	/**
	 * @var array
	 */
	static $allowed_actions = array(
		'logout',
        'ViewArticle'
	);

    function init() {
        parent::init();
	    Requirements::css('news/code/ui/frontend/css/news.css');
        Requirements::css(Director::protocol().'://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
    }

	public function BootstrapConverted(){
		return true;
	}

	public function __construct(){
		parent::__construct();
		$this->news_repository = new SapphireNewsRepository();
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

        $featured_news = $this->news_repository->getFeaturedNews();
        $recent_news = $this->news_repository->getRecentNews();
        $slide_news = $this->news_repository->getSlideNews();

        return $this->renderWith(array('NewsPage','Page'), array('FeaturedNews' => new ArrayList($featured_news),
                                                                 'RecentNews' => new ArrayList($recent_news),
                                                                 'SlideNews' => new ArrayList($slide_news)));
    }

    function FutureEvents($num = 4) {
        return DataObject::get('EventPage', "EventEndDate >= now()", "EventStartDate ASC", "", $num);
    }

    function RssItems($limit = 10) {

        $feed = new RestfulService('http://pipes.yahoo.com/pipes/pipe.run?_id=7479b77882a68cdf5a7143374b51cf30&_render=rss',7200);
        $feedXML = $feed->request()->getBody();

        // Extract items from feed
        $result = $feed->getValues($feedXML, 'channel', 'item');

        foreach ($result as $item ) {
            $item->pubDate = date("D, M jS Y", strtotime($item->pubDate));
        }

        // Return items up to limit
        return $result->getRange(0,$limit);
    }

    function ViewArticle() {
        if (isset($this->requestParams['articleID'])) {
            $article_id = $this->requestParams['articleID'];
            $article = $this->news_repository->getNewsByID($article_id);
        }
        return $this->renderWith(array('NewsArticlePage','Page'), $article );
    }

} 