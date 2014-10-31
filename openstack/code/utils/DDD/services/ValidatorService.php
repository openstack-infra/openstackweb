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
final class ValidatorService implements IValidator {

	private $messages;
	protected $failedRules      = array();
	protected $data;
	protected $files            = array();
	protected $rules;
	protected $customMessages   = array();
	protected $fallbackMessages = array();
	protected $customAttributes = array();
	protected $replacers        = array();
	protected $sizeRules        = array('Size', 'Between', 'Min', 'Max');
	protected $numericRules     = array('Numeric', 'Integer');
	protected $implicitRules    = array('Required', 'RequiredWith', 'RequiredWithAll', 'RequiredWithout', 'RequiredWithoutAll', 'RequiredIf', 'Accepted');

	/**
	 * @param array $values
	 * @param array $rules
	 */
	private function __construct($data, $rules, $messages = array(), $customAttributes = array()){
		$this->customMessages   = $messages;
		$this->data             = $this->parseData($data);
		$this->rules            = $this->explodeRules($rules);
		$this->customAttributes = $customAttributes;
	}

	private function __clone(){}

	/**
	 * @param array $data
	 * @param array $rules
	 * @param array $messages
	 * @param array $customAttributes
	 * @return ValidatorService
	 */
	public static function make(array $data, array $rules, array $messages = array(), array $customAttributes = array()){
		$instance = new ValidatorService( $data, $rules, $messages, $customAttributes);
		return $instance;
	}


	public function getCustomMessages()
	{
		return $this->customMessages;
	}

	/**
	 * Set the custom messages for the validator
	 *
	 * @param  array  $messages
	 * @return void
	 */
	public function setCustomMessages(array $messages)
	{
		$this->customMessages = array_merge($this->customMessages, $messages);
	}

	/**
	 * Get the fallback messages for the validator.
	 *
	 * @return void
	 */
	public function getFallbackMessages()
	{
		return $this->fallbackMessages;
	}

	/**
	 * Set the fallback messages for the validator.
	 *
	 * @param  array  $messages
	 * @return void
	 */
	public function setFallbackMessages(array $messages)
	{
		$this->fallbackMessages = $messages;
	}

	/**
	 * Get the failed validation rules.
	 *
	 * @return array
	 */
	public function failed()
	{
		return $this->failedRules;
	}

	/**
	 * Explode the rules into an array of rules.
	 *
	 * @param  string|array  $rules
	 * @return array
	 */
	protected function explodeRules($rules)
	{
		foreach ($rules as $key => &$rule)
		{
			$rule = (is_string($rule)) ? explode('|', $rule) : $rule;
		}

		return $rules;
	}

	protected function parseData(array $data)
	{
		$this->files = array();

		foreach ($data as $key => $value)
		{
			// If this value is an instance of the HttpFoundation File class we will
			// remove it from the data array and add it to the files array, which
			// is used to conveniently separate out the files from other datas.
			if ($value instanceof File)
			{
				$this->files[$key] = $value;

				unset($data[$key]);
			}
		}

		return $data;
	}

	public function fails()
	{
		return ! $this->passes();
	}

	public function passes()
	{
		$this->messages = array();

		// We'll spin through each rule, validating the attributes attached to that
		// rule. Any error messages will be added to the containers with each of
		// the other error messages, returning true if we don't have messages.
		foreach ($this->rules as $attribute => $rules)
		{
			foreach ($rules as $rule)
			{
				$this->validate($attribute, $rule);
			}
		}

		return count($this->messages) === 0;
	}

	public function messages()
	{
		return $this->messages;
	}

	protected function validate($attribute, $rule)
	{
		if (trim($rule) == '') return;

		list($rule, $parameters) = $this->parseRule($rule);

		// We will get the value for the given attribute from the array of data and then
		// verify that the attribute is indeed validatable. Unless the rule implies
		// that the attribute is required, rules are not run for missing values.
		$value = $this->getValue($attribute);

		$validatable = $this->isValidatable($rule, $attribute, $value);

		$method = "validate{$rule}";

		if ($validatable && ! $this->$method($attribute, $value, $parameters, $this))
		{
			$this->addFailure($attribute, $rule, $parameters);
		}
	}

