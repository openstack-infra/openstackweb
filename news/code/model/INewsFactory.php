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
interface INewsFactory {
	/**
	 * @param NewsMainInfo       $info
	 * @param string[]           $tags
	 * @param                    $submitter
	 * @param IFileUploadService $upload_service
	 * @return INews|News
	 */
	public function buildNews(NewsMainInfo $info, $tags, $submitter,  IFileUploadService $upload_service);

	/**
	 * @param array $data
	 * @return NewsMainInfo
	 */
	public function buildNewsMainInfo(array $data);


    /**
     * @param array $data
     * @return NewsSubmitter
     */
    public function buildNewsSubmitter(array $data);

    public function setNewsID(INews $news, array $data);
} 