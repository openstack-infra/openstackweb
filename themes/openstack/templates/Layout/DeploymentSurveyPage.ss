
<h1>OpenStack User Survey</h1>

<div class="container">
	
	<div class="row">
		<div class="lg-col-8">
			<ul class="survey-steps">
				<li><a href="{$Link}OrgInfo">About You</a></li>
				<li><a href="{$Link}AppDevSurvey">Your OpenStack Usage</a></li>
				<li><a href="{$Link}Deployments">Your OpenStack Deployments</a></li>
				<li><a href="{$Link}ThankYou">Thank You!</a></li>
			</ul>
		</div>
		<div class="lg-col-4">
			Logged in as <strong>$CurrentMember.FirstName</strong>. &nbsp; <a href="{$link}logout" class="roundedButton">Log Out</a>
		</div>
	</div>
	
	<div class="row">

$Content
$Form

</div>

</div>