	protected function isImplicit($rule)
	{
		return in_array($rule, $this->implicitRules);
	}

	protected function addFailure($attribute, $rule, $parameters)
	{
		$this->addError($attribute, $rule, $parameters);

		$this->failedRules[$attribute][$rule] = $parameters;
	}

	protected function addError($attribute, $rule, $parameters)
	{
		$message = $this->getMessage($attribute, $rule);

		$message = $this->doReplacements($message, $attribute, $rule, $parameters);

		array_push($this->messages,array('attribute'=>$attribute,'message'=>$message));
	}

	protected function getMessage($attribute, $rule)
	{
		$lowerRule = snake_case($rule);

		$inlineMessage = $this->getInlineMessage($attribute, $lowerRule);

		// First we will retrieve the custom message for the validation rule if one
		// exists. If a custom validation message is being used we'll return the
		// custom message, otherwise we'll keep searching for a valid message.
		if ( ! is_null($inlineMessage))
		{
			return $inlineMessage;
		}

		// Finally, if no developer specified messages have been set, and no other
		// special messages apply for this rule, we will just pull the default
		// messages out of the translator service for this validation rule.
		$key = "validation.{$lowerRule}";

		return $this->getInlineMessage(
			$attribute, $lowerRule, $this->fallbackMessages
		) ?: $key;
	}

	protected function doReplacements($message, $attribute, $rule, $parameters)
	{
		$message = str_replace(':attribute', $this->getAttribute($attribute), $message);

		if (isset($this->replacers[snake_case($rule)]))
		{
			$message = $this->callReplacer($message, $attribute, snake_case($rule), $parameters);
		}
		elseif (method_exists($this, $replacer = "replace{$rule}"))
		{
			$message = $this->$replacer($message, $attribute, $rule, $parameters);
		}

		return $message;
	}

	/**
	 * Call a custom validator message replacer.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function callReplacer($message, $attribute, $rule, $parameters)
	{
		$callback = $this->replacers[$rule];

		if ($callback instanceof Closure)
		{
			return call_user_func_array($callback, func_get_args());
		}
		elseif (is_string($callback))
		{
			return $this->callClassBasedReplacer($callback, $message, $attribute, $rule, $parameters);
		}
	}

	/**
	 * Call a class based validator message replacer.
	 *
	 * @param  string  $callback
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function callClassBasedReplacer($callback, $message, $attribute, $rule, $parameters)
	{
		list($class, $method) = explode('@', $callback);

		return call_user_func_array(array($this->container->make($class), $method), array_slice(func_get_args(), 1));
	}


	/**
	 * Get the displayable name of the attribute.
	 *
	 * @param  string  $attribute
	 * @return string
	 */
	protected function getAttribute($attribute)
	{
		// The developer may dynamically specify the array of custom attributes
		// on this Validator instance. If the attribute exists in this array
		// it takes precedence over all other ways we can pull attributes.
		if (isset($this->customAttributes[$attribute]))
		{
			return $this->customAttributes[$attribute];
		}

		return str_replace('_', ' ', $attribute);

	}

	protected function getInlineMessage($attribute, $lowerRule, $source = null)
	{
		$source = $source ?: $this->customMessages;

		$keys = array("{$attribute}.{$lowerRule}", $lowerRule);

		// First we will check for a custom message for an attribute specific rule
		// message for the fields, then we will check for a general custom line
		// that is not attribute specific. If we find either we'll return it.
		foreach ($keys as $key)
		{
			if (isset($source[$key])) return $source[$key];
		}
	}


	protected function isValidatable($rule, $attribute, $value)
	{
		return $this->presentOrRuleIsImplicit($rule, $attribute, $value) &&
		$this->passesOptionalCheck($attribute);
	}

	protected function presentOrRuleIsImplicit($rule, $attribute, $value)
	{
		return $this->validateRequired($attribute, $value) || $this->isImplicit($rule);
	}

