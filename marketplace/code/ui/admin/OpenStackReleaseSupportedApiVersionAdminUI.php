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
final class OpenStackReleaseSupportedApiVersionAdminUI
extends DataExtension {

	/**
	 * @param FieldList $fields
	 * @return FieldList|void
	 */

	private $versions;

	public function updateCMSFields(FieldList $fields) {
		$fields->removeByName('ApiVersionID');

		$versions = OpenStackApiVersion::get()->map('ID', 'Version');
		$ddl = new DropdownField('ApiVersionID', 'API Version', $versions);
		$ddl->setEmptyString('--Select An API Version --');
		$fields->insertAfter($ddl,'OpenStackComponentID');

		$versions = array();
		foreach(OpenStackComponent::get()->filter('SupportsVersioning',true) as $component){
			foreach($component->getVersions() as $version){
				if(!array_key_exists(intval($component->getIdentifier()),$versions)){
					$versions[intval($component->getIdentifier())] = array();
				}
				array_push($versions[intval($component->getIdentifier())],array('value' => intval($version->getIdentifier()), 'text' => $version->getVersion()));
			}
		}

		$json_data = json_encode($versions);
		$script = <<<JS
		<script>
		var versions = {$json_data};
		(function($) {

			var ddl_component = $('#Form_ItemEditForm_OpenStackComponentID');
			var ddl_versions = $('#Form_ItemEditForm_ApiVersionID');
			ddl_component.change(function(event){
						var component_id = $(this).val();
						var component_versions = versions[component_id];
						ddl_versions.empty(); //remove all child nodes
						ddl_versions.html('');
						ddl_versions.append("<option value='' selected='selected'>-- Please Select --</option>");
						$.each(component_versions, function (i, item) {
		                    ddl_versions.append($('<option>', {value: item.value,text : item.text}));
						});
						ddl_versions.trigger('liszt:updated');
			});
			var current_component = ddl_component.val();

			if(current_component!=''){
				var current_version = ddl_versions.val();

				var component_versions = versions[current_component];
				ddl_versions.empty(); //remove all child nodes
				ddl_versions.html('');
				ddl_versions.append("<option value='' selected='selected'>-- Please Select --</option>");
				$.each(component_versions, function (i, item) {
		             ddl_versions.append($('<option>', {value: item.value,text : item.text}));
				});
				ddl_versions.val(current_version);
				ddl_versions.trigger('liszt:updated');
			}
		})(jQuery);</script>
JS;

		$fields->add(new LiteralField('js_data', $script));
		$fields->removeByName('OpenStackComponentID');
		//kludge; get parent id from url....
		$url = preg_split('/\//', $_REQUEST['url']);
		$release_id = (int)$url[8];
		$ddl = new DropdownField('OpenStackComponentID', 'OpenStack Component', OpenStackComponent::get()->filter('SupportsVersioning',true)->innerJoin('OpenStackRelease_OpenStackComponents',"OpenStackRelease_OpenStackComponents.OpenStackComponentID = OpenStackComponent.ID AND OpenStackReleaseID = {$release_id} ")->map('ID','Name'));
		$ddl->setEmptyString('--Select A OS Component--');
		$fields->insertBefore($ddl,'ApiVersionID');
		return $fields;
	}


	public function onBeforeWrite(){
		//create group here?
		parent::onBeforeWrite();
	}

	function getCMSValidator()
	{
		return $this->getValidator();
	}

	function getValidator()
	{
		$validator= new RequiredFields(array('OpenStackComponentID','ApiVersionID'));
		return $validator;
	}
}