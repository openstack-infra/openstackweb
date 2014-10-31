<% require themedCSS(election-page) %>

<div class="span-7 prepend-1 last">

	<h2>Election Details</h2>

	<% if NominationsAreOpen %>
	<p>Nominations for Individual Board Members are now open.</p>
	<% else %>
	<p>Nominations for Individual Board Members have closed.</p>
	<% end_if %>

	<p>
		<ul id="election-nav">
			<li><a href="$Link">Election Details</a></li>
			<li><a href="{$Link}CandidateList">See The Candidates</a></li>
			<% if NominationsAreOpen %>
			<li><a href="/community/members/">Nominate A Member</a></li>
			<% end_if %>
			<% if ElectionIsActive %><% else %>
			<li><a href="/profile/election/">Be A Candidate</a></li>
			<% end_if %>
			<li><a href="{$Link}CandidateListGold">Gold Member Election Candidates</a></li>
			<li><a href="/legal/community-code-of-conduct/">Code of Conduct</a></li>
		</ul>
	</p>

</div>
