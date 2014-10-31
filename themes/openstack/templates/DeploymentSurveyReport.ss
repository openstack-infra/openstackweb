sections:<br/>
<% loop PublicDeployments.GroupedBy(getIndustry) %>
	- name: $getIndustry<br/>
	&nbsp;&nbsp;users:<br/>
        <% loop Children %>
            &nbsp;&nbsp;-<br/>
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;name: $getOrg<br/>
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;country: $getCountry<br/>
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cloudtype: $DeploymentType<br/>
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;version: $CurrentReleases<br/>
        <% end_loop %>
    </ul>
	</div>
<% end_loop %>