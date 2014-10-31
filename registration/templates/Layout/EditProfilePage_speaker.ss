<div class="container">
	
	$SetCurrentTab(7)
	
	<% require themedCSS(profile-section) %>
	
		<h1>$Title</h1>
	
		
			<% if CurrentMember %>
				<% if Saved %>
					
						    <div class="span-24 last siteMessage" id="SuccessMessage">
						        <p>Your Profile has been saved!</p>
						    </div>
	
				
				<% end_if %>
	
				<% include ProfileNav %>
	
	
				$EditSpeakerProfileForm
	
				
	
			<% else %>
				<p>In order to edit your community profile, you will first need to <a href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>. Don't have an account? <a href="/join/">Join The Foundation</a></p>
				<p><a class="roundedButton" href="/Security/login/?BackURL=%2Fprofile%2F">Login</a> <a href="/join/" class="roundedButton">Join The Foundation</a></p>
			<% end_if %>
		</div></div>