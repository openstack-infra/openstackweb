<h1>OpenStack User Survey</h1>

<div class="container">
	
	<div class="row">
		<div class="col-lg-8">
		<ul class="survey-steps">
			<li><a href="{$Link}OrgInfo">About You</a></li>
			<li><a href="{$Link}AppDevSurvey">Your OpenStack Usage</a></li>
			<li><a href="{$Link}Deployments">Your OpenStack Deployments</a></li>
			<li><a href="{$Link}ThankYou" class="current">Thank You!</a></li>
		</ul>
		</div>
		<div class="col-lg-4">
			Logged in as <strong>$CurrentMember.FirstName</strong>. &nbsp; <a href="{$link}logout" class="roundedButton">Log Out</a>
		</div>
	</div>
	
	<div class="row">
    $ThankYouContent

<% if CurrentMember.Password %>
<% else %>
	<h2>Create A Password For Return Visits</h2>
	<p>If you'd like to be able to return and update this information in the future, you
	may choose a password below.</p>
	$SavePasswordForm
<% end_if %>


</div>
</div>

