<% require themedCSS(speaker-voting-page) %>
<% require javascript(themes/openstack/javascript/speaker-voting-page.js) %>


	<h1>Vote For The Following Presentation by $Presentation.FirstName $Presentation.LastName</h1>
	<h3>$Presentation.FirstName would like to speak at the OpenStack Summit. What do you think? </h3>


	<% if CurrentMember %>

	<p><strong>A few notes:</strong> We use these ratings to determine which presentations will be included in the <a href="/summit/">OpenStack Summit.</a>. When you cast a vote, make sure you see "rating saved." When you are done voting, click the button at the very bottom of the page to <a href="{$Link}">vote for more presentations.</a></p>

	<% else %>

	<p><strong>A few notes:</strong> We use these ratings to determine which presentations will be included in the <a href="/summit/">OpenStack Summit.</a>. You must be logged in to vote.</p>
	<a href="/Security/login?BackURL={$BackLink}presentation%2F{$Presentation.ID}" class="roundedButton">Login To Vote</a>&nbsp;<a href="/summit/" class="roundedButton">Summit Details</a></p>

	<% end_if %>

	<% loop Presentation %>
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

	<% if CurrentMember %>
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

	<% end_if %>


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

	<% if CurrentMember %>
	<div class="span-2">&nbsp;</div>
	<div class="span-18 last"><a class="toggleCommentArea">Comment on this entry...</a></div>
	<div class="span-2">&nbsp;</div>
	<div class="span-18 last commentArea">
		<h3>Add a comment here:</h3>
		<textarea id="comment{$ID}" style="height:40px">$GetNote</textarea>
		<p><a href="#" class="roundedButton commentSubmitButton">Save Your Comment</a></p>
	</div>
	<% end_if %>
	</div>
	<div class="span-24 last"><p></p></div>
	<% end_loop %>
	<hr />
	<div class="span-24 last">
		<p><strong>Keep Voting!</strong> Click the button below to vote for more presentations.</p> <p><a href="{$Link}" class="roundedButton">Show Me More Presentations</a></p>
	</div>