<h2>Graphics</h2>
<hr>
<span class="title">$GraphicsNotes</span> 
<div class="item-list">
	<% loop LatestGraphics %> <% if First %>
	<div class="span-16">
		<% end_if %>
		<!--item header -->
		<% if MoreThanValidFiles(1) %> <% if MultipleOf(4) %>
		<div class="item span-4 folder last">
			<% else %>
			<div class="item span-4 folder">
				<% end_if %> <% else %> <% if MultipleOf(4) %>
				<div class="item span-4 last">
					<% else %>
					<div class="item span-4">
						<% end_if %> <% end_if %>
						<!-- Main Picture -->
						<div class="picture" title="$Name">
							<% if MoreThanValidFiles(1) %> 
								<img height="134" width="134" src="$URLPreview" alt="$Name" />
								<p class="caption">$ValidFiles.Count Items</p>
							<% else %> 
								<a href="$FileLink" target="_blank"> 
									<img height="134" width="134" src="$URLPreview" alt="$Name"/>
								</a>
							<% end_if %>
						</div>
						 
						<a href="#">$BannerName</a>
						

						<!--end item header -->

						<% if MoreThanValidFiles(1) %>
						<!--Folder Content-->
						<div class="folder-contents span-16">
							<div class="arrow"></div>
							<div class="header">
								<h3>$Name</h3>
								<a href="$DonwloadAllZip" target="_blank">Download All</a><a
									class="close" href="#">close</a>
							</div>

							<% loop ValidFiles %> <% if First %>
							<div class="span-16">
								<% end_if %>

								<div class="item span-4 <% if MultipleOf(4) %>last<% end_if %>">
									<div class="picture" title="$Name">
										<a href="$Attachment.Link" title="$Name" target="_blank"> <img width="134" height="134"
											src="$URLPreview" alt="$Name">
										</a>
									</div>
									<a href="$Attachment.Link">$Attachment.Name</a>
								</div>

								<!--break line for list item -->
								<% if MultipleOf(4) %>
							</div>
							<div class="span-16"><% end_if %> <% if Last %></div>
							<% end_if %> <% end_loop %>

						</div>
						<!--end Folder Content-->
						<% end_if %>

					</div>

					<!--break line for list item -->
					<% if MultipleOf(4) %>
				</div>
				<div class="span-16">
					<% end_if %>
					<!--end break line for list item -->
					<% if Last %>
				</div>
				<% end_if %> <% end_loop %>
			</div>