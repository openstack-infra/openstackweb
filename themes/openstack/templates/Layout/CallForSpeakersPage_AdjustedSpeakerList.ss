<% loop AdjustedSpeakers %>

	<hr/>

	<p><strong>Speaker</strong></p>
	<p>$FirstName $Surname</p>
	<div>$Bio</div>

	<% with Member %>

	<p><strong>Member</strong></p>
	<p>$FirstName $Surname</p>
	<p>$CurrentOrgTitle</p>
	<p>$Email</p>
	<p>$Bio</p>

	<% end_with %>

	<p><strong>Talks</strong></p>

	<% loop Talks %>

	<p> - $PresentationTitle </p>

	<% end_loop %>

	<p><strong>Admins</strong></p>

	<% loop Talks %>

	<p> - $Owner.Email </p>

	<% end_loop %>


	<hr/>

<% end_loop %>