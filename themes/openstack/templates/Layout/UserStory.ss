<% require themedCSS(case-studies) %>

			<div class="span-12">
				<p class="breadcrumb"><a href="$Parent.Link">&laquo; Back to $Parent.Title</a></p>
				<h1>$CaseStudyTitle</h1>
				$CaseStudyBody
				<% if ObjectivesTitle %>
				<h4>$ObjectivesTitle</h4>
				<div class="user-objectives">
					$ObjectivesBody
				</div>
				<% end_if %>
			</div>
			
			<div class="prepend-1 span-11 last">
			<div class="user-photo">
				$CaseStudyImg.SetWidth(410)
			</div>
			
			<h2 class="user-name">$CompanyName</h2>
			<ul>
				<% if CompanyURL %>
				<li><a href="$CompanyURL">$CompanyURL</a></li>
				<% end_if %>
				<li><strong>Industry:</strong> $Industry</li>
				<li><strong>Headquarters:</strong> $Headquarters</li>
				<li><strong>Size:</strong> $OrgSize</li>
			</ul>
			
			<hr />
            <% if Projects %>
			<h4>OpenStack technologies $Title uses: </h4>

			<ul class="user-project-list">
			    <% loop Projects %>
                    <li>$Project</li>
			    <% end_loop %>
			</ul>
            <% end_if %>
			<p></p>	
			
	
			<% if UserStoriesLink %>
			<hr />
			<h4>Links About $Title</h4>
			<ul class="user-links">
				<% loop UserStoriesLink %>
				<li><a href="$LinkURL">$LinkName</a></li>
				<% if Description %>
					<p>$Description</p>
				<% end_if %>
				<% end_loop %>
			</ul>
			<% end_if %>

			<p><a href="{$Link}pdf" class="roundedButton">Download this story as PDF</a></p>
						
			</div>