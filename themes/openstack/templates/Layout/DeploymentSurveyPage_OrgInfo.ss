<h1>OpenStack User Survey: About Your Organization</h1>

<div class="container">
	
	<div class="row">
		<div class="lg-col-8">
		<ul class="survey-steps">
			<li><a href="{$Link}OrgInfo" class="current">About You</a></li>
			<li><a href="{$Link}AppDevSurvey" class="future">Your OpenStack Usage</a></li>
			<li><a href="{$Link}Deployments" class="future">Your OpenStack Deployments</a></li>
			<li><a href="{$Link}ThankYou" class="future">Thank You!</a></li>
		</ul>
		</div>
		<div class="lg-col-4">
			Logged in as <strong>$CurrentMember.FirstName</strong>. &nbsp; <a href="{$link}logout" class="roundedButton">Log Out</a>
		</div>
	</div>
	
	<div class="row">
		$Form
	</div>
</div>