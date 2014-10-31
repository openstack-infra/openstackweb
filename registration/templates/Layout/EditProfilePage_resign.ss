<div class="container">
	<% require themedCSS(profile-section) %>
	
		<h1>Resign Your Membership</h1>
	
		
			<% if CurrentMember %>
				
				<p>Are you sure you want to resign your membership to the OpenStack Foundation? This action will <strong>completely remove your account</strong> and cannot be undone.</p>
				<p><a href="{$Top.Link}resign/?confirmed=1" class="roundedButton">Yes, I want to resign</a> &nbsp; <a href="{$Top.Link}" class="roundedButton">Cancel</a></p>
	
			
	
			<% else %>
				<p>In order to edit your community profile, you will first need to <a href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>. Don't have an account? <a href="/join/">Join The Foundation</a></p>
				<p><a class="roundedButton" href="/Security/login/?BackURL=%2Fprofile%2F">Login</a> <a href="/join/" class="roundedButton">Join The Foundation</a></p>
			<% end_if %>
		</div></div>