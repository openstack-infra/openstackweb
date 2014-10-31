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
class Deployment extends DataObject
{

	private static $db = array(

		// Section 2
		'Label' => 'Text',
		'IsPublic' => 'Boolean',
		'DeploymentType' => 'Text',
		'ProjectsUsed' => 'Text',
		'CurrentReleases' => 'Text',
		'DeploymentStage' => 'Text',
		'NumCloudUsers' => 'Text',
		'WorkloadsDescription' => 'Text',
		'OtherWorkloadsDescription' => 'Text',

		// Section 3
		'APIFormats' => "Text",
		'Hypervisors' => "Text",
		'OtherHypervisor' => 'Text',
		'BlockStorageDrivers' => 'Text',
		'OtherBlockStorageDriver' => 'Text',
		'NetworkDrivers' => 'Text',
		'OtherNetworkDriver' => 'Text',
		'WhyNovaNetwork' => 'Text',
		'OtherWhyNovaNetwork' => 'Text',
		'IdentityDrivers' => 'Text',
		'OtherIndentityDriver' => 'Text',
		'SupportedFeatures' => 'Text',
		'DeploymentTools' => 'Text',
		'OtherDeploymentTools' => 'Text',
		'OperatingSystems' => 'Text',
		'OtherOperatingSystems' => 'Text',
		'ComputeNodes' => 'Text',
		'ComputeCores' => 'Text',
		'ComputeInstances' => 'Text',
		'BlockStorageTotalSize' => 'Text',
		'ObjectStorageSize' => 'Text',
		'ObjectStorageNumObjects' => 'Text',
		'NetworkNumIPs' => 'Text',
		//control fields
		'SendDigest' => 'Boolean',// SendDigest = 1 SENT, SendDigest = 0 to be send
		'UpdateDate' => 'SS_Datetime',
		'SwiftGlobalDistributionFeatures' => 'Text',
		'SwiftGlobalDistributionFeaturesUsesCases' => 'Text',
		'OtherSwiftGlobalDistributionFeaturesUsesCases' => 'Text',
		'Plans2UseSwiftStoragePolicies' => 'Text',
		'OtherPlans2UseSwiftStoragePolicies' => 'Text',
		'UsedDBForOpenStackComponents' => 'Text',
		'OtherUsedDBForOpenStackComponents' => 'Text',
		'ToolsUsedForYourUsers' => 'Text',
		'OtherToolsUsedForYourUsers' => 'Text',
		'Reason2Move2Ceilometer' => 'Text',
	);

	private static $has_one = array(
		'DeploymentSurvey' => 'DeploymentSurvey',
		'Org' => 'Org'
	);

	private static $summary_fields = array(
		'Label' => 'Label',
		'Org.Name' => 'Organization'
	);

	private static $singular_name = 'Deployment';
	private static $plural_name = 'Deployments';


	protected function onBeforeWrite()
	{
		parent::onBeforeWrite();
		$this->UpdateDate = SS_Datetime::now()->Rfc2822();
	}

