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
	<% loop Parent %>
		<% include HeadlineSponsors %>
	<% end_loop %>
</div>
<!-- Content Area -->
<div class="prepend-1 span-11" id="news-feed">
	<% if StillUploading %>
	<div class="span-18 last siteMessage" id="SuccessMessage">
		<p>Note: Presentations are still being uploaded. If you do not see the presentation you are looking for, please check back soon.</p>
	</div>

	<% end_if %>	

	<div class="span-18 last">

		<ul class="day-picker">
			<li><a href="{$Link}?day=1" <% if currentDay=1 %>class="selected"<% end_if %> >Day 1</a></li>
			<li><a href="{$Link}?day=2" <% if currentDay=2 %>class="selected"<% end_if %> >Day 2</a></li>
			<li><a href="{$Link}?day=3" <% if currentDay=3 %>class="selected"<% end_if %> >Day 3</a></li>
			<li><a href="{$Link}?day=4" <% if currentDay=4 %>class="selected"<% end_if %> >Day 4</a></li>
		</ul>


		<h1>Videos of Sessions From Day $currentDay</h1>

		<hr/>


		<% if Presentations %>
		<% loop Presentations %>

			<% if DisplayOnSite %>
			<% if YouTubeID %>
			<div class="presentation">

			<div class="span-4"><a href="{$Top.Link}presentation/{$URLSegment}"><img src="$ThumbnailURL" /></a></div>
			<div class="span-14 last">
				<h2><a href="{$Top.Link}presentation/{$URLSegment}">$Title</a></h2>
				<% if Speakers %><p><strong>$Speakers</strong></p><% end_if %>
                $RAW_val(Description)
				<p>
					<a href="{$Top.Link}presentation/{$URLSegment}" class="roundedButton">Watch Now</a>
					<% if SlidesLink %>
						&nbsp;<a href="$SlidesLink" class="roundedButton">Slides</a>
					<% end_if %>
				</p>
			</div>
			<hr/>

			</div>

			<% end_if %>
			<% end_if %>

		<% end_loop %>

		<% else %>
			<p>Sorry, no presentations are available for the day you've selected. Please check back soon.</p>
		<% end_if %>

	</div>

	<ul class="day-picker">
			<li><a href="{$Link}?day=1" <% if currentDay=1 %>class="selected"<% end_if %> >Day 1</a></li>
			<li><a href="{$Link}?day=2" <% if currentDay=2 %>class="selected"<% end_if %> >Day 2</a></li>
			<li><a href="{$Link}?day=3" <% if currentDay=3 %>class="selected"<% end_if %> >Day 3</a></li>
			<li><a href="{$Link}?day=4" <% if currentDay=4 %>class="selected"<% end_if %> >Day 4</a></li>
		</ul>
	
</div>
