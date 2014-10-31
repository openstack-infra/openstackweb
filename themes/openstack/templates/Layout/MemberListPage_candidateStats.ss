
<% loop Candidate %>

	<h2>$Member.Name has $Nominations.Count nomination<% if CountNominations = 1 %><% else %>s<% end_if %>.</h2>

	<ul>
		<% loop Nominations %>
			<li>Nominated by $Member.Name on $Created.Month $Created.format(d), $Created.Year</li>
		<% end_loop %>
	</ul>

	<% if MoreThanTen %>
		<p>This candidate has enough nominations to appaer on the election ballet.</p>
	<% else %>
		<p>This candidate needs at least 10 nominations to appear on the election ballet.</p>	
	<% end_if %>

	<p><a class="roundedButton" href="{$Top.Link}profile/$Member.ID">Done</a> &nbsp; <a class="roundedButton" href="{$Top.Link}">Nominate Someone Else</a></p>

<% end_loop %>