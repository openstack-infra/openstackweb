<div class="container">
<% loop ShowSchedSpeakers %>

<% if PresentationsForThisSpeaker %>

<ul>

<li>$name - <a href="{$top.link}Presentations/?key={$SpeakerHash}">$SpeakerHash</a>

	<% loop PresentationsForThisSpeaker %>
		<ul><li>$eventtitle</li></ul>
	<% end_loop %>

</li>

</ul>

<% end_if %>

<% end_loop %>
</div>
