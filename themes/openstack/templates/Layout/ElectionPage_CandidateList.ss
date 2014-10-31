<h1>{$Title}: Candidate List</h1>

<% if ElectionIsActive %>

<div class="siteMessage" id="InfoMessage">
<p><strong>HOW TO VOTE</strong></p>
<p>If you are an eligible voter, you should have received an email with the subject <strong>"OpenStack Foundation - 2014 Individual Director Election"</strong> from secretary@openstack.org. This email includes your unique voting link. If you did not receive an email, please contact <a href="mailto:secretary@openstack.org">secretary@openstack.org</a>.</p>
</div>

<% end_if %>


<div class="span-16">

<% if AcceptedCandidatesList %>

<h2>Candidates On The Ballot</h2>


		<p>The candidates on this list have the 10 nominations required to be on the election ballot and have completed the application.</p>


	<% loop AcceptedCandidatesList %>

		<% if MoreThanTen %>

			<% include CandidateDisplay %>

		<% end_if %>

	<% end_loop %>

<% else %>

	<p>There are not yet any candidates for this election.</p>

<% end_if %>

<p></p>

<% if NominationsAreOpen %>

	<h2>Candidates Not Yet On The Ballot</h2>

	<p>The candidates on this list have been nominated but do not yet have the 10 nominations required to appear on the ballot. If you don't see a member you nominated, they may still need to complete the application and accept the nomination.</p>

	<% loop AcceptedCandidatesList %>

		<% if countNominations %>

			<% if LessThanTen %>

				<% include CandidateDisplay %>

			<% end_if %>

		<% end_if %>

	<% end_loop %>

<% end_if %>


</div>

<% include ElectionSideMenu %>

