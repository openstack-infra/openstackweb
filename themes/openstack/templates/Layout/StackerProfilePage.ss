<% require themedCSS(conference) %> 

<% loop Parent %>
$HeaderArea
<% end_loop %>

<div class="span-5">
	<p><strong>Design Summit &amp; Conference</strong><br />San Francisco 2012</p>
	<ul class="navigation">
		<% loop Parent %>
			<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>Overview</span></a></li>
		<% end_loop %>
		<% loop Menu(3) %>
		  		<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>$MenuTitle.XML</span></a></li>
	   	<% end_loop %>
	</ul>

	
	<% loop Parent %>
		<% include HeadlineSponsors %>
	<% end_loop %>


</div> 

<!-- Content Area -->

<div class="prepend-1 span-11" id="news-feed">

	<div class="span-18 last">
		<% if IsEditMode %>
			$Content
			$EditProfileForm
		<% else %>
			<h2>Hmm... There appears to be an error.</h2>
			<p>You should have only arrived on this page through a link sent to you in your email. Please check your email for the full link, which will include the token you need to retrieve your registration code.</p>
		<% end_if %>
	</div>
	
</div>