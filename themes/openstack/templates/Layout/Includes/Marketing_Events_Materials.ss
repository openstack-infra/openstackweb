<h2>Events Materials</h2>
<hr>
<div class="item-list">
		<% loop LatestEventsMaterial %>
			<% if First %>
				<div class="span-8">
			<% end_if %>
			
			<div class="item span-8">
				<div class="span-1" title="$Name">
					<a href="$FileURL" target="_blank">
						<img width="22" height="22" src="$Icon" alt="$Name">
					</a>
				</div>
				<div class="span-7 last item-content" title="$Name">
					<a href="$FileURL" target="_blank">$FileName</a>
				</div>
			</div>
			<% if Mid %>
				</div><div class="span-8 last">
			<% end_if %>		
			<% if Last %>
				</div>
			<% end_if %>
		<% end_loop %>
</div>