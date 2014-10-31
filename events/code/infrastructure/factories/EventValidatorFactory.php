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
 * Class EventValidatorFactory
 */
final class EventValidatorFactory
	implements IEventValidatorFactory {
	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForEventRegistration(array $data)
	{
		$rules = array(
			'title'                  => 'required|text|max:35',
			'url'                    => 'required|url',
			'city'                   => 'required|text',
			'state'                  => 'sometimes|text',
			'country'                => 'required|text',
			'start_date'             => 'required|date',
			'end_date'               => 'required|date|after:start_date',
			'point_of_contact_name'  => 'required|text',
			'point_of_contact_email' => 'required|email',
		);

		$messages = array(
			'title.required'                  => ':attribute is required',
			'title.text'                      => ':attribute should be valid text.',
			'title.max'                       => ':attribute should have less than 35 chars.',
			'url.required'                    => ':attribute is required',
			'url.url'                         => ':attribute should be valid url.',
			'city.required'                   => ':attribute is required',
			'city.text'                       => ':attribute should be valid text.',
			'state.text'                      => ':attribute should be valid text.',
			'country.required'                => ':attribute is required',
			'country.text'                    => ':attribute should be valid text.',
			'start_date.required'             => ':attribute is required',
			'start_date.date'                 => ':attribute should be valid date.',
			'end_date.required'               => ':attribute is required',
			'end_date.date'                   => ':attribute should be valid date.',
			'end_date.after'                  => ':attribute should be after than start_date.',
			'point_of_contact_name.required'  => ':attribute is required',
			'point_of_contact_name.text'      => ':attribute should be valid text.',
			'point_of_contact_email.required' => ':attribute is required',
			'point_of_contact_email.email'    => ':attribute should be valid email.'
		);

		return ValidatorService::make($data, $rules, $messages);
	}

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForEventRejection(array $data)
	{
		$rules = array(
			'send_rejection_email' => 'required|boolean',
			'custom_reject_message' => 'sometimes|text'
		);

		$messages = array(
			'send_rejection_email.required' => ':attribute is required',
			'send_rejection_email.boolean' => ':attribute should be valid boolean.',
			'custom_reject_message.text' => ':attribute should be valid text.'
		);

		return ValidatorService::make($data, $rules, $messages);
	}
}