<a href="$Top.Link(ViewDeploymentDetails)">Back to list</a>&nbsp;|&nbsp;<a href="javascript:window.print()">Print This Page</a>
<h1>Deployment # {$ID}</h1>
<h2>$Label</h2>
<hr>
<b>Last Updated:</b>&nbsp;$UpdateDate<br>
<b>Organization:</b>&nbsp;$Org.Name<br>
<b>Survey:</b>&nbsp;<a href="$Top.Link(SurveyDetails)/{$DeploymentSurvey.ID}" title="view associated survey"># $DeploymentSurvey.ID</a><br>
<b>Type:</b>&nbsp;$DeploymentType<br>
<b>Country:</b>&nbsp;$CountryCode<br>
<b>Is Public?</b>&nbsp;<% if IsPublic %>True<% else %>False<% end_if %><br>
<b>Industry:</b>&nbsp;$DeploymentSurvey.Industry<br>
<b>Projects Used:</b>&nbsp;$ProjectsUsed<br>
<b>Current Releases:</b>&nbsp;$CurrentReleases<br>
<b>Deployment Stage:</b>&nbsp;$DeploymentStage<br>
<b>Num Cloud Users:</b>&nbsp;$NumCloudUsers<br>
<b>Workloads Description:</b>&nbsp;$WorkloadsDescription<br>
<b>Other Workloads Description:</b>&nbsp;$OtherWorkloadsDescription<br>
<b>APIFormats:</b>&nbsp;$APIFormats<br>
<b>Hypervisors:</b>&nbsp;$Hypervisors<br>
<b>Other Hypervisor:</b>&nbsp;$OtherHypervisor<br>
<b>Block Storage Drivers:</b>&nbsp;$BlockStorageDrivers<br>
<b>Other Block Storage Driver:</b>&nbsp;$OtherBlockStorageDriver<br>
<b>Network Drivers:</b>&nbsp;$NetworkDrivers<br>
<b>Other Network Driver:</b>&nbsp;$OtherNetworkDriver<br>
<b>Why Nova Network:</b>&nbsp;$WhyNovaNetwork<br>
<b>Identity Drivers:</b>&nbsp;$IdentityDrivers<br>
<b>Other Identity Driver:</b>&nbsp;$OtherIndentityDriver<br>
<b>Supported Features:</b>&nbsp;$SupportedFeatures<br>
<b>Deployment Tools:</b>&nbsp;$DeploymentTools<br>
<b>Other Deployment Tools:</b>&nbsp;$OtherDeploymentTools<br>
<b>Operating Systems:</b>&nbsp;$OperatingSystems<br>
<b>Other Operating Systems:</b>&nbsp;$OtherOperatingSystems<br>
<b>Compute Nodes:</b>&nbsp;$ComputeNodes<br>
<b>Compute Cores:</b>&nbsp;$ComputeCores<br>
<b>Compute Instances:</b>&nbsp;$ComputeInstances<br>
<b>Block Storage Total Size:</b>&nbsp;$BlockStorageTotalSize<br>
<b>Object Storage Size:</b>&nbsp;$ObjectStorageSize<br>
<b>Object Storage Num Objects:</b>&nbsp;$ObjectStorageNumObjects<br>
<b>Network Num IPs:</b>&nbsp;$NetworkNumIPs<br>

