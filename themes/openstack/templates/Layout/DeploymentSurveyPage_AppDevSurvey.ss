<h1>OpenStack User Survey: Deployment Details (Continued)</h1>

<div class="container">
	
	<div class="row">
		<div class="col-lg-8">
		<ul class="survey-steps">
			<li><a href="{$Link}OrgInfo">About You</a></li>
			<li><a href="{$Link}AppDevSurvey" class="current">Your OpenStack Usage</a></li>
			<li><a href="{$Link}Deployments" class="future">Your OpenStack Deployments</a></li>
			<li><a href="{$Link}ThankYou" class="future">Thank You!</a></li>
		</ul>
		</div>
		<div class="col-lg-4">
			Logged in as <strong>$CurrentMember.FirstName</strong>. &nbsp; <a href="{$link}logout" class="roundedButton">Log Out</a>
		</div>
	</div>
	
	<div class="row">
<p>
    The questions on this page are optional, but will help us better understand the details
    of how you are using and interacting with OpenStack. Any information you provide on this step
    will be treated as private and confidential and only used in aggregate reporting.
</p>
<p>
    <strong>If you do not wish to answer these questions, you make <a href="{$Link}Deployments">skip to the next section</a>.</strong>
</p>
$Form
</div>
</div>
