		<p></p>
		<hr/>

		<h2>Your Candidate Nominations</h2>
		<% if NominationsByCurrentMember %>
		<p>These are the OpenStack Foundation Individual Members you have nominated in this election.</p>
		<ul class="CandidateNominations">
			<% loop NominationsByCurrentMember %>
				<li>You nominated <strong>$Candidate.FirstName $Candidate.Surname</strong> on {$Created.Month} $Created.format(d), $Created.Year at $Created.Time</li>
			<% end_loop %>
		</ul>
		<% else %>
		<p>You have not nominated any candidates for this election.</p>
		<% end_if %>
