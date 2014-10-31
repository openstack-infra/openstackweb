<% require themedCSS(speaker-voting-page) %>
<% require javascript(themes/openstack/javascript/speaker-voting-page.js) %>

<% if CurrentMember %>
	<% if HasCompletedVoting %>

		<h1>Thanks, you're all done!</h1>
		<p>We have carefully saved your voting submissions.</p>
		<p><a href="{$Link}reopenvoting/" class="roundedButton">Reopen My Ballot</a></p>

	<% else %>

	<h1>Vote For The Following Presentations</h1>
	<p>Please provide a rating for any presentations you would like to see at <a href="/summit/">OpenStack Summit April 2013 in Portland</a>. You can vote for as many or as few presentations as you'd like.</p>

	<p><strong>A few notes:</strong> When you cast a vote, make sure you see "rating saved." When you are done voting, you can click the button at the very bottom of the page. As long as you have cookies enabled in your browser, you can pause your voting and come back later.</p>

	<hr/>
	<div class="span-24 last topic-container">
	<p><strong>Jump To A Topic:</strong><br/>
	
		<select id="topic-selector">
			<% loop SpeakerSubmissions.GroupedBy(MainTopic) %>
			<option value="$MainTopic">$MainTopic</option>
			<% end_loop %>
		</select>
	</p>
	</div>

	<% loop SpeakerSubmissions.GroupedBy(MainTopic) %>
	<a name="$MainTopic"></a>
	<div class="span-24 groupHeader last"><h2>Topic: $MainTopic</h2></div>

	<% loop Children %>

	<a name="$ID"></a>

	<hr/>
	<div class="span-4">
		$Photo.SetWidth(100)
		<p>&nbsp;</p>
	</div>


	<div class="span-20 last submission" id="$ID">
	<% if PresentationTitle %>
		<h3><a href="{$Top.Link}#{$ID}" class="presentation-title">$PresentationTitle</a></h3>
	<% else %>
		<h3>Title Not Avaialble</h3>
	<% end_if %>
	<h4>$FirstName $LastName &mdash; $JobTitle <% if Company %>at $Company<% end_if %></h4>

	<div class="rating-bar span-20 last">

		<ul>
		<% loop RatingBar %>
			<li class="rt{$Value}"><a class="ratingsButton $Selected" href="{$Top.Link}SaveRating/?id={$ID}&rating={$Value}">$RatingTitle</a></li>
		<% end_loop %>
		</ul>
	</div>
		

	<div class="span-9 last saved-bar">
		<div class="saved-rating" id="saved{$ID}">Your rating was saved.</div>
		<input type="hidden" id="$ID" value="$GetRating" />
	</div>


	<hr />

	<% if SiteAdmin %>
	<div class="span-2"><strong>Email</strong></div>
	<div class="span-18 last">$Email &nbsp;</div>

	<div class="span-2"><strong>Edit Link</strong></div>
	<div class="span-18 last"><a href="{$Top.Parent.Link}call-for-speakers/?edit=1&key={$SpeakerEditHash}">Edit This Submission</a> &nbsp;</div>
	<% end_if %>

	<div class="span-2"><strong>Topic(s)</strong></div>
	<div class="span-18 last">$Topic &nbsp;</div>

	<div class="span-2"><strong>Abstract</strong></div>
	<div class="span-18 last">$Abstract &nbsp;</div>

	<div class="span-2"><strong>Speaker Bio</strong></div>
	<div class="span-18 last">$Bio &nbsp;</div>

	<div class="span-2">&nbsp;</div>
	<div class="span-18 last"><a class="toggleCommentArea">Comment on this entry...</a></div>


	<div class="span-2">&nbsp;</div>
	<div class="span-18 last commentArea">
		<h3>Add a comment here:</h3>
		<textarea id="comment{$ID}" style="height:40px">$GetNote</textarea>
		<p><a href="#" class="roundedButton commentSubmitButton">Save Your Comment</a></p>
	</div>



	</div>




	<div class="span-24 last"><p></p></div>

	<% end_loop %>

	<% end_loop %>

	<hr />

	<div class="span-24 last">
		<p>Are you finished voting? Click the button below to close your ballot.</p> <p><a href="{$Link}completevoting/" class="roundedButton">I'm Done Voting</a></p>
	</div>

	<% end_if %>

<% else %>
	
	<h1>Voting for the 2013 Portland OpenStack Summit Presentations</h1>
	<p>We've received a lot of great speaking submissions, and would like your help shaping the agenda for the next OpenStack Summit.  We've made the submissions public for your input, and you have until Monday, February 25 at 5:59 CT / 23:59 UTC to vote up your favorites.</p>
	<p><strong>Note: you must login as a member of the foundation to vote for presentations.</strong></p>
	<a href="/Security/login?BackURL={$BackLink}" class="roundedButton">Login</a>&nbsp;<a href="/join/" class="roundedButton">Join The Foundation</a></p>

<% end_if %>