	function getCMSFields()
	{


		$fields = new FieldSet(
			$rootTab = new TabSet("Root",
				$tabContent = new TabSet('Content',
					new Tab('Main'),
					new Tab('Details')
				))
		);

		$fields->addFieldsToTab('Root.Content.Main',
			array(
				new TextField('Label', 'Deployment Name'),
				new OptionSetField(
					'IsPublic',
					'Would you like to keep this information confidential or allow the Foundation to share information about this deployment publicly?',
					array('1' => '<strong>Willing to share:</strong> The information on this page may be shared for this deployment',
						'0' => '<strong>Confidential:</strong> All details provided should be kept confidential to the OpenStack Foundation'),
					1
				),
				new DropdownField('OrgID', 'Organization', DataObject::get('Org', '', 'Org.Name ASC')->map("ID", "Name", "-- Please choose an Organization --")),
				new DropdownField('DeploymentType', 'Deployment Type', Deployment::$deployment_type_options),
				new CheckboxSetField('ProjectsUsed', 'Projects Used', Deployment::$projects_used_options),
				new CheckboxSetField('CurrentReleases', 'What releases are you currently using?', Deployment::$current_release_options),
				new CheckboxSetField(
					'DeploymentStage',
					'In what stage is your OpenStack deployment? (make a new deployment profile for each type of deployment)',
					Deployment::$stage_options
				),
				new TextAreaField('NumCloudUsers',
					'What\'s the size of your cloud by number of users?'),
				new CheckboxSetField(
					'WorkloadsDescription',
					'Describe the workloads or applications running in your Openstack environment. (choose any that apply)',
					ArrayUtils::AlphaSort(Deployment::$workloads_description_options, null, array('Other' => 'Other (please specify)'))),
				new TextAreaField(
					'OtherWorkloadsDescription',
					'Other workloads or applications running in your Openstack environment. (optional)'),
			));

		$fields->addFieldsToTab('Root.Content.Details',

			array(
				new LiteralField('Break', '<p>The information below will help us better understand
        the most common configuration and component choices OpenStack deployments are using.</p>'),
				new CheckboxSetField(
					'Hypervisors',
					'If you are using OpenStack Compute, which hypervisors are you using?',
					ArrayUtils::AlphaSort(Deployment::$hypervisors_options)
				),
				new TextField('OtherHypervisor', 'Other Hypervisor'),
				new CheckboxSetField(
					'NetworkDrivers',
					'Do you use nova-network, or OpenStack Network (Neutron)? If you are using OpenStack Network (Neutron), which drivers are you using?',
					ArrayUtils::AlphaSort(Deployment::$network_driver_options)
				),
				new TextField('OtherNetworkDriver', 'Other Network Driver'),
				new CheckboxSetField(
					'WhyNovaNetwork',
					'If you are using nova-network and not OpenStack Networking (Neutron), what would allow you to migrate? (optional)',
					ArrayUtils::AlphaSort(Deployment::$why_nova_network_options, null, array('Other (please specify)' => 'Other (please specify)'))),
				new LiteralField('Break', '<br/>'),
				new TextField('OtherWhyNovaNetwork', ''),
				new CheckboxSetField(
					'BlockStorageDrivers',
					'If you are using OpenStack Block Storage, which drivers are you using?',
					ArrayUtils::AlphaSort(Deployment::$block_storage_divers_options)
				),
				new TextField('OtherBlockStorageDriver', 'Other Block Storage Driver'),
				new CheckboxSetField(
					'IdentityDrivers',
					'If you are using OpenStack Identity which OpenStack Identity drivers are you using?',
					ArrayUtils::AlphaSort(Deployment::$identity_driver_options)
				),
				new TextField('OtherIndentityDriver', 'Other/Custom Identity Driver'),
				new CheckboxSetField(
					'SupportedFeatures',
					'Which of the following compatibility APIs does/will your deployment support?',
					ArrayUtils::AlphaSort(Deployment::$deployment_features_options_new)
				),
				new CheckboxSetField(
					'DeploymentTools',
					'What tools are you using to deploy/configure your cluster?',
					ArrayUtils::AlphaSort(Deployment::$deployment_tools_options)
				),
				new TextField('OtherDeploymentTools', 'Other tools'),
				new CheckboxSetField(
					'OperatingSystems',
					'What is the main Operating System you are using to run your OpenStack cloud?',
					ArrayUtils::AlphaSort(Deployment::$operating_systems_options)
				),
				new TextField('OtherOperatingSystems', 'Other Operating System'),
				new LiteralField('Break', '<p>Please provide the following information about the
        size and scale of this OpenStack deployment. This information is optional, but will
        be kept confidential and never published in connection with your organization.</p>'),
				new LiteralField('Break', '<p><strong>If using OpenStack Compute, what’s the size of your cloud?</strong></p>'),
				new DropdownField(
					'ComputeNodes',
					'Physical compute nodes',
					Deployment::$compute_nodes_options
				),
				new DropdownField(
					'ComputeCores',
					'Processor cores',
					Deployment::$compute_cores_options
				),
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
				new LiteralField('Break', '<p><strong>If using OpenStack Object Storage, what’s the size of your cloud?</strong></p>'),
				new DropdownField(
					'ObjectStorageSize',
					'Total storage in terabytes',
					Deployment::$storage_size_options
				),
				new DropdownField(
					'ObjectStorageNumObjects',
					'Total objects stored',
					Deployment::$stoage_objects_options
				),
				new DropdownField(
					'SwiftGlobalDistributionFeatures',
					'Are you using Swift\'s global distribution features?',
					ArrayUtils::AlphaSort(Deployment::$swift_global_distribution_features_options, array('unspecified' => '-- Select One --'))
				),

				new DropdownField(
					'SwiftGlobalDistributionFeaturesUsesCases',
					'If yes, what is your use case?',
					ArrayUtils::AlphaSort(Deployment::$swift_global_distribution_features_uses_cases_options, array('unspecified' => '-- Select One --'))
				),
				new TextField('OtherSwiftGlobalDistributionFeaturesUsesCases', ''),

				new DropdownField(
					'Plans2UseSwiftStoragePolicies',
					'Do you have plans to use Swift\'s storage policies or erasure codes in the next year?',
					ArrayUtils::AlphaSort(Deployment::$plans_2_use_swift_storage_policies_options, array('unspecified' => '-- Select One --'))
				),
				new TextField('OtherPlans2UseSwiftStoragePolicies', ''),

				new DropdownField(
					'NetworkNumIPs',
					'If using OpenStack Network, what’s the size of your cloud by number of fixed/floating IPs?',
					Deployment::$network_ip_options
				),
				new CheckboxSetField(
					'UsedDBForOpenStackComponents',
					'What database do you use for the components of your OpenStack cloud?',
					ArrayUtils::AlphaSort(Deployment::$used_db_for_openstack_components_options, null, array('Other' => 'Other (Specify)'))
				),
				new TextField('OtherUsedDBForOpenStackComponents', ''),
				new CheckboxSetField(
					'ToolsUsedForYourUsers',
					'What tools are you using charging or show-back for your users?',
					ArrayUtils::AlphaSort(Deployment::$tools_used_for_your_users_options, null, array('Other' => 'Other (Specify)'))
				),
				new TextField('OtherToolsUsedForYourUsers', ''),
				new TextareaField('Reason2Move2Ceilometer', 'If you are not using Ceilometer, what would allow you to move to it (optional free text)?')
			)
		);
		return $fields;
	}

	function getCMSValidator()
	{
		return $this->getValidator();
	}

	function getValidator()
	{
		// Create Validators
		$validator = new RequiredFields('Label',
			'IsPublic',
			'ProjectsUsed',
			'NumCloudUsers',
			'CurrentReleases',
			'DeploymentStage',
			'DeploymentType');
		return $validator;
	}

	public function getCountry()
	{
		return $this->DeploymentSurvey()->PrimaryCountry;
	}

	public function getIndustry()
	{
		return $this->DeploymentSurvey()->Industry;
	}

	public function getMember()
	{
		return $this->DeploymentSurvey()->Member();
	}

	public function getOrg()
	{
		return $this->Org()->Name;
	}

	public function OrgAndLabel()
	{
		return $this->getOrg() . ' - ' . $this->Label;
	}

	public function hasUserStory()
	{
		$userStory = UserStory::get()->filter('DeploymentID', $this->ID)->first();
		return ($userStory) ? true : false;
	}

	public function getUserStory()
	{
		if ($this->hasUserStory()) {
			return UserStory::get()->filter('DeploymentID', $this->ID)->first();
		}
		return false;
	}

	/**
	 * @param int $batch_size
	 * @return mixed
	 */
	public function getNotDigestSent($batch_size)
	{
		Deployment::get()->filter('SendDigest', 0)->sort('UpdateDate', 'ASC')->limit($batch_size);
	}

	public static $deployment_type_options = array(
		'unspecified' => '-- Select One --',
		'On-Premise Private Cloud' => 'On-Premise Private Cloud',
		'Hosted Private Cloud' => 'Hosted Private Cloud',
		'Public Cloud' => 'Public Cloud',
		'Hybrid Cloud' => 'Hybrid Cloud',
		'Community Cloud' => 'Community Cloud'
	);

	public static $projects_used_options = array(
		'Openstack Compute (Nova)' => 'Openstack Compute (Nova)',
		'Openstack Block Storage (Cinder)' => 'Openstack Block Storage (Cinder)',
		'Openstack Object Storage (Swift)' => 'Openstack Object Storage (Swift)',
		'Openstack Network' => 'Openstack Network (Neutron)',
		'Openstack Dashboard (Horizon)' => 'Openstack Dashboard (Horizon)',
		'Openstack Identity Service (Keystone)' => 'Openstack Identity Service (Keystone)',
		'Openstack Image Service (Glance)' => 'Openstack Image Service (Glance)',
		'Heat' => 'OpenStack Orchestration (Heat)',
		'Ceilometer' => 'OpenStack Telemetry (Ceilometer)',
		'OpenStack Bare Metal (Ironic)' => 'OpenStack Bare Metal (Ironic)',
		'OpenStack Database as a Service (Trove)' => 'OpenStack Database as a Service (Trove)',
		'OpenStack Data Processing (Sahara)' => 'OpenStack Data Processing (Sahara)'
	);

	public static $current_release_options = array(
		'Trunk' => 'Trunk / Continuous deployment',
		'Icehouse (2014.1)' => 'Icehouse (2014.1)',
		'Havana (2013.2)' => 'Havana (2013.2)',
		'Grizzly' => 'Grizzly (2013.1)',
		'Folsom (2012.2)' => 'Folsom (2012.2)',
		'Essex (2012.1)' => 'Essex (2012.1)',
		'Diablo (2011.3)' => 'Diablo (2011.3)',
		'Cactus (2011.2)' => 'Cactus (2011.2)',
		'Bexar (2011.1)' => 'Bexar (2011.1)',
		'Austin (2010.1)' => 'Austin (2010.1)'
	);

	public static $stage_options = array(
		'' => '-- Select One --',
		'Proof of Concept' => 'Proof of Concept',
		'Under development/in testing' => 'Under development/in testing',
		'Production' => 'Production'
	);

	public static $num_cloud_users_options = array(
		'Prefer not to say' => 'Prefer not to say',
		'1-100 users' => '1-100 users',
		'101-1,000 users' => '101-1,000 users',
		'1,001-5,000 users' => '1,001-5,000 users',
		'5,001-10,000 users' => '5,001-10,000 users',
		'10,001-50,000 users' => '10,001-50,000 users',
		'50,001-100,000 users' => '50,001-100,000 users',
		'More than 100,000 users' => 'More than 100,000 users'
	);

	public static $workloads_description_options = array(
		'Virtual Desktops' => 'Virtual Desktops',
		'HPC' => 'High Throughput Computing/Batch System/HPC',
		'Public Hosting' => 'Public Hosting',
		'Web Services' => 'Web Services',
		'Data Mining/Big Data/Hadoop' => 'Data Mining/Big Data/Hadoop',
		'Storage/Backup' => 'Storage/Backup',
		'QA/Test Environment' => 'QA/Test Environment',
		'Continuous integration/Automated Testing workflows' => 'Continuous integration/Automated Testing workflows',
		'Bio/Medical Applications' => 'Bio/Medical Applications',
		'Mobile Applications' => 'Mobile Applications',
		'Network Applications' => 'Network Applications',
		'Geographical Information Systems (GIS)' => 'Geographical Information Systems (GIS)',
		'File Sharing' => 'File Sharing',
		'CDN/Video Streaming' => 'CDN/Video Streaming',
		'Education/MOOC' => 'Education/MOOC',
		'Enterprise Applications' => 'Enterprise Applications',
		'Databases' => 'Databases',
		'Benchmarks/Stress Testing' => 'Benchmarks/Stress Testing',
		'Research' => 'Research',
		'Management and Monitoring Systems' => 'Management and Monitoring Systems',
		'Games/Online Games' => 'Games/Online Games',
		'Up to the user' => 'It’s up to the user',

	);


	public static $api_options = array(
		'XML' => 'XML',
		'JSON' => 'JSON'
	);

	public static $hypervisors_options = array(
		'kvm' => 'KVM',
		'xen' => 'Xen / XCP',
		'xenserver' => 'Citrix XenServer',
		'hyperv' => 'Microsoft HyperV',
		'esx' => 'VMware ESX',
		'lxc' => 'LXC',
		'QEMU' => 'QEMU',
		'PowerKVM' => 'PowerKVM',
		'Bare Metal' => 'Bare Metal',
		'OpenVZ' => 'OpenVZ',
		'Docker' => 'Docker'
	);

	public static $block_storage_divers_options = array(
		'Ceph RBD' => 'Ceph RBD',
		'Coraid' => 'Coraid',
		'Dell EqualLogic' => 'Dell EqualLogic',
		'EMC' => 'EMC',
		'GlusterFS' => 'GlusterFS',
		'HDS' => 'HDS',
		'HP 3PAR' => 'HP 3PAR',
		'HP LeftHand' => 'HP LeftHand',
		'Huawei' => 'Huawei',
		'Storwize' => 'IBM Storwize',
		'XIV' => 'IBM XIV/DS8000',
		'IBM GPFS' => 'IBM GPFS',
		'LVM' => 'LVM (default)',
		'Mellanox' => 'Mellanox',
		'NetApp' => 'NetApp',
		'Nexenta' => 'Nexenta',
		'NFS' => 'NFS',
		'SAN/Solaris' => 'SAN/Solaris',
		'Scality' => 'Scality',
		'Sheepdog' => 'Sheepdog',
		'SolidFire' => 'SolidFire',
		'Windows' => 'Windows Server 2012',
		'Xenapi' => 'Xenapi NFS',
		'XenAPI Storage Manager' => 'XenAPI Storage Manager',
		'Zadara' => 'Zadara',
		'IBM NAS' => 'IBM NAS',
		'ProphetStor' => 'ProphetStor',
		'VMWare VMDK' => 'VMWare VMDK',
	);

	public static $network_driver_options = array(
		'nova-network' => 'nova-network',
		'Big Switch' => 'Big Switch',
		'Brocade' => 'Brocade',
		'Cisco' => 'Cisco UCS/Nexus',
		'Embrane' => 'Embrane',
		'Extreme Networks' => 'Extreme Networks',
		'Hyper-V' => 'Hyper-V',
		'Juniper' => 'Juniper',
		'Linux Bridge' => 'Linux Bridge',
		'Mellanox' => 'Mellanox',
		'MidoNet' => 'MidoNet',
		'Modular Layer 2 Plugin' => 'Modular Layer 2 Plugin (ML2)',
		'NEC' => 'NEC OpenFLow',
		'Nicira' => 'Nicira NVP',
		'Open vSwitch' => 'Open vSwitch',
		'PLUMgrid' => 'PLUMgrid',
		'Ruijie Networks' => 'Ruijie Networks',
		'Ryu' => 'Ryu OpenFlow Controller',
		'A10 Networks' => 'A10 Networks',
		'Arista' => 'Arista',
		'IBM SDN-VE' => 'IBM SDN-VE',
		'Meta Plugin' => 'Meta Plugin',
		'Nuage Networks' => 'Nuage Networks',
		'One Convergence NVSD' => 'One Convergence NVSD',
		'OpenDaylight' => 'OpenDaylight',
		'VMWare NSX (formerly Nicira NVP)' => 'VMWare NSX (formerly Nicira NVP)',
	);

	public static $identity_driver_options = array(
		'LDAP' => 'LDAP',
		'AD' => 'Active Directory',
		'SQL' => 'SQL',
		'PAM' => 'PAM',
		'KVS' => 'KVS',
		'Templated' => 'Templated'
	);

	public static $deployment_features_options = array(
		'Dashboard' => 'Dashboard',
		'Object storage' => 'Object storage',
		'Live migration' => 'Live migration',
		'Snapshotting to new images' => 'Snapshotting to new images',
		'EC2 compatibility API' => 'EC2 compatibility API',
		'S3 compatibility API' => 'S3 compatibility API',
		'OCCI compatibility API' => 'OCCI compatibility API',
		'GCE compatibility API' => 'GCE compatibility API'
	);

	public static $deployment_features_options_new = array(
		'EC2 compatibility API' => 'EC2 compatibility API',
		'S3 compatibility API' => 'S3 compatibility API',
		'OCCI compatibility API' => 'OCCI compatibility API',
		'GCE compatibility API' => 'GCE compatibility API'
	);

	public static $deployment_tools_options = array(
		'DevStack' => 'DevStack',
		'Chef' => 'Chef',
		'Crowbar' => 'Crowbar',
		'PackStack' => 'PackStack',
		'Puppet' => 'Puppet',
		'SaltStack' => 'SaltStack',
		'Ansible' => 'Ansible',
		'CFEngine' => 'CFEngine',
		'Juju' => 'Juju',
	);

	public static $operating_systems_options = array(
		'CentOS' => 'CentOS',
		'Debian' => 'Debian',
		'openSUSE' => 'openSUSE',
		'Red Hat Enterprise Linux' => 'Red Hat Enterprise Linux',
		'SUSE Linux Enterprise' => 'SUSE Linux Enterprise',
		'Ubuntu' => 'Ubuntu',
		'Windows' => 'Windows',
		'Fedora' => 'Fedora',
		'Scientific Linux' => 'Scientific Linux',

	);

	public static $compute_nodes_options = array(
		'unspecified' => '-- Select One --',
		'1-50 nodes' => '1-50 nodes',
		'51-100 nodes' => '51-100 nodes',
		'101-500 nodes' => '101-500 nodes',
		'501-1,000 nodes' => '501-1,000 nodes',
		'More than 1,000 nodes' => '1,001-5,000 nodes',
		'More than 5,000 nodes' => 'More than 5,000 nodes'
	);


	public static $compute_cores_options = array(
		'unspecified' => '-- Select One --',
		'1-100 cores' => '1-100 cores',
		'101-500 cores' => '101-500 cores',
		'501-1,000 cores' => '501-1,000 cores',
		'1,001-5,000 cores' => '1,001-5,000 cores',
		'5,001-10,000 cores' => '5,001-10,000 cores',
		'More than 10,000 cores' => '10,001-50,000 cores',
		'More than 50,000 cores' => 'More than 50,000 cores'
	);

	public static $compute_instances_options = array(
		'unspecified' => '-- Select One --',
		'1-100 instances' => '1-100 instances',
		'101-500 instances' => '101-500 instances',
		'501-1,000 instances' => '501-1,000 instances',
		'1,001-5,000 instances' => '1,001-5,000 instances',
		'5,000-10,000 instances' => '5,000-10,000 instances',
		'More than 10,000 instances' => 'More than 10,000 instances'
	);

	public static $storage_size_options = array(
		'unspecified' => '-- Select One --',
		'0-10 TB' => '0-10 TB',
		'11-100 TB' => '11-100 TB',
		'100-500 TB' => '100-500 TB',
		'More than 500 TB' => '501-1,000 TB',
		'1PB-5PB' => '1PB-5PB',
		'5PB-20PB' => '5PB-20PB',
		'over 20PB' => 'over 20PB',
	);

	public static $stoage_objects_options = array(
		'unspecified' => '-- Select One --',
		'1-10,000 objects' => '1-10,000 objects',
		'10,001-100,000 objects' => '10,001-100,000 objects',
		'100,001 to 1 million objects' => '100,001 to 1 million objects',
		'1 million to 100 million objects' => '1 million to 100 million objects',
		'100 million to 500 million objects' => '100 million to 500 million objects',
		'More than 500 million objects' => 'More than 500 million objects'
	);

	public static $network_ip_options = array(
		'unspecified' => '-- Select One --',
		'less than 100' => 'less than 100',
		'101 to 1,000' => '101 to 1,000',
		'1,001 to 10,000' => '1,001 to 10,000',
		'More than 10,000' => 'More than 10,000'
	);


	public static $why_nova_network_options = array(
		'Complexity of Neutron' => 'Complexity of Neutron',
		'Scalability, Performance' => 'Scalability, Performance',
		'Migration effort' => 'Migration effort',
	);

	public static $swift_global_distribution_features_options = array(
		'Yes, globally distributed clusters' => 'Yes, globally distributed clusters',
		'Yes, container sync' => 'Yes, container sync',
		'No, this Swift cluster is only in a single region' => 'No, this Swift cluster is only in a single region',
	);

	public static $swift_global_distribution_features_uses_cases_options = array(
		'Disaster recovery, continuity of business, or regulatory reasons' => 'Disaster recovery, continuity of business, or regulatory reasons',
		'Locality of access' => 'Locality of access',
		'Other' => 'Other (specify)',
	);

	public static $plans_2_use_swift_storage_policies_options = array(
		'Yes' => 'Yes',
		'No' => 'No',
		'Maybe. Please explain' => 'Maybe. Please explain',
	);

	public static $used_db_for_openstack_components_options = array(
		'MySQL' => 'MySQL',
		'Percona Server' => 'Percona Server',
		'MariaDB' => 'MariaDB',
		'MySQL with Galera' => 'MySQL with Galera',
		'MySQL with DRBD' => 'MySQL with DRBD',
		'Percona XtraDB Cluster' => 'Percona XtraDB Cluster',
		'MariaDB Galera Cluster' => 'MariaDB Galera Cluster',
		'PostgreSQL' => 'PostgreSQL',
		'MongoDB' => 'MongoDB',
		'SQLite' => 'SQLite',

	);

	public static $tools_used_for_your_users_options = array(
		'None' => 'None',
		'Home grown tools using ceilometer' => 'Home grown tools using ceilometer',
		'Home grown tools using other OpenStack components than ceilometer' => 'Home grown tools using other OpenStack components than ceilometer',
		'Cloud Kitty' => 'Cloud Kitty',

	);
	
}