
<% require themedCSS(member-list) %>

<h1>Individual Member Profile</h1>

<% with Profile %>

<div class="candidate span-14">
<div class="span-4">

	<% if Photo.Exists %>
		$Photo.SetWidth(100) <p>&nbsp</p>
	<% else %>
		<img src="/themes/openstack/images/generic-profile-photo.png"><p>&nbsp;</p>
	<% end_if %>

</div>
<a name="profile-$ID"></a>
<div class="details span-10 last">
<div class="last name-and-title">
<h3>$FirstName $Surname</h3>
</div>
<hr><div class="span-3"><strong>Date Joined</strong></div>
<div class="span-7 last">$Created.Month $Created.format(d), $Created.Year <br><br></div>
<% if OrderedAffiliations %>
    <div class="span-3"><strong>Affiliations</strong></div>
    <div class="span-7 last">
        <% loop OrderedAffiliations %>
            <div>
                <b>$Organization.Name</b> $Duration
            </div>
        <% end_loop %>
    </div>
<% end_if %>
<div class="span-3"><strong>Statement of Interest </strong></div>
<div class="span-7 last">
<p>$StatementOfInterest</p>
</div>

<% if TwitterName %>
<hr><div class="span-3"><strong>Twitter</strong></div>
<div class="span-7 last"><a href="https://twitter.com/{$TwitterName}">@{$TwitterName}</a></div>
<% end_if %>
<% if LinkedInProfile %>
<div class="span-3"><strong>LinkedIn </strong></div>
<div class="span-7 last"><a href="http://linkedin.com/in/{$LinkedInProfile}">@{$LinkedInProfile}</a></div>
<% end_if %>
<% if IRCHandle %>
<div class="span-3"><strong>IRC</strong></div>
<div class="span-7 last">$IRCHandle<br><p>&nbsp;</p>
</div>
<% end_if %>

<% if Bio %>
	<div class="span-3"><strong>Bio</strong></div>
	<div class="span-7 last">$Bio<br><p>&nbsp;</p>
	</div>
<% end_if %>

<% if Projects %>

<hr><div class="span-3"><strong>Projects</strong></div>
<div class="span-7 last">
<p>I'm involved in the following OpenStack projects: $Projects</p>
</div>

<% end_if %>

<p>&nbsp;</p>

<% end_with %>

	<% if Candidate %>
	<% if Candidate.HasAcceptedNomination %>

		<h3>$Profile.FirstName is a candidate in the <% with CurrentElection %> $Title <% end_with %>.</h3>
		<hr/>

		<% if CurrentElection.NominationsAreOpen %>
			<% if Candidate.MoreThanTen %>
				<p>$Top.Profile.FirstName has been nominated enough times to appear on the election ballot. You can read the answers $Top.Profile.FirstName gave to the election questions below.</p>
			<% else %>
				<p>Read the Q&A below and see if you want to <a href="/community/members/confirmNomination/{$Top.Profile.ID}">Nominate $Top.Profile.FirstName</a> in this election.</p>
			<% end_if %>
			<hr/>

		<% end_if %>

		<% loop Candidate %>

		<div class="election-question span-10 last">
			<div class="span-1">Q</div>
			<div class="question span-9 last">
				<h4>What is your relationship to OpenStack, and why is its success important to you? What would you say is your biggest contribution to OpenStack's success to date?</h4>
			</div>
			<div class="span-1">A</div>
			<div class="answer span-9 last">
				$RelationshipToOpenStack
			</div>
		</div>

		<div class="election-question span-10 last">
			<div class="span-1">Q</div>
			<div class="question span-9 last">
				<h4>Describe your experience with other non profits or serving as a board member. How does your experience prepare you for the role of a board member?</h4>
			</div>
			<div class="span-1">A</div>
			<div class="answer span-9 last">
				$Experience
			</div>
		</div>

		<div class="election-question span-10 last">
			<div class="span-10 last">
			<div class="span-1">Q</div>
			<div class="question span-9 last">
				<h4>What do you see as the Board's role in OpenStack's success?</h4>
			</div>
			</div>
			<div class="span-10 last">
			<div class="span-1">A</div>
			<div class="answer span-9 last">
				$BoardsRole
			</div>
			</div>
		</div>

		<div class="election-question span-10 last">
			<div class="span-1">Q</div>
			<div class="question span-9 last">
				<h4>What do you think the top priority of the Board should be in 2014?</h4>
			</div>
			<div class="span-1">A</div>
			<div class="answer span-9 last">
				$TopPriority
			</div>
		</div>


		<% end_loop %>


		<hr/>
		<p><% if Candidate.Nominations %>$Profile.FirstName has already been nominated by:<% end_if %></p>
		<% loop Candidate %>
			<ul>
			<% loop Nominations %>
				<li>$Member.Name</li>
			<% end_loop %>
			</ul>
		<% end_loop %>


	<% end_if %>
	<% end_if %>

</div>


</div>





<div class="span-6 prepend-1 last">
	<% if CurrentElection.NominationsAreOpen %>
		<h2>Election</h2>
		<% loop CurrentElection %>
			<p>Nominations are open for the <a href="$Link">$Title.</a></p>
			<% if CurrentMember %>
					<% if CurrentMember.isFoundationMember %>
						<p><a href="/community/members/confirmNomination/{$Top.Profile.ID}" class="roundedButton">Nominate $Top.Profile.FirstName</a></p>
					<% else %>
						<hr/>
						<p><strong>Your account credentials do not allow you to nominate candidates.</strong></p>
						<p>If you have more than one account on this site, please log out and log back in with the credentials associated with your Foundation Membership</p>
						<p>Have additional questions? Email <a href=“mailto:secretary@openstack.org”>secretary@openstack.org</a></p>
					<% end_if %>
			<% else %>
				<p><a href="/Security/login/?BackURL={$Top.EncodedLink}profile%2F{$Top.Profile.ID}" class="roundedButton">Log In To Nominate</a></p>
			<% end_if %>
		<% end_loop %>
	<% end_if %>
	<% if OwnProfile %>
		<hr/>
		<h2>Your Profile</h2>
		<p><a href="/profile/" class="roundedButton">Edit Your Profile</a></p>
	<% end_if %>
</div>
