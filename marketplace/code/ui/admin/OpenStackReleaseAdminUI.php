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
 * Class OpenStackReleaseAdminUI
 */
final class OpenStackReleaseAdminUI extends DataExtension {

	private static $searchable_fields =  array('Name','ReleaseDate');
	/**
	 * @param FieldList $fields
	 * @return FieldList|void
	 */
	public function updateCMSFields(FieldList $fields) {

		$oldFields = $fields->toArray();
		foreach($oldFields as $field){
			$fields->remove($field);
		}
		$fields->push(new LiteralField("Title","<h2>OpenStack Release</h2>"));
		$fields->push(new TextField("Name","Name"));
		$date = DateField::create('ReleaseDate')->setConfig('showcalendar', true);
		$fields->push($date);
		$date->setTitle('Release Date');
		$fields->push(new TextField("ReleaseNumber","Release Number"));
		$fields->push(new TextField("ReleaseNotesUrl","Release Notes Url"));
		$fields->push( new DropdownField(
			'Status',
			'Status',
			$this->owner->dbObject('Status')->enumValues()
		));

		//components
		if($this->owner->ID>0){
			$components_config = new GridFieldConfig_RelationEditor();

			$components =  new GridField("OpenStackComponents", "Supported Release Components", $this->owner->OpenStackComponents(), $components_config);
			$components_config->removeComponentsByType('GridFieldAddNewButton');
			$fields->push($components);


			//supported versions
			//only if we have components set

			if($this->owner->OpenStackComponents()->filter('SupportsVersioning',true)->count()>0){
				$supported_versions_config = new GridFieldConfig_RecordEditor(10);

				$dataColumns = $supported_versions_config->getComponentByType('GridFieldDataColumns');
				$dataColumns->setDisplayFields(array(
					'OpenStackComponent.Name' => 'Component',
					'ApiVersion.Version'=> 'Api Version',
				));

				//$supported_versions_config->removeComponentsByType('GridFieldEditButton');

				$supported_versions = new GridField("SupportedApiVersions", "Supported Release Components", $this->owner->SupportedApiVersions(" ReleaseID = {$this->owner->getIdentifier()} AND OpenStackComponent.SupportsVersioning = 1 ")->innerJoin('OpenStackComponent','OpenStackComponent.ID = OpenStackReleaseSupportedApiVersion.OpenStackComponentID') , $supported_versions_config);

				$fields->push($supported_versions);
			}
		}
		return $fields;
	}

	public function onAfterWrite(){
		parent::onAfterWrite();
		//create supported versions for not versioned components
		$supported_components  =  $this->owner->OpenStackComponents();
		if($supported_components && count($supported_components)>0){
			$non_versioned_components = array();
			foreach ($supported_components as $component) {
				if(!$component->getSupportsVersioning()){
					//crete dumb version
					array_push($non_versioned_components,$component->getIdentifier());
					$old = $this->owner->SupportedApiVersions(" OpenStackComponentID = {$component->getIdentifier()} AND ApiVersionID = 0 ");
					if(count($old)==0){
						$new_supported_version                        = new OpenStackReleaseSupportedApiVersion;
						$new_supported_version->OpenStackComponentID  = $component->getIdentifier();
						$new_supported_version->ReleaseID             = $this->owner->getIdentifier();
						$new_supported_version->ApiVersionID          = 0;
						$new_supported_version->write();
					}
				}
			}
			$to_delete="";
			if(count($non_versioned_components)>0){
				$to_delete = implode(',',$non_versioned_components);
				$to_delete = "AND OpenStackComponentID NOT IN ({$to_delete})";
			}
			DB::query("DELETE FROM OpenStackReleaseSupportedApiVersion WHERE ReleaseID = {$this->owner->getIdentifier()} AND ApiVersionID = 0 {$to_delete}");
		}
	}

	function getCMSValidator()
	{
		return $this->getValidator();
	}

	function getValidator()
	{
		$validator= new RequiredFields(array('Name','ReleaseNumber','ReleaseDate'));
		return $validator;
	}

}