<% require themedCSS(presentation) %> 

<h3>OpenStack Summit Presentations</h3>
<hr/>

<p></p>

<div>

	<div class="span-18">
		<h1>"$Presentation.Name"</h1>
		<h4 class="speakers">By: $Presentation.Speakers</h4>

		<% if Presentation.Description %>
		$Presentation.Description
		<% end_if %>

		<hr/>

		<a name="video"></a>
		<h3>Watch Presentation</h3>

        <iframe width="700" height="436" src="//www.youtube.com/embed/{$Presentation.YouTubeID}" frameborder="0" allowfullscreen></iframe>

		<% loop Presentation %>
		<% if SlidesLink %>
			<p></p>
			<hr/>

			<a name="slides"></a>

			<% if EmbedSlides %>
				<h3>View Slides</h3>
				<iframe src="{$SlidesLink}?embedded=true" width="700" height="480" style="border:1px solid #ddd;"></iframe>
			<% else %>
				<h3>Download The Presentation Slides</h3>
				<p><a href="$SlidesLink" class="roundedButton">Download Slides</a></p>
			<% end_if %>
		<% end_if %>
		<% end_loop %>


	</div>

	<div class="prepend-1 span-5 last">
		<div class="share-box">

			<p><strong>Media:</strong><br/>
			<a href="{$Top.Link}presentation/{$Presentation.URLSegment}/#video" class="roundedButton">Video</a> &nbsp;
			<% if Presentation.SlidesLink %>
				<a href="{$Top.Link}presentation/{$Presentation.URLSegment}/#slides" class="roundedButton">Slides</a>
			<% end_if %>
			</p>

			<hr/>

			<p><strong>Share This Presentation:</strong><br/>
			<a href="https://twitter.com/share" class="twitter-share-button" data-related="jasoncosta" data-lang="en" data-size="large" data-count="none">Tweet</a></p>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

			<hr/>

			<p><strong>Discover More Content:</strong><br/>
			<a href="{$Top.Link}?day=$Presentation.Day" class="roundedButton">More Presentations</a></p>

		</div>
	</div>

</div>