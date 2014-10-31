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
 * Defines the ArticleHolder page type
 */
class ArticleHolder extends Page
{
	static $db = array();
	static $has_one = array();

	static $allowed_children = array('ArticlePage');
	static $icon = "themes/tutorial/images/treeicons/news";
}

class ArticleHolder_Controller extends Page_Controller
{
	function rss()
	{
		$rss = new RSSFeed($this->Children(), $this->Link(), "The coolest news around");
		$rss->outputToBrowser();
	}

	function init()
	{
		RSSFeed::linkToFeed($this->Link() . "rss");
		parent::init();
	}
}