	protected function passesOptionalCheck($attribute)
	{
		if ($this->hasRule($attribute, array('Sometimes')))
		{
			return array_key_exists($attribute, $this->data) || array_key_exists($attribute, $this->files);
		}
		else
		{
			return true;
		}
	}

	protected function hasRule($attribute, $rules)
	{
		$rules = (array) $rules;

		// To determine if the attribute has a rule in the ruleset, we will spin
		// through each of the rules assigned to the attribute and parse them
		// all, then check to see if the parsed rules exists in the arrays.
		foreach ($this->rules[$attribute] as $rule)
		{
			list($rule, $parameters) = $this->parseRule($rule);

			if (in_array($rule, $rules)) return true;
		}

		return false;
	}

	protected function getValue($attribute)
	{
		if ( ! is_null($value = array_get($this->data, $attribute)))
		{
			return $value;
		}
		elseif ( ! is_null($value = array_get($this->files, $attribute)))
		{
			return $value;
		}
	}

	protected function parseRule($rule)
	{
		$parameters = array();

		// The format for specifying validation rules and parameters follows an
		// easy {rule}:{parameters} formatting convention. For instance the
		// rule "Max:3" states that the value may only be three letters.
		if (strpos($rule, ':') !== false)
		{
			list($rule, $parameter) = explode(':', $rule, 2);

			$parameters = $this->parseParameters($rule, $parameter);
		}

		return array(studly_case($rule), $parameters);
	}

	protected function parseParameters($rule, $parameter)
	{
		if (strtolower($rule) == 'regex') return array($parameter);

		return str_getcsv($parameter);
	}

	protected function validateSometimes()
	{
		return true;
	}

	/**
	 * Merge additional rules into a given attribute.
	 *
	 * @param  string  $attribute
	 * @param  string|array  $rules
	 * @return void
	 */
	public function mergeRules($attribute, $rules)
	{
		$current = array_get($this->rules, $attribute, array());

		$merge = head($this->explodeRules(array($rules)));

		$this->rules[$attribute] = array_merge($current, $merge);
	}


	//validation methods

	/**
	 * Validate that a required attribute exists.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateRequired($attribute, $value)
	{
		if (is_null($value))
		{
			return false;
		}
		elseif (is_string($value) && trim($value) === '')
		{
			return false;
		}
		elseif ($value instanceof File)
		{
			return (string) $value->getPath() != '';
		}

		return true;
	}

	public function validateBoolean($attribute, $value, $parameters)
	{
		if(is_bool($value))
			return true;
		if(is_int($value))
			return true;
		return strtoupper(trim($value)) =='TRUE' || strtoupper(trim($value))=='FALSE' || strtoupper(trim($value))=='1' || strtoupper(trim($value))=='0' ;
	}

	public function validateText($attribute, $value, $parameters)
	{
		$value = trim($value);
		return preg_match("%[^<>]+$%i", $value) == 1;
	}

	public function validateHtmlText($attribute, $value, $parameters)
	{
		$value = trim($value);
		return true;
	}

	/**
	 * Validate that an attribute is numeric.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateNumeric($attribute, $value)
	{
		return is_numeric($value);
	}

	/**
	 * Validate that an attribute is an integer.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateInteger($attribute, $value)
	{
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	/**
	 * @param $attribute
	 * @param $value
	 * @return bool
	 */
	protected function validateFloat($attribute, $value){
		return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
	}

