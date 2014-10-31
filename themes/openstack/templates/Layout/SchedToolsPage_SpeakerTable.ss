<div class="container">
<table>
<tr>
	<th>Name</th>
	<th>Email</th>
	<th>Upload Link</th>
</tr>

<% loop ShowSchedSpeakers %>
	<tr>
		<td>$name</td>
		<td>$email</td>
		<td>http://www.openstack.org{$top.link}Presentations/?key={$SpeakerHash}</td>
	</tr>
<% end_loop %>

</table>
</div>
