<h1>OpenStack User Survey: Deployments</h1>

<div class="container">
	
	<div class="row">
		<div class="col-lg-8">
		<ul class="survey-steps">
			<li><a href="{$Link}OrgInfo">About You</a></li>
			<li><a href="{$Link}AppDevSurvey">Your OpenStack Usage</a></li>
			<li><a href="{$Link}Deployments" class="current">Your OpenStack Deployments</a></li>
			<li><a href="{$Link}ThankYou" class="future">Thank You!</a></li>
		</ul>
		</div>
		<div class="col-lg-4">
			Logged in as <strong>$CurrentMember.FirstName</strong>. &nbsp; <a href="{$link}logout" class="roundedButton">Log Out</a>
		</div>
	</div>
	
	<div class="row">

<h2>Deployments</h2>
<p>A deployment profile tracks information about an individual OpenStack deployment. An
organization may have multiple deployment profiles for each OpenStack deployment they have.
Once you have created a deployment profile, you can return to the site to
update the data in it. You will be the default administrator for any deployment profiles
you create. As always, the information you provide is confidential and will only be presented
in aggregate unless you consent to making it public.</p>
<% if DeploymentList %>
	<% loop DeploymentList %>
		<div class="deployment">
			<a href="{$Top.Link}DeploymentDetails/?DeploymentID={$ID}" class="deployment-name">$Label</a> &nbsp;  [<a href="{$Top.Link}RemoveDeployment/?DeploymentID={$ID}"> Remove </a>]
		</div>
	<% end_loop %>
	<p></p>
	<p><a class="roundedButton" href="{$Link}AddDeployment">Add Another Deployment</a> &nbsp; <a class="roundedButton" href="{$Link}SkipDeployments">Done</a></p>
<% else %>
	<p>Click "Get Started" below to add optional details about your OpenStack Deployments.</p>
	<p><a class="roundedButton" href="{$Link}AddDeployment">Get Started</a> &nbsp; <a class="roundedButton" href="{$Link}SkipDeployments">Skip This Step</a></p>
<% end_if %>




</div>
</div>
