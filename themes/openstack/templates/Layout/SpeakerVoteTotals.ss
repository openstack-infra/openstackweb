
	<% if SiteAdmin %>

	<table>
	<% loop SpeakerSubmissions %>

	<tr>
	<td>$MainTopic</td><td>$PresentationTitle</td><td>$FirstName</td><td>$LastName</td><td>$Bio.NoHTML</td><td>$Abstract.NoHTML</td><td>$RatingTotal</td><td>$CountVotes(3)</td><td>$CountVotes(2)</td><td>$CountVotes(1)</td><td>$CountVotes(-1)</td>
	</tr>

	<% end_loop %>
	</table>

	<% end_if %>

