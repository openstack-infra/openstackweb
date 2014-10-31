<h2>Collateral + Presentations</h2>
<hr>
<div class="item-list">
		<% loop LatestPresentations %>
			<% if First %>
				<div class="span-8">
			<% end_if %>
			
			<div class="item span-8" title="$Name">
				<div class="span-1">
					<a href="$FileURL" target="_blank">
						<img width="22" height="22" src="$Icon" alt="$Name">
					</a>
				</div>
				<div class="span-7 last item-content">
					<a href="$FileURL" target="_blank">$Name</a>
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