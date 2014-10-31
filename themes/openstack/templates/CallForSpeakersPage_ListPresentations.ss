<% require themedCSS(conference) %> 

<% loop AllPresentations %>

	<hr class="talk-divider"/>
	<h2>{$ID}. $PresentationTitle</h2>
	<p>$Created.Nice</p>
	<p><strong>Selected Topics: $Topic</strong></p>

	<div>$Abstract</div>

	<% if Speakers %>
		<% loop Speakers %>
			<hr class="speaker-divider"/>
			<h4>$FirstName $Surname</h4>
			<p>$Bio</p>
		<% end_loop %>
	<% else %>
		<hr class="speaker-divider"/>
		<p>No speakers attached to this presentation.</p>
	<% end_if %>

<% end_loop %>