		<div class="candidate">
			<div class="span-2">
				<% loop Member %>
					<% if Photo.Exists %>
						<a href="/community/members/profile/{$ID}">$Photo.SetWidth(50)</a> <p>&nbsp</p>
					<% else %>
						<a href="/community/members/profile/{$ID}"><img src="/themes/openstack/images/generic-profile-photo-small.png"></a><p>&nbsp;</p>
					<% end_if %>
				<% end_loop %>
			</div>
			<div class="span-14 last">
				<h4><a href="/community/members/profile/{$Member.ID}">$Member.Name</a></h4>
				<p class="nominations">
					<strong>Nominated by: </strong><% loop Nominations %><span>$Member.Name</span> <% end_loop %>
				</p>
<% if Member.Bio %>
<div class="bio">
<strong>About $Member.FirstName $Member.LastName</strong><br/>
$Member.Bio</div>
<% end_if %>
<a href="/community/members/profile/{$Member.ID}">View $Member.Name's full candidate profile and Q&A >></a><br/>
<br/>
			</div>
			<hr/>
		</div>
