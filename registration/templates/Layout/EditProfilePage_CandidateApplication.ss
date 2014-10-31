<div class="container">
	<% require javascript(sapphire/thirdparty/tinymce/tiny_mce.js) %>
	<% require javascript(themes/openstack/javascript/simple-tinymce.js) %>
	
	<% require themedCSS(profile-section) %>
	
	
	<% if FoundationMember %>
	
		<h1>Accept Nomination</h1>
		<p>To accept nominations and be listed as a candidate for the OpenStack election, please answer the questions below.</p>
		<h2>Candidate Application Form</h2>
		$CandidateApplicationForm
	
	
	<% else %>
		<p>In order to edit your community profile, you will first need to <a href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>. Don't have an account? <a href="/join/">Join The Foundation</a></p>
		<p><a class="roundedButton" href="/Security/login/?BackURL=%2Fprofile%2F">Login</a> <a href="/join/" class="roundedButton">Join The Foundation</a></p>
	<% end_if %>
	</div></div>