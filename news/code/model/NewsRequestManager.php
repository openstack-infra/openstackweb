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
 * Class NewsRequestManager
 */
final class NewsRequestManager {
	/**
	 * @var INewsValidationFactory
	 */
	private $validator_factory;
	/**
	 * @var ITransactionManager
	 */
	private $tx_manager;
	/**
	 * @var INewsFactory
	 */
	private $factory;

	/**
	 * @var IEntityRepository
	 */
	private $news_repository;

    /**
     * @var IEntityRepository
     */
    private $submitter_repository;

	/**
	 * @var IFileUploadService
	 */
	private $upload_service;

	/**
	 * @param IEntityRepository      $news_repository
	 * @param IEntityRepository      $submitter_repository
	 * @param INewsFactory           $factory
	 * @param INewsValidationFactory $validator_factory
	 * @param IFileUploadService     $upload_service
	 * @param ITransactionManager    $tx_manager
	 */
	public function __construct(IEntityRepository $news_repository,
                                IEntityRepository $submitter_repository,
	                            INewsFactory $factory,
	                            INewsValidationFactory $validator_factory,
	                            IFileUploadService $upload_service,
	                            ITransactionManager $tx_manager){

		$this->news_repository         = $news_repository;
        $this->submitter_repository         = $submitter_repository;
		$this->validator_factory       = $validator_factory;
		$this->factory                 = $factory;
		$this->upload_service          = $upload_service;
		$this->tx_manager              = $tx_manager;
	}
	/**
	 * @param array $data
	 * @return INews
	 */
	public function postNews(array $data){
		$validator_factory    = $this->validator_factory;
		$factory              = $this->factory;
		$repository           = $this->news_repository ;
        $submitter_repository = $this->submitter_repository;
		$upload_service       = $this->upload_service;

		return $this->tx_manager->transaction(function() use($data, $repository, $submitter_repository, $factory, $validator_factory, $upload_service){
			$validator = $validator_factory->buildValidatorForNews($data);
			if ($validator->fails()) {
					throw new EntityValidationException($validator->messages());
			}

            $submitter = $submitter_repository->getSubmitterByEmail($data['submitter_email']);
            if (!$submitter) {
                $submitter = $factory->buildNewsSubmitter($data);
            }

			$news = $factory->buildNews(
				$factory->buildNewsMainInfo($data),
				$data['tags'],
                $submitter,
				$upload_service
			);

			$repository->add($news);

            //send email
            $email = EmailFactory::getInstance()->buildEmail(NEWS_SUBMISSION_EMAIL_FROM,
                NEWS_SUBMISSION_EMAIL_ALERT_TO,
                NEWS_SUBMISSION_EMAIL_SUBJECT);

            $email->setTemplate('NewsSubmissionEmail');
            $email->populateTemplate(array(
                'ArticleHeadline'      => $news->Headline,
                'ArticleSummary'      => $news->Summary
            ));

            $email->send();
		});
	}

	/**
	 * @param array $data
	 * @return INews
	 */
	public function updateNews(array $data){
        $validator_factory    = $this->validator_factory;
        $factory              = $this->factory;
        $repository           = $this->news_repository ;
        $upload_service       = $this->upload_service;

		return $this->tx_manager->transaction(function() use($data, $repository, $validator_factory, $factory, $upload_service){
			$validator = $validator_factory->buildValidatorForNews($data);
			if ($validator->fails()) {
				throw new EntityValidationException($validator->messages());
			}

			$news = $repository->getById(intval($data['newsID']));
			if(!$news)
				throw new NotFoundEntityException('News',sprintf('id %s',$data['id'] ));


            $news_main_info = $factory->buildNewsMainInfo($data);
            $news->registerMainInfo($news_main_info);
            //create image object
            $image_info = $news_main_info->getImage();
            if($image_info['size']){
                $news->registerImage($upload_service);
            }
            //create image object
            $document_info = $news_main_info->getDocument();
            if($document_info['size']){
                $news->registerDocument($upload_service);
            }

            $news->clearTags();
            $news->registerTags($data['tags']);

			return $news;
		});
	}

    /**
     * @param integer $article_id
     * @param integer $new_rank
     * @param string $target
     * @return INews
     */
    public function moveNewsArticle($article_id,$new_rank,$target){
        $repository           = $this->news_repository ;

        return $this->tx_manager->transaction(function() use($repository,$article_id,$new_rank,$target){

            $news = $repository->getById(intval($article_id));
            if(!$news)
                throw new NotFoundEntityException('News',sprintf('id %s',$article_id ));

            $news->registerSection($target);
            $news->registerRank($new_rank);

        });
    }

    /**
     * @param integer $article_id
     * @param integer $new_rank
     * @param string $target
     * @return INews
     */
    public function sortNewsArticles($article_id,$new_rank,$old_rank,$is_new,$is_remove,$type){
        $repository           = $this->news_repository ;

        return $this->tx_manager->transaction(function() use($repository,$article_id,$new_rank,$old_rank,$is_new,$is_remove,$type){

            $result_array = $repository->getArticlesToSort($article_id,$new_rank,$old_rank,$is_new,$is_remove,$type);
            $rank_delta = $result_array[1];
            $unsorted_news = $result_array[0];

            foreach ($unsorted_news as $article) {
                $article->Rank = $article->Rank + $rank_delta;
            }

        });
    }

    /**
     * to be called inside a transaction, reorders all articles
     */
    private function reorderArticles($section,$repository) {
        $news = $repository->getArticlesBySection($section);

        foreach ($news as $key => $article) {
            $article->Rank = $key + 1;
        }
    }

    /**
     * Remove all articles that have expired from published sections .
     */
    public function removeExpired(){
        $repository           = $this->news_repository ;

        return $this->tx_manager->transaction(function() use($repository){
            $expired_news = $repository->getExpiredNews();

            foreach ($expired_news as $article) {
                $article->registerSection('standby');
            }

            $this->reorderArticles('recent',$repository);
            $this->reorderArticles('slider',$repository);
            $this->reorderArticles('featured',$repository);

        });
    }

    /**
     * Moves articles from standby to recent when the embargo date is reached .
     */
    public function activateNews(){
        $repository           = $this->news_repository ;

        return $this->tx_manager->transaction(function() use($repository){
            $expired_news = $repository->getNewsToActivate();

            foreach ($expired_news as $article) {
                $article->registerSection('recent');
                $article->registerRank(1);
            }

            $this->reorderArticles('recent',$repository);
        });
    }


} 