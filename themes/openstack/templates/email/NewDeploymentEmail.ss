<% with Deployment %>
    <h1>New Deployment - $Label </h1>
    <h2>Main Info</h2>
    <ul>
        <li><b>Deployment Name</b> $Label</li>
        <li><b>Would you like to keep this information confidential or allow the Foundation to share information about this deployment publicly?</b> $IsPublic</li>
        <li><b>Organization</b> $OrgID</li>
        <li><b>Deployment Type</b> $DeploymentType</li>
        <li><b>Projects Used</b> $ProjectsUsed</li>
        <li><b>What releases are you currently using?</b> $CurrentReleases </li>
        <li><b>In what stage is your OpenStack deployment? (make a new deployment profile for each type of deployment)</b> $DeploymentStage </li>
        <li><b>What's the size of your cloud by number of users?</b> $NumCloudUsers</li>
        <li><b>Describe the workloads or applications running in your Openstack environment. (choose any that apply)</b> $WorkloadsDescription</li>
        <li><b>Other workloads or applications running in your Openstack environment. (optional)</b> $OtherWorkloadsDescription</li>
    </ul>
    <h2>Details</h2>
    <ul>
        <li><b>If you are using OpenStack Compute, which hypervisors are you using?</b> $Hypervisors</li>
        <li><b>Other Hypervisor</b> $OtherHypervisor</li>
        <li><b>Do you use nova-network, or OpenStack Network (Neutron)? If you are using OpenStack Network (Neutron), which drivers are you using?</b> $NetworkDrivers</li>
        <li><b>Other Network Driver</b> $OtherNetworkDriver</li>
        <li><b>If you are using nova-network and not OpenStack Networking (Neutron), what would allow you to migrate? (optional)</b> $WhyNovaNetwork $OtherWhyNovaNetwork</li>
        <li><b>If you are using OpenStack Block Storage, which drivers are you using?</b> $BlockStorageDrivers</li>
        <li><b>Other Block Storage Driver</b> $OtherBlockStorageDriver</li>
        <li><b>If you are using OpenStack Identity which OpenStack Identity drivers are you using?</b> $IdentityDrivers</li>
        <li><b>Other/Custom Identity Driver</b> $OtherIndentityDriver</li>
        <li><b>Which of the following compatibility APIs does/will your deployment support?</b> $SupportedFeatures</li>
        <li><b>What tools are you using to deploy/configure your cluster?</b> $DeploymentTools</li>
        <li><b>Other tools</b> $OtherDeploymentTools</li>
        <li><b>What is the main Operating System you are using to run your OpenStack cloud</b> $OperatingSystems</li>
        <li><b>Other Operating Systems</b> $OtherOperatingSystems</li>
        <li><b>Physical compute nodes</b> $ComputeNodes</li>
        <li><b>Processor cores</b> $ComputeCores</li>
        <li><b>Number of instances</b> $ComputeInstances</li>
        <li><b>If using OpenStack Block Storage, what’s the size of your cloud by total storage in terabytes?</b> $BlockStorageTotalSize</li>
        <li><b>Total storage in terabytes</b> $ObjectStorageSize</li>
        <li><b>Total objects stored</b> $ObjectStorageNumObjects</li>
        <li><b>Are you using Swift\'s global distribution features?</b> $SwiftGlobalDistributionFeatures</li>
        <li><b>If yes, what is your use case?</b>$SwiftGlobalDistributionFeaturesUsesCases $OtherSwiftGlobalDistributionFeaturesUsesCases</li>
        <li><b>Do you have plans to use Swift\'s storage policies or erasure codes in the next year?</b> $Plans2UseSwiftStoragePolicies $OtherPlans2UseSwiftStoragePolicies</li>
        <li><b>If using OpenStack Network, what’s the size of your cloud by number of fixed/floating IPs</b> $NetworkNumIPs</li>
        <li><b>What database do you use for the components of your OpenStack cloud?</b> $UsedDBForOpenStackComponents $OtherUsedDBForOpenStackComponents</li>
        <li><b>What tools are you using charging or show-back for your users?</b> $ToolsUsedForYourUsers $OtherToolsUsedForYourUsers</li>
        <li><b>If you are not using Ceilometer, what would allow you to move to it (optional free text)?</b>Reason2Move2Ceilometer</li>
    </ul>
<% end_with %>