	/**
	 * Validate that an attribute is a valid date.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateDate($attribute, $value)
	{
		if ($value instanceof DateTime) return true;

		if (strtotime($value) === false) return false;

		$date = date_parse($value);

		return checkdate($date['month'], $date['day'], $date['year']);
	}

	/**
	 * Validate that an attribute matches a date format.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateDateFormat($attribute, $value, $parameters)
	{
		$parsed = date_parse_from_format($parameters[0], $value);

		return $parsed['error_count'] === 0 && $parsed['warning_count'] === 0;
	}


	/**
	 * Validate that an attribute is a valid URL.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateUrl($attribute, $value)
	{
		$value = trim($value);
		$res = filter_var($value, FILTER_VALIDATE_URL);
		return $res!==FALSE;
	}

	public function validateHttpMethod($attribute, $value, $parameters){
		$value = strtoupper(trim($value));
		//'GET', 'HEAD','POST','PUT','DELETE','TRACE','CONNECT','OPTIONS'
		$allowed_http_verbs = array(
			'GET'=>'GET',
			'HEAD'=>'HEAD',
			'POST'=>'POST',
			'PUT'=>'PUT',
			'DELETE'=>'DELETE',
			'TRACE'=>'TRACE',
			'CONNECT'=>'CONNECT',
			'OPTIONS'=>'OPTIONS',
		);

		return array_key_exists($value,$allowed_http_verbs);
	}

	public function validateRelativeUrl($attribute, $value, $parameters){
		return true;
	}

	public function validateVersionStatus($attribute, $value, $parameters){
		return true;
	}

	/**
	 * Validate that an attribute is between a given number of digits.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateDigitsBetween($attribute, $value, $parameters)
	{
		$length = strlen((string) $value);

		return $length >= $parameters[0] && $length <= $parameters[1];
	}

	/**
	 * Validate the size of an attribute.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateSize($attribute, $value, $parameters)
	{
		return $this->getSize($attribute, $value) == $parameters[0];
	}

	/**
	 * Validate the size of an attribute is between a set of values.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateBetween($attribute, $value, $parameters)
	{
		$size = $this->getSize($attribute, $value);

		return $size >= $parameters[0] && $size <= $parameters[1];
	}

	/**
	 * Validate the size of an attribute is greater than a minimum value.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateMin($attribute, $value, $parameters)
	{
		return $this->getSize($attribute, $value) >= $parameters[0];
	}

	/**
	 * Validate the size of an attribute is less than a maximum value.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateMax($attribute, $value, $parameters)
	{
		if ($value instanceof UploadedFile && ! $value->isValid()) return false;

		return $this->getSize($attribute, $value) <= $parameters[0];
	}

	/**
	 * Get the size of an attribute.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @return mixed
	 */
	protected function getSize($attribute, $value)
	{
		$hasNumeric = $this->hasRule($attribute, $this->numericRules);

		// This method will determine if the attribute is a number, string, or file and
		// return the proper size accordingly. If it is a number, then number itself
		// is the size. If it is a file, we take kilobytes, and for a string the
		// entire length of the string will be considered the attribute size.
		if (is_numeric($value) && $hasNumeric)
		{
			return array_get($this->data, $attribute);
		}
		elseif (is_array($value))
		{
			return count($value);
		}
		elseif ($value instanceof File)
		{
			return $value->getSize() / 1024;
		}
		else
		{
			return $this->getStringSize($value);
		}
	}

	/**
	 * Get the size of a string.
	 *
	 * @param  string  $value
	 * @return int
	 */
	protected function getStringSize($value)
	{
		if (function_exists('mb_strlen')) return mb_strlen($value);

		return strlen($value);
	}

	/**
	 * Validate an attribute is contained within a list of values.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateIn($attribute, $value, $parameters)
	{
		return in_array($value, $parameters);
	}

	/**
	 * Validate an attribute is not contained within a list of values.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateNotIn($attribute, $value, $parameters)
	{
		return ! in_array($value, $parameters);
	}

	/**
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return bool
	 */
	protected function validateColor($attribute, $value, $parameters) {
		return preg_match('/^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/i',$value)==1 ;
	}

	/**
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return bool
	 */
	protected function validateEmail($attribute, $value, $parameters) {
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * Validate the date is after a given date.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateAfter($attribute, $value, $parameters)
	{
		if (!($date = strtotime($parameters[0])))
		{
			return strtotime($value) >= strtotime($this->getValue($parameters[0]));
		}
		else
		{
			return strtotime($value) >= $date;
		}
	}

	/**
	 * Validate the date is before a given date.
	 *
	 * @param  string  $attribute
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateBefore($attribute, $value, $parameters)
	{
		if ( ! ($date = strtotime($parameters[0])))
		{
			return strtotime($value) <= strtotime($this->getValue($parameters[0]));
		}
		else
		{
			return strtotime($value) <= $date;
		}
	}
}