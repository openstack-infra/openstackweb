<% require themedCSS(case-studies) %>

			<div class="span-12">
				<img class="logo" src="{$BASEURL}/themes/openstack/images/open-stack-cloud-computing-logo-2.png" width="167" height="56"/>
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
				<% if Deployment.DeploymentSurvey.Industry %>
				<li><strong>Industry:</strong> $Deployment.DeploymentSurvey.Industry</li>
				<% end_if %>
				<% if Deployment.DeploymentSurvey.PrimaryCity %>
				<li><strong>Headquarters:</strong> $Deployment.DeploymentSurvey.PrimaryCity</li>
				<% end_if %>
				<% if Deployment.DeploymentSurvey.OrgSize %>
				<li><strong>Size:</strong> $Deployment.DeploymentSurvey.OrgSize</li>
				<% end_if %>
			</ul>

            <% if Projects %>
                <hr />
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
				<li><a href="$LinkURL">$LinkName ($LinkURL)</a><% if Description %> - $Description<% end_if %></li>
				<% end_loop %>
			</ul>
			<% end_if %>
						
			</div>