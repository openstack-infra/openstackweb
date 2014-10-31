<h1>{$Title}: Gold Member Candidate List</h1>

<div class="span-16">

<% if GoldCandidatesList %>

<h2>Gold Director Selector Candidates</h2>


		<p>The candidates on this list are the intended Gold Directors from the Gold Member companies who are running for election as Gold Director Selectors.</p>


	<% loop GoldCandidatesList %>

			<% include CandidateDisplayGold %>

	<% end_loop %>

<% else %>

	<p>There are not yet any candidates for this election.</p>

<% end_if %>

<p></p>


</div>

<% include ElectionSideMenu %>

