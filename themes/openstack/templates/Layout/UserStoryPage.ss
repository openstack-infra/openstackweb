			<div class="span-12">
			
				<h1>$Title</h1>
				$Content
			</div>
			
			<div class="prepend-1 span-11 last">
			<div class="user-photo">
			<% loop Parent %>
				<% loop Photos %>
					$SetWidth(410)
				<% end_loop %>
			</div>
			
			<h2 class="user-name">$Title</h2>
			<ul>
				<% if URL %>
				<li><a href="$URL">$URL</a></li>
				<% end_if %>
				<% if Industry %>
					<li><strong>Industry:</strong> $Industry</li>
				<% end_if %>
				<% if Headquarters %>
				<li><strong>Headquarters:</strong> $Headquarters</li>
				<% end_if %>
				<% if Size %>
				<li><strong>Size:</strong> $Size</li>
				<% end_if %>
			</ul>
			
			<hr />
			<h4>OpenStack technologies $Title uses: </h4>
			<ul class="user-project-list">
				<% loop Projects %>
					<li>$Name</li>
				<% end_loop %>
			</ul>
			<p></p>	
			
			<% if Attachments %>
			<h4>Downloads</h4>
			<ul class="user-downloads">
				<% loop Attachments %>
				<li><a href="$Link">$Name ($Size)</a></li>
				<% end_loop %>
			</ul>
			<p></p>
			<% end_if %>
			
			<% if Links %>
			<hr />
			<h4>Links About $Title</h4>
			<ul class="user-links">
				<% loop Links %>
				<li>
				<a href="$URL">$Label</a>
				<% if Description %>
					<p>$Description</p>
				<% end_if %>
				</li>
				
				<% end_loop %>
			</ul>
			<% end_if %>
			
			<% end_loop %>
			
			
			</div>