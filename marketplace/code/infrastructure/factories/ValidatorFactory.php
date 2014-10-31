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
 * Class ValidatorFactory
 */
final class ValidatorFactory
	implements IValidatorFactory {

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForCompanyService(array $data)
	{
		$rules = array(
			'name'              => 'required|text',
			'overview'          => 'required|text|max:250',
			'active'            => 'required|boolean',
			'company_id'        => 'required|integer',
			'call_2_action_uri' => 'required|url',
		);

		$messages = array(
			'name.required'              => ':attribute is required',
			'name.text'                  => ':attribute should be valid text.',
			'overview.required'          => ':attribute is required',
			'overview.text'              => ':attribute should be valid text.',
			'overview.max'               => ':attribute should have less than 250 chars.',
			'call_2_action_uri.required' => ':attribute is required',
			'call_2_action_uri.url'      => ':attribute should be valid url.',
			'active.required'            => ':attribute is required',
			'active.boolean'             => ':attribute should be valid boolean value',
			'company_id.required'        => ':attribute is required',
			'company_id.boolean'         => ':attribute should be valid integer value',
		);

		return ValidatorService::make($data, $rules, $messages);
	}

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForCompanyResource(array $data)
	{

		$rules = array(
			'name' => 'required|text|max:250',
			'link' => 'required|url',
		);

		$messages = array(
			'name.required' => '(additional resource) - :attribute is required',
			'name.text'     => '(additional resource) - :attribute should be valid text.',
			'name.max'      => '(additional resource) - :attribute should have less than 250 chars.',
			'link.required' => '(additional resource) - :attribute is required',
			'link.url'      => '(additional resource) - :attribute should be valid url.',
		);

		return ValidatorService::make($data, $rules, $messages);
	}

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForMarketPlaceVideo(array $data)
	{
		$rules = array(
			'title'       => 'required|text',
			'description' => 'sometimes|text',
			'length'      => 'required|integer',
			'type_id'     => 'required|integer',
			'youtube_id'  => 'required|text',
		);

		$messages = array(
			'title.required'       => '(youtube video) - :attribute is required',
			'title.text'           => '(youtube video) - :attribute should be valid text.',
			'description.text'     => '(youtube video) - :attribute should be valid text.',
			'youtube_id.required'  => '(youtube video) - :attribute is required',
			'youtube_id.text'      => '(youtube video) - :attribute should be valid text.',
			'length.required'      => '(youtube video) - :attribute is required',
			'length.integer'       => '(youtube video) - :attribute should be valid integer value.',
			'type_id.required'     => '(youtube video) - :attribute is required',
			'type_id.integer'      => '(youtube video) - :attribute should be valid integer value.',
		);

		return ValidatorService::make($data, $rules, $messages);
	}

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForCapability(array $data)
	{
		$rules = array(
			'component_id' => 'required|integer',
			'release_id'   => 'required|integer',
			'version_id'   => 'required|integer',
			'coverage'     => 'required|integer|between:0,100',
		);

		$messages = array(
			'component_id.required' => '(OpenStack capability) - :attribute is required',
			'component_id.integer'  => '(OpenStack capability) - :attribute should be valid integer value.',
			'release_id.required'   => '(OpenStack capability) - :attribute is required',
			'release_id.integer'    => '(OpenStack capability) - :attribute should be valid integer value.',
			'version_id.required'   => '(OpenStack capability) - :attribute is required',
			'version_id.integer'    => '(OpenStack capability) - :attribute should be valid integer value.',
			'coverage.required'     => '(OpenStack capability) - :attribute is required',
			'coverage.integer'      => '(OpenStack capability) - :attribute should be valid integer value.',
			'coverage.between'      => '(OpenStack capability) - :attribute should be between 0 - 100.',
		);
		return ValidatorService::make($data, $rules, $messages);
	}

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForServiceOffered(array $data)
	{
		$rules = array(
			'component_id'      => 'required|integer',
			'release_id'        => 'required|integer',
			'version_id'        => 'required|integer',
			'coverage'          => 'required|integer|between:0,100',
			'pricing_schema_id' => 'required|integer',
		);

		$messages = array(
			'component_id.required' => '(OpenStack capability) - :attribute is required',
			'component_id.integer'  => '(OpenStack capability) - :attribute should be valid integer value.',
			'release_id.required'   => '(OpenStack capability) - :attribute is required',
			'release_id.integer'    => '(OpenStack capability) - :attribute should be valid integer value.',
			'version_id.required'   => '(OpenStack capability) - :attribute is required',
			'version_id.integer'    => '(OpenStack capability) - :attribute should be valid integer value.',
			'coverage.required'     => '(OpenStack capability) - :attribute is required',
			'coverage.integer'      => '(OpenStack capability) - :attribute should be valid integer value.',
			'coverage.between'      => '(OpenStack capability) - :attribute should be between 0 - 100.',
			'pricing_schema_id.required' => '(OpenStack capability) - :attribute is required',
			'pricing_schema_id.integer'  => '(OpenStack capability) - :attribute should be valid integer value.',
		);
		return ValidatorService::make($data, $rules, $messages);
	}

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForDataCenterRegion(array $data)
	{
		$rules = array(
			'name'      => 'required|text',
			'color'     => 'required|color',
			'endpoint'  => 'sometimes|url',
		);

		$messages = array(
			'name.required' => '(data center region) - :attribute is required',
			'name.text'     => '(data center region) - :attribute should be valid text.',
			'color.required' => '(data center region) - :attribute is required',
			'color.color'     => '(data center region) - :attribute should be valid color(RGB).',
			'endpoint.required' => '(data center region) - :attribute is required',
			'endpoint.url'      => '(data center region) - :attribute should be valid url.',
		);

		return ValidatorService::make($data, $rules, $messages);
	}

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForDataCenterLocation(array $data)
	{
		$rules = array(
			'city'    => 'required|text',
			'country' => 'required|text',
			'region'  => 'required|text',
			'state'   => 'sometimes|text',
			'lat'     => 'required|float',
			'lng'     => 'required|float',
		);

		$messages = array(
			'name.required'    => '(data center location) - :attribute is required',
			'name.text'        => '(data center location) - :attribute should be valid text.',
			'country.required' => '(data center location) - :attribute is required',
			'country.text'     => '(data center location) - :attribute should be valid text.',
			'region.required'  => '(data center location) - :attribute is required',
			'region.text'      => '(data center location) - :attribute should be valid text.',
			'state.text'       => '(data center location) - :attribute should be valid text.',
			'lat.required'     => '(data center location) - :attribute is required.',
			'lat.float'        => '(data center location) - :attribute should be valid float number.',
			'lng.required'     => '(data center location) - :attribute is required.',
			'lng.float'        => '(data center location) - :attribute should be valid float number.',
		);

		return ValidatorService::make($data, $rules, $messages);
	}

	/**
	 * @param array $data
	 * @return IValidator
	 */
	public function buildValidatorForOffice(array $data)
	{
		$rules = array(
			'address_1' => 'sometimes|text',
			'address_2' => 'sometimes|text',
			'zip_code ' => 'sometimes|text',
			'state'     => 'sometimes|text',
			'city'      => 'required|text',
			'country'   => 'required|text',
			'lat'       => 'required|float',
			'lng'       => 'required|float',
		);

		$messages = array(
			'name.required'    => '(office) - :attribute is required',
			'name.text'        => '(office) - :attribute should be valid text.',
			'country.required' => '(office) - :attribute is required',
			'country.text'     => '(office) - :attribute should be valid text.',
			'lat.required'     => '(office) - :attribute is required.',
			'lat.float'        => '(office) - :attribute should be valid float number.',
			'lng.required'     => '(office) - :attribute is required.',
			'lng.float'        => '(office) - :attribute should be valid float number.',
			'address_1.text'   => '(office) - :attribute should be valid text.',
			'address_2.text'   => '(office) - :attribute should be valid text.',
			'zip_code.text'    => '(office) - :attribute should be valid text.',
			'state.text'       => '(office) - :attribute should be valid text.',
		);

		return ValidatorService::make($data, $rules, $messages);
	}
}