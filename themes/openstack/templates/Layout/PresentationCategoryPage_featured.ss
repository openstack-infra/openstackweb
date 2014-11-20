<% require themedCSS(videos) %>

<% loop Presentation %>

<div class="main-video-wrapper">
	<iframe width="853" height="480" src="//www.youtube.com/embed/{$YouTubeID}?rel=0<% if Top.Autoplay %>&autoplay=1<% end_if %>" frameborder="0" allowfullscreen></iframe>
</div>

<div class="container single-video-details">
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-3 video-share">
			<a href="https://twitter.com/share" data-related="jasoncosta" data-lang="en" data-size="large" data-count="none"><i class="fa fa-twitter"></i></a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
				<h3>$Name</h3>
		</div>
	</div>

</div>
<% end_loop %>


<% include VideoThumbnails %>
