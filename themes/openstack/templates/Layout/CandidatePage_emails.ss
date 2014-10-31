<% require themedCSS(candidate) %>

<h1>$Title</h1>

$Content

<h2>Final List of Nominees</h2>

<% loop Candidates %>
<% if MoreThanTen %>

<hr/>
<div class="candidate">
<div class="span-4">
	$Photo.SetWidth(100)
	<p>&nbsp;</p>
</div>


<div class="details span-20 last">
<div class="last name-and-title"><h3>$FirstName $LastName $Email</h3>

	<% if hasNominated %>
		<strong>(You've Nominated $FirstName)</strong>
	<% end_if %>

<p><strong>Nominated by</strong>: 
<% loop Nominations %>
	<% if Last %>
		$VotingMember.FirstName $VotingMember.Surname
	<% else %>
		$VotingMember.FirstName $VotingMember.Surname,
	<% end_if %>
<% end_loop %>
</p>

<br/>
<% if Company %><p><% if JobTitle %>$JobTitle at<% else %>Employee at<% end_if %> $Company</p><% end_if %>
</div>

<% if Bio %>
<div class="bio">
<strong>About $FirstName $LastName</strong><br />
$Bio</div>
<% end_if %>

<% if TwitterName %>
<div class="span-3"><strong>Twitter Name</strong></div>
<div class="span-17 last"><a href="http://twitter.com/{$TwitterName}">@{$TwitterName}</a> &nbsp;</div>
<% end_if %>

<% if IRCHandle %>
<div class="span-3"><strong>IRC Handle</strong></div>
<div class="span-17 last">$IRCHandle &nbsp;</div>
<% end_if %>

</div>


<div class="span-24 last"><p></p></div>
</div>


<% end_if %>
<% end_loop %>