<div class="container">

$SetCurrentTab(1)
<% require javascript(framework/thirdparty/tinymce/tiny_mce.js) %>
<% require javascript(themes/openstack/javascript/simple-tinymce.js) %>
<% require themedCSS(profile-section) %>

	<h1>$Title</h1>
	<% if Success %>
		<% if CurrentMember %>
	    <div class="span-24 last siteMessage" id="SuccessMessage">
	        <p>Your application has been received!  Please Edit your profile now.</p>
	    </div>
		<p>Your details are as follows: </p>
		<% with CurrentMember %>
			<p>
				<strong>Name</strong>: $Name<br />
				<strong>Email</strong>: $Email<br />
			</p>
		<% end_with %>
		<a href="$Link" class="roundedButton">Edit Profile</a>
		<% include LegalAgreements %>
		<% end_if %>
	<% else %>
		<% if CurrentMember %>
			<% if Saved %>
                <div class="span-24 last siteMessage" id="SuccessMessage">
				    <p>Your Profile has been saved!</p>
				</div>
			<% end_if %>
			<% if Error %>
			    <div class="span-24 last siteMessage" id="ErrorMessage">
			    <p>There was a problem saving your profile. Please look for errors below.</p>
				</div>
			<% end_if %>
			<% include ProfileNav %>
			<div class="profile-appearance">See how your <a href="/community/members/profile/{$CurrentMember.ID}">public profile</a> appears.</div>
			$EditProfileForm
            <div id="affiliation-edition-dialog">
                $AffiliationEditForm
            </div>
			<% include CompanyAdmin %>
		</div> <!-- Close logged in box -->
		<% else %>
			<p>In order to edit your community profile, you will first need to <a href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>. Don't have an account? <a href="/join/">Join The Foundation</a></p>
			<p><a class="roundedButton" href="/Security/login/?BackURL=%2Fprofile%2F">Login</a> <a href="/join/" class="roundedButton">Join The Foundation</a></p>
		<% end_if %>
	<% end_if %>
</div>
