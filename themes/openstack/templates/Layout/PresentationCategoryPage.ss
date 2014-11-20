<% require themedCSS(videos) %>

<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="eventTitleArea">
				<h1>Paris Summit 2014: Videos</h1>
			</div>
		</div>
	</div>
</div>
<% loop LatestPresentation %>
<div class="main-video-wrapper">
	<a href="{$Top.Link}presentation/{$URLSegment}" class="main-video">
		<div class="video-description-wrapper">
			<div class="video-description">
				<p class="latest-video">Latest Video</p>
				<h3>$Name</h3>
				<p>$FormattedStartTime GMT<p>
				<p>$Description</p>
			</div>
			<div class="play-btn">
				<img id="play" src="//www.openstack.org/themes/openstack/images/landing-pages/auto/play-button.png">
			</div>
		</div>
		<img src="//img.youtube.com/vi/{$YouTubeID}/0.jpg">
	</a>
</div>
<% end_loop %>
<div class="featured-row">
	<div class="container">
		<h2>
			Daily Recaps
			<span>Highlights from the OpenStack Summit in Paris</span>
		</h2>
	</div>
</div>
<div class="container daily-recap-wrapper">
	<div class="row">
		<% loop FeaturedVideos %>

		<!-- If there is a YouTube ID -->

		<% if YouTubeID %>

			<div class="col-lg-3 col-md-3 col-sm-3 video-block">
				<a href="{$Top.Link}featured/{$URLSegment}">
					<div class="video-thumb">
						<div class="thumb-play"></div>
						<img class="video-thumb-img" src="//img.youtube.com/vi/{$YouTubeID}/0.jpg">
					</div>
					<p class="video-thumb-title">
						$Name
					</p>
				</a>
			</div>

		<% else %>


			<div class="col-lg-3 col-md-3 col-sm-3">
				<div class="video-thumb">
					<img class="video-thumb-img" src="/themes/openstack/images/no-video.jpg">
				</div>
				<p class="video-thumb-title">
					Day {$Pos} - Coming Soon
				</p>
			</div>

		<% end_if %>


		<% end_loop %>
	</div>
</div>

<div class="sort-row">
	<div class="container">
		<div class="sort-left">
			<i class="fa fa-th active"></i>
			<i class="fa fa-th-list"></i>
		</div>
		<div class="sort-right">
			<div class="dropdown video-dropdown">
				<a data-toggle="dropdown" href="#">Select A Day <i class="fa fa-caret-down"></i></a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">

				<li role="presentation"><a role="menuitem" tabindex="-1" href="{$Top.Link}#keynotes">Keynote Presentations</a></li>


				<% loop  Presentations.GroupedBy(PresentationDay) %>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="{$Top.Link}#day-{$Pos}">$PresentationDay</a></li>
				<% end_loop %>

				</ul>
			</div>
		</div>
	</div>
</div>

<div class="container">

<div class="row">
	<div class="col-lg-12" id="keynotes">
		<h2 id="keynotes">Keynotes</h2>
	</div>
</div>

<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/openstack-foundation-keynote">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/QOhK0qfiw98/0.jpg">
	</div>
	<p class="video-thumb-title">
	OpenStack Foundation Keynote
	</p>
	<p class="video-thumb-speaker">
	Jonathan Bryce
	</p>
	</a>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/managing-r-and-38-d-externally">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/5BbNwVpi2fY/0.jpg">
	</div>
	<p class="video-thumb-title">
	Managing R&amp;D Externally
	</p>
	<p class="video-thumb-speaker">
	Jim Zemlin
	</p>
	</a>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/bbva-bank-on-openstack">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/PESWFDPbexs/0.jpg">
	</div>
	<p class="video-thumb-title">
	BBVA Bank on OpenStack
	</p>
	<p class="video-thumb-speaker">
	Jose Maria San Jose Juarez
	</p>
	</a>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/private-cloud-openstack-and-the-bmw-datacenter">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/Hk3VNbeftks/0.jpg">
	</div>
	<p class="video-thumb-title">
	Private Cloud, OpenStack, and the BMW Datacenter
	</p>
	<p class="video-thumb-speaker">
	Dr. Stefan Lenz
	</p>
	</a>
	</div>

</div>
<div class="row">

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/standing-up-openstack-at-time-warner-cable">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/gqRkDVOslZ8/0.jpg">
	</div>
	<p class="video-thumb-title">
	Standing Up OpenStack at Time Warner Cable
	</p>
	<p class="video-thumb-speaker">
	Matt Haines
	</p>
	</a>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/superuser-awards">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/_YS5gzWrqnw/0.jpg">
	</div>
	<p class="video-thumb-title">
	Superuser Awards!
	</p>
	<p class="video-thumb-speaker">
	</p>
	</a>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/headline-panel-global-enterprise-it-companies-supporting-openstack">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/rQgQoOVbOR4/0.jpg">
	</div>
	<p class="video-thumb-title">
	Headline Panel: Global Enterprise IT Companies Supporting OpenStack
	</p>
	<p class="video-thumb-speaker">
	Mark McLoughlin,Haiying Wang,Ruchi Bhargava,Mats Karlsson
	</p>
	</a>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/openstack-in-a-hybrid-world">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/flUicBD0peI/0.jpg">
	</div>
	<p class="video-thumb-title">
	OpenStack Keynote: "Distributed"
	</p>
	<p class="video-thumb-speaker">
	Mark Collier
	</p>
	</a>
	</div>

</div>
<div class="row">

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/cern-openstack-user-story">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/7k3VnWXOjP4/0.jpg">
	</div>
	<p class="video-thumb-title">
	CERN OpenStack User Story
	</p>
	<p class="video-thumb-speaker">
	Tim Bell
	</p>
	</a>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/accelerating-innovation-at-expedia-with-openstack">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/6ylRJGQwA3Y/0.jpg">
	</div>
	<p class="video-thumb-title">
	Accelerating Innovation at Expedia with OpenStack
	</p>
	<p class="video-thumb-speaker">
	Rajeev Khanna
	</p>
	</a>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/why-diversity-matters-musings-on-tapjoy-and-39-s-first-year-on-openstack">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/sudJIemM_N8/0.jpg">
	</div>
	<p class="video-thumb-title">
	Why Diversity Matters: Musings on Tapjoy's First Year on OpenStack
	</p>
	<p class="video-thumb-speaker">
	Weston Jossey
	</p>
	</a>
	</div>


	<div class="col-lg-3 col-md-3 col-sm-3 video-block">
	<a href="/summit/openstack-paris-summit-2014/session-videos/presentation/cloud-control-to-major-telco">
	<div class="video-thumb">
	<div class="thumb-play"></div>
	<img class="video-thumb-img" src="//img.youtube.com/vi/uBlE9GatNz4/0.jpg">
	</div>
	<p class="video-thumb-title">
	Cloud Control to Major Telco
	</p>
	<p class="video-thumb-speaker">
	Tobias Ford,Markus Brunner,Xiaolong Kong
	</p>
	</a>
	</div>

</div>

</div>

<% include VideoThumbnails %>
