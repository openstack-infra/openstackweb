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
 * Interface INews
 */
interface INews extends IEntity {

    /**
     * @return ISubmitter
     */
    public function getSubmitter();

    public function setSubmitter(ISubmitter $submitter);

    /**
     * @return ITag[]
     */
    public function getTags();

    public function addTag(ITag $tag);

    public function clearTags();

	/**
	 * @param IFileUploadService $upload_service
	 */
	public function registerImage(IFileUploadService $upload_service);

    /**
     * @param IFileUploadService $upload_service
     */
    public function registerDocument(IFileUploadService $upload_service);

    /**
     * @param NewsSubmitter $submitter
     * @return void
     */
    public function registerSubmitter(NewsSubmitter $info);

    /**
     * @param string[] $tags
     * @return void
     */
    public function registerTags($tags);
} 