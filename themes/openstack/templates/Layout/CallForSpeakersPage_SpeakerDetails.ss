<% require themedCSS(conference) %> 

<% with Parent %>
$HeaderArea
<% end_with %>

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
			<h1>Edit The Speaker Details</h1>
			$SpeakerDetailsForm
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