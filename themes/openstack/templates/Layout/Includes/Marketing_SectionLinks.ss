<div class="promoted-content span-16">
	<% loop LatestSectionLinks %>
		<% if First %>
			<div class="span-8">
		<% end_if %>
		<a href="$Link" target="_top">
			$Preview
		</a>
		<% if Mid %>
			</div><div class="span-8 last">
		<% end_if %>
		<% if Last %>
			</div>
		<% end_if %>
	<% end_loop %>
</div>