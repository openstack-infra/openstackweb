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
class DeploymentSurveyMoreDeploymentDetailsForm extends Form
{

	function __construct($controller, $name)
	{

		$CurrentDeploymentID = Session::get('CurrentDeploymentID');

		// Define fields //////////////////////////////////////

		$fields = new FieldList (
			new LiteralField('Break', '<p>The information below will help us better understand
        the most common configuration and component choices OpenStack deployments are using.</p>'),
        new LiteralField('Break', ColumnFormatter::$left_column_start),
        new CheckboxSetField(
            'Hypervisors',
            'If you are using OpenStack Compute, which hypervisors are you using?',
            ArrayUtils::AlphaSort( Deployment::$hypervisors_options)
        ),
        new TextField('OtherHypervisor','Other Hypervisor'),
        new CheckboxSetField(
            'NetworkDrivers',
            'Do you use nova-network, or OpenStack Network (Neutron)? If you are using OpenStack Network (Neutron), which drivers are you using?',
            ArrayUtils::AlphaSort( Deployment::$network_driver_options)
        ),
        new TextField('OtherNetworkDriver','Other Network Driver'),
        new CheckboxSetField(
	        'WhyNovaNetwork',
	        'If you are using nova-network and not OpenStack Networking (Neutron), what would allow you to migrate to Neutron?',
	        ArrayUtils::AlphaSort(Deployment::$why_nova_network_options,null,array(	'Other (please specify)'=>'Other (please specify)'))),
        new LiteralField('Break','<br/>'),
        new TextField('OtherWhyNovaNetwork',''),
        new LiteralField('Break', ColumnFormatter::$right_column_start),
        new CheckboxSetField(
            'BlockStorageDrivers',
            'If you are using OpenStack Block Storage, which drivers are you using?',
            ArrayUtils::AlphaSort( Deployment::$block_storage_divers_options)
        ),
        new TextField('OtherBlockStorageDriver','Other Block Storage Driver'),
        new CheckboxSetField(
            'IdentityDrivers',
            'If you are using OpenStack Identity which OpenStack Identity drivers are you using?',
            ArrayUtils::AlphaSort( Deployment::$identity_driver_options)
        ),
        new TextField('OtherIndentityDriver','Other/Custom Identity Driver'),
        new LiteralField('Break', ColumnFormatter::$end_columns),


        new LiteralField('Break','<hr/>'),
        new LiteralField('Break', ColumnFormatter::$left_column_start),
        new CheckboxSetField(
            'SupportedFeatures',
            'Which of the following compatibility APIs does/will your deployment support?',
            ArrayUtils::AlphaSort( Deployment::$deployment_features_options_new)
        ),
        new LiteralField('Break', ColumnFormatter::$right_column_start),
        new CheckboxSetField(
            'DeploymentTools',
            'What tools are you using to deploy/configure your cluster?',
            ArrayUtils::AlphaSort( Deployment::$deployment_tools_options)
        ),
        new TextField('OtherDeploymentTools','Other tools'),
        new LiteralField('Break', ColumnFormatter::$end_columns),

        new LiteralField('Break', ColumnFormatter::$left_column_start),
        new CheckboxSetField(
            'OperatingSystems',
            'What is the main Operating System you are using to run your OpenStack cloud?',
            ArrayUtils::AlphaSort( Deployment::$operating_systems_options)
        ),
        new TextField('OtherOperatingSystems','Other Operating System'),
        new LiteralField('Break', ColumnFormatter::$right_column_start),
        new LiteralField('Break', ColumnFormatter::$end_columns),


        new LiteralField('Break','<hr/>'),
        new LiteralField('Break','<p>Please provide the following information about the
        size and scale of this OpenStack deployment. This information is optional, but will
        be kept confidential and never published in connection with your organization.</p>'),
        new LiteralField('Break','<p><strong>If using OpenStack Compute, what’s the size of your cloud?</strong></p>'),
        new LiteralField('Break', ColumnFormatter::$left_column_start),
        new DropdownField(
            'ComputeNodes',
            'Physical compute nodes',
            Deployment::$compute_nodes_options
        ),
        new LiteralField('Break', ColumnFormatter::$right_column_start),
        new DropdownField(
            'ComputeCores',
            'Processor cores',
            Deployment::$compute_cores_options
        ),
        new LiteralField('Break', ColumnFormatter::$end_columns),
        new DropdownField(
            'ComputeInstances',
            'Number of instances',
            Deployment::$compute_instances_options
        ),
        new DropdownField(
            'BlockStorageTotalSize',
            'If using OpenStack Block Storage, what’s the size of your cloud by total storage in terabytes?',
            Deployment::$storage_size_options
        ),
        new LiteralField('Break','<p><strong>If using OpenStack Object Storage, what’s the size of your cloud?</strong></p>'),
        new LiteralField('Break', ColumnFormatter::$left_column_start),
        new DropdownField(
            'ObjectStorageSize',
            'Total storage in terabytes',
            Deployment::$storage_size_options
        ),
        new LiteralField('Break', ColumnFormatter::$right_column_start),
        new DropdownField(
            'ObjectStorageNumObjects',
            'Total objects stored',
            Deployment::$stoage_objects_options
        ),
        new LiteralField('Break', ColumnFormatter::$right_column_start),
        new DropdownField(
	        'SwiftGlobalDistributionFeatures',
	        'Are you using Swift\'s global distribution features?',
	        ArrayUtils::AlphaSort( Deployment::$swift_global_distribution_features_options,array('unspecified' => '-- Select One --'))
        ),
        new LiteralField('Break', ColumnFormatter::$left_column_start),
        new DropdownField(
	        'SwiftGlobalDistributionFeaturesUsesCases',
	        'If yes, what is your use case?',
	        ArrayUtils::AlphaSort(  Deployment::$swift_global_distribution_features_uses_cases_options,array('unspecified' => '-- Select One --'))
        ),
        new TextField('OtherSwiftGlobalDistributionFeaturesUsesCases',''),

        new DropdownField(
	        'Plans2UseSwiftStoragePolicies',
	        'Do you have plans to use Swift\'s storage policies or erasure codes in the next year?',
	        ArrayUtils::AlphaSort( Deployment::$plans_2_use_swift_storage_policies_options,array('unspecified' => '-- Select One --'))
        ),
        new TextField('OtherPlans2UseSwiftStoragePolicies',''),
        new LiteralField('Break', ColumnFormatter::$end_columns),
        new DropdownField(
            'NetworkNumIPs',
            'If using OpenStack Network, what’s the size of your cloud by number of fixed/floating IPs?',
            Deployment::$network_ip_options
        ),
        new CheckboxSetField(
	        'UsedDBForOpenStackComponents',
	        'What database do you use for the components of your OpenStack cloud?',
	        ArrayUtils::AlphaSort( Deployment::$used_db_for_openstack_components_options,null,array('Other' => 'Other (Specify)'))
        ),
        new TextField('OtherUsedDBForOpenStackComponents',''),
        new CheckboxSetField(
		       'ToolsUsedForYourUsers',
		       'What tools are you using charging or show-back for your users?',
		       ArrayUtils::AlphaSort( Deployment::$tools_used_for_your_users_options,null,array('Other' => 'Other (Specify)'))
	    ),
        new TextField('OtherToolsUsedForYourUsers',''),
        new TextareaField('Reason2Move2Ceilometer','If you are not using Ceilometer, what would allow you to move to it (optional free text)?')
      );

      $saveButton = new FormAction('SaveDeployment', 'Save Deployment');
      $cancelButton = new CancelFormAction($controller->Link().'Deployments', 'Cancel');

      $actions = new FieldList(
          $saveButton, $cancelButton
      );

      // Create Validators
      $validator = new RequiredFields();


      parent::__construct($controller, $name, $fields, $actions, $validator);

      if($CurrentDeploymentID) {
          //Populate the form with the current members data
          if ($Deployment = $this->controller->LoadDeployment($CurrentDeploymentID)) {
              $this->loadDataFrom($Deployment->data());
          } else {
              // HTTP ERROR
              return $this->httpError(403, 'Access Denied.');
          }
      }

   }

   function SaveDeployment($data, $form) {

      $id = Session::get('CurrentDeploymentID');

      // Only loaded if it belongs to current user
      $Deployment = $form->controller->LoadDeployment($id);

      $form->saveInto($Deployment);
      $Deployment->write();

      Session::clear('CurrentDeploymentID');
	   Controller::curr()->redirect($form->controller->Link() . 'Deployments');
   }

   function Cancel($data, $form) {
	   Controller::curr()->redirect($form->controller->Link() . 'Deployments');
   }

   function forTemplate() {
      return $this->renderWith(array(
         $this->class,
         'Form'
      ));
   }

}

