<% require themedCSS(conference) %> 


<div class="container summit">

	$HeaderArea
	
  <div class="row">
		<div class="col-lg-3 col-md-3 col-sm-3">
			<p><strong>The OpenStack Summit</strong><br />$MenuTitle.XML</p>

				<div class="newSubNav">
				    <ul class="overviewNav">

							<li id="$URLSegment"><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>Overview</span> <i class="fa fa-chevron-right"></i></a></li>

				        <% loop Menu(3) %>
				            <li id="$URLSegment"><a href="$Link" title="Go to the &quot;{$Title}&quot; page"  class="$LinkingMode">$MenuTitle <i class="fa fa-chevron-right"></i></a></li>
				        <% end_loop %>
				    </ul>
				</div>
			
			<% include SummitVideos %>
			<% include HeadlineSponsors %>


		</div> 

		<!-- News Feed -->

		<div class="col-lg-7 col-md-7 col-sm-7" id="news-feed">

			<div class="overview">
			$Content
			<p>
			</div>

			<hr />
				<div class="row">
					<div class="col-lg-9 col-md-9 col-sm-9 news-heading">
						<h2>News &amp; Updates</h2>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3">
						<a href="{$Link}rss" class="rss">RSS</a>
					</div>
				</div>
			<hr />

			<% loop NewsItems %>
				<div class="news-item">
				<h2>$Title</h2>
				<p class="post-date">Posted: $Created.Month $Created.DayOfMonth</p>
				$Content
				</div>
			<% end_loop %>

			<!-- Be Excellent -->
			<h3>Reminder: Be Excellent</h3>
			<p>Be excellent to everyone. If you think someone is not being excellent to you at the OpenStack Summit call 512-827-8633 or email <a href="mailto:events@openstack.org">events@openstack.org.</a></p>

		</div>

		<!-- Important Dates -->

		<div class="col-lg-2 col-md-2 col-sm-2" id="important-dates">

			$Sidebar

		</div>
	</div>
</div>

$GATrackingCode