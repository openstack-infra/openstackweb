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
 * Class NewsValidationFactory
 */
final class NewsValidationFactory
	implements INewsValidationFactory{

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForNews(array $data){

		$rules = array(
			'headline'              => 'required|text|max:100',
			'date'                  => 'required|date',
			'summary'               => 'required|htmltext',
			'tags'                  => 'required|text',
			//'date_embargo'          => 'required|date',
			'submitter_first_name'  => 'required|text',
            'submitter_last_name'   => 'required|text',
			'submitter_email'       => 'required|email',
            'submitter_company'     => 'required|htmltext',
		);

		$messages = array(
			'headline.required'        => ':attribute is required',
			'headline.text'            => ':attribute should be valid text.',
			'headline.max'             => ':attribute should have less than 100 chars.',
			'date.required'            => ':attribute is required',
			'date.date'                => ':attribute should be a valid date.',
			'summary.required'         => ':attribute is required',
			'summary.htmltext'         => ':attribute should be valid text.',
			'tags.required'            => ':attribute is required',
			'tags.text'                => ':attribute should be valid text.',
			//'date_embargo.required'    => ':attribute is required',
			//'date_embargo.date'        => ':attribute should be a valid date.',
            'submitter_first_name.required'  => ':attribute is required',
            'submitter_first_name.text'      => ':attribute should be valid text.',
            'submitter_last_name.required'   => ':attribute is required',
            'submitter_last_name.text'       => ':attribute should be valid text.',
            'submitter_email.required'       => ':attribute is required',
            'submitter_email.email'          => ':attribute should be valid email.',
            'submitter_company.required'     => ':attribute is required',
            'submitter_company.htmltext'     => ':attribute should be valid text.',
		);

		return ValidatorService::make($data, $rules, $messages);
	}

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForNewsRejection(array $data){
		$rules = array(
			'custom_reject_message' => 'sometimes|text'
		);

		$messages = array(
			'custom_reject_message.text' => ':attribute should be valid text.'
		);

		return ValidatorService::make($data, $rules, $messages);
	}
}