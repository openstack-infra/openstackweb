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
		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="video-thumb">
				<img class="video-thumb-img" src="/themes/openstack/images/no-video.jpg">
			</div>
			<p class="video-thumb-title">
				Day 1 - Coming Soon
			</p>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="video-thumb">
				<img class="video-thumb-img" src="/themes/openstack/images/no-video.jpg">
			</div>
			<p class="video-thumb-title">
				Day 2 - Coming Soon
			</p>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="video-thumb">
				<img class="video-thumb-img" src="/themes/openstack/images/no-video.jpg">
			</div>
			<p class="video-thumb-title">
				Day 3 - Coming Soon
			</p>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="video-thumb">
				<img class="video-thumb-img" src="/themes/openstack/images/no-video.jpg">
			</div>
			<p class="video-thumb-title">
				Day 4 - Coming Soon
			</p>
		</div>
	</div>
</div>

<% include VideoThumbnails %>
