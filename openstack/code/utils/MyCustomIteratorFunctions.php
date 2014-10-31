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
 * Class MyCustomIteratorFunctions
 */
final class MyCustomIteratorFunctions implements TemplateIteratorProvider
{

	protected $iteratorPos;
	protected $iteratorTotalItems;

	public static function get_template_iterator_variables()
	{
		return array('Mid','IsFourth','MultipleOf');
	}

	public function iteratorProperties($pos, $totalItems)
	{
		$this->iteratorPos = $pos;
		$this->iteratorTotalItems = $totalItems;
	}

	/**
	 * @return bool
	 */
	function Mid()
	{
		$mid = round( $this->iteratorTotalItems / 2);
		return ($this->iteratorPos+1) == $mid;
	}

	public function IsFourth(){
		return ($this->iteratorPos % 4) == 0;
	}

	public function MultipleOf($mul){
		$mul = (int)$mul;
		return ($this->iteratorPos + 1) % $mul == 0;
	}
}