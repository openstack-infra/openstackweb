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
 * Action that takes the user back to a given link rather than submitting
 * the form.
 *
 * @package cancelformaction
 */
class CancelFormAction extends FormAction {

	/**
	 * @var string
	 */
	private $link;
	
	function __construct($link = "", $title = "", $form = null, $extraData = null, $extraClass = 'roundedButton') {
		if(!$title) $title = _t('CancelFormAction.CANCEL', 'Cancel');
		
		$this->setLink($link);
	
		parent::__construct('CancelFormAction', $title, $form, $extraData, $extraClass);
	}
	
	function setLink($link) {
		$this->link = $link;
	}
	
	function getLink() {
		return $this->link;
	}
	
	function Field($properties = array()) {

		$properties = array_merge(
			$properties,
			array(
				'id' => $this->id(),
				'name' => $this->action,
				'class' => 'action cancel roundedButton ' . ($this->extraClass() ? $this->extraClass() : ''),
				'name' => $this->action,
				'href' => $this->getLink()
			)
		);

		if($this->isReadonly()) {
			$properties['disabled'] = 'disabled';
			$properties['class'] = $properties['class'] . ' disabled';
		}

		return FormField::create_tag('a', $properties, 	$this->buttonContent ? $this->buttonContent : $this->Title());

	}
}