<% if CurrentMember %>

<% if Success %>

	<h1>Please confirm your nomination</h1>

	<p>Are you sure you would officially like to nominate <strong>$Candidate.FirstName $Candidate.Surname</strong> to the OpenStack Board?</p>

	<p><a class="roundedButton" href="$NominateLink">Yes, Nominate $Candidate.FirstName</a> &nbsp; <a class="roundedButton" href="{$Link}profile/$Candidate.ID">No</a></p>

<% else %>

	<% if NominatedByMe %>
        <% with Candidate %>
              <h1>You have already nominated $FirstName $Surname.</h1>
        <% end_with %>
        <p><a class="roundedButton" href="{$Election.Link}CandidateList/">See Nominations</a> <a class="roundedButton" href="{$Link}">See All Members</a></p>
	<% else_if LimitExceeded %>
		<h1>This candidate has already received 10 nominations.</h1>
		<p>That's all the nominations that are required to appear on the election ballot. You may want to nominate someone else who you think would be a good candidate.</p>
		<p><a class="roundedButton" href="$BackLink">Go Back</a>&nbsp;<a class="roundedButton" href="{$Link}">Nominate Someone Else</a></p>

	<% else %>
		<h1>There was an error nominating this candidate. Please try again.</h1>
		<p><a class="roundedButton" href="$BackLink">Go Back</a></p>

	<% end_if %>

<% end_if %>

<% else %>
	<h1>You are not logged in</h1>
	<p>In order to nominate a candidate, you will first need to <a href="/Security/login/?BackURL={$Top.EncodedLink}">login as a member</a>. Don't have an account? <a href="/join/">Join The Foundation</a></p>
	<p><a class="roundedButton" href="/Security/login/?BackURL={$Top.EncodedLink}">Login</a> <a href="/join/" class="roundedButton">Join The Foundation</a></p>
<% end_if %>

