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
 * Class GridFieldBecomeMemberAction
 */
final class GridFieldBecomeMemberAction implements GridField_ColumnProvider, GridField_ActionProvider {

	public function augmentColumns($gridField, &$columns) {
		if(!in_array('Actions', $columns)) {
			$columns[] = 'Actions';
		}
	}

	public function getColumnAttributes($gridField, $record, $columnName) {
		return array('class' => 'col-buttons');
	}

	public function getColumnMetadata($gridField, $columnName) {
		if($columnName == 'Actions') {
			return array('title' => '');
		}
	}

	public function getColumnsHandled($gridField) {
		return array('Actions');
	}

	public function getColumnContent($gridField, $record, $columnName) {
		if(!$record->canEdit()) return;
		$member = $gridField->getList()->byID($record->ID);
		$allowed = !$member->isFoundationMember();

		$title = $allowed ? "Make this user a Foundation Member":"Foundation Member";
		$icon  = $allowed ? 'chain--exclamation':'chain-unchain';

		$field = GridField_FormAction::create($gridField,  'becomefoundationmember'.$record->ID, false, "becomefoundationmember",
			array('RecordID' => $record->ID))
			->setAttribute('title',$title)
			->setAttribute('data-icon',$icon)
			->setDescription($title);
		$field->setDisabled(!$allowed);

		return $field->Field();
	}

	public function getActions($gridField) {
		return array('becomefoundationmember');
	}

	public function handleAction(GridField $gridField, $actionName, $arguments, $data) {
		if($actionName == 'becomefoundationmember') {
			$member = $gridField->getList()->byID($arguments['RecordID']);
			$allowed = !$member->isFoundationMember();
			$msg  = 'This user is already a Foundation Member!';
			if($allowed){
				$member->upgradeToFoundationMember();
				$msg = 'User is now a Foundation Member';
			}
			Controller::curr()->getResponse()->setStatusCode(200,$msg);
		}
	}
}

/**
 * Class SecurityAdminDecorator
 */
final class SecurityAdminDecorator extends Extension {

    public function updateEditForm(Form &$form){
	    $root_tab = $form->Fields()->fieldByName('Root');
	    $tabs = $root_tab->Tabs();
	    $users_tab = $tabs->fieldByName('Users');
	    $members =  $users_tab->Fields()->FieldByName('Members');
	    if(!is_null($members))
	        $members->getConfig()->addComponent(new GridFieldBecomeMemberAction());
	    return $form;
    }
}
