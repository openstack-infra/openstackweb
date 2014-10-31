<% require themedCSS(conference) %> 

<% loop Parent %>
$HeaderArea
<% end_loop %>

<div class="span-5">
		<p><strong>The OpenStack Summit</strong><br />$Parent.MenuTitle.XML</p>
	<ul class="navigation">
		<% loop Parent %>
			<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>Overview</span></a></li>
		<% end_loop %>
		<% loop Menu(3) %>
		  		<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>$MenuTitle.XML</span></a></li>
	   	<% end_loop %>
	</ul>

</div> 

<!-- Content Area -->


<div class="prepend-1 span-11" id="news-feed">

	<div class="span-18 last">
		<% if CurrentMember %>
			<% if SpeakersForThisPresentation %>
			<h1>Speakers For This Presentation</h1>
				<ul class="speakers">
				<% loop SpeakersForThisPresentation %>
					<li><a href="{$Top.Link}SpeakerDetails/{$ID}">$FirstName $Surname</a> <a class="delete-button" href="{$Top.Link}DeleteSpeaker/{$ID}/{$Top.TalkID}">Remove</a></li>
				<% end_loop %>
				</ul>
				<hr/>
				<h3>Add Another Speaker</h3>
			<% else %>
				<h1>Add Your First Speaker</h1>
				<p class="siteMessage" id="InfoMessage"><strong>You have not added any speakers to your presentation yet.</strong> You need to add at least one speaker by starting with an email below. (Please make sure to use the Speaker's email address for this section, even if the submission is being managed by a third party.) You will have the opportunity to add multiple speakers if needed.</p>
				<p><strong>Tip: Use your own email here if you are presenting.</strong> You'll be able to provide your bio in the next step.</p>
			<% end_if %>

			$AddSpeakerForm

		<% else %>
			<p>You must be logged in as a member to create or edit speaker submissions.</p>

			<div class="span-9">
			<h3>Already have a login for the OpenStack website?</h3>
			<% include LoginForm %>
			</div>
			
			<div class="span-9 last">
			<h3>Don't have a login? Start here.</h3>
			$RegisterForm
			</div>

		<% end_if %>
	</div>
	
</div>