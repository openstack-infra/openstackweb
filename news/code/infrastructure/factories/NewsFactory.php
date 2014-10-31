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
 * Class NewsFactory
 */
final class NewsFactory
	implements INewsFactory {

	/**
	 * @param NewsMainInfo       $info
	 * @param string[]           $tags
	 * @param                    $submitter
	 * @param IFileUploadService $upload_service
	 * @return INews|News
	 */
	public function buildNews(NewsMainInfo $info, $tags, $submitter,  IFileUploadService $upload_service) {
		$news = new News();
        $news->registerMainInfo($info);
		$news->registerTags($tags);
        if (get_class($submitter) == 'NewsSubmitter') {
            $news->registerSubmitter($submitter);
        } else {
            $news->setSubmitter($submitter);
        }
		//create image object
        $image_info = $info->getImage();
		if($image_info['size']){
			$news->registerImage($upload_service);
		}
        //create image object
        $document_info = $info->getDocument();
        if($document_info['size']){
            $news->registerDocument($upload_service);
        }

		return $news;
	}

	/**
	 * @param array $data
	 * @return NewsMainInfo
	 */
	public function buildNewsMainInfo(array $data)
	{
        $date_embargo = isset($data['date_embargo']) ? $data['date_embargo'] : null;
        $date_expire = isset($data['date_expire']) ? $data['date_expire'] : null;

        $main_info = new NewsMainInfo(trim($data['headline']),trim($data['summary']),$data['city'],$data['state'], $data['country'],
                                      $data['date'],trim($data['body']),$data['link'],$data['Image'],$data['Document'],
                                      $date_embargo,$date_expire);
		return $main_info;
	}

    /**
     * @param array $data
     * @return NewsSubmitter
     */
    public function buildNewsSubmitter(array $data)
    {
        $submitter = new NewsSubmitter(trim($data['submitter_first_name']),trim($data['submitter_last_name']), trim($data['submitter_email']),
                                       trim($data['submitter_company']),$data['submitter_phone']);

        return $submitter;
    }

    public function setNewsID(INews $news, array $data) {
        $news->ID = $data['newsID'];
    }

}