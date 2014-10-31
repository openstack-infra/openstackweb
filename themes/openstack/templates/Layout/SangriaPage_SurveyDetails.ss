<a href="javascript:window.print()">Print This Page</a>
<h1>Survey # {$ID}</h1>

<hr>
<b>Last Updated:</b>&nbsp;$UpdateDate<br>
<b>Member:</b>&nbsp;$Member.FirstName, $Member.Surname<br>
<b>Email:</b>&nbsp;<a href="mailto:$Member.Email">$Member.Email</a><br>
<b>Title:</b>&nbsp;$Title<br>
<b>Organization:</b>&nbsp;$Org.Name<br>
<b>Industry:</b>&nbsp;$Industry<br>
<b>Other Industry:</b>&nbsp;$OtherIndustry<br>
<b>Primary City:</b>&nbsp;$PrimaryCity<br>
<b>Primary State:</b>&nbsp;$PrimaryState<br>
<b>Primary Country:</b>&nbsp;$PrimaryCountry<br>
<b>Org. Size:</b>&nbsp;$OrgSize<br>
<b>OpenStack Involvement:</b>&nbsp;$OpenStackInvolvement<br>
<b>Information Sources:</b>&nbsp;$InformationSources<br>
<b>Other Information Sources:</b>&nbsp;$OtherInformationSources<br>
<b>Further Enhancement:</b>&nbsp;$FurtherEnhancement<br>
<b>Foundation User Committee Priorities:</b>&nbsp;$FoundationUserCommitteePriorities<br>
<b>Business Drivers:</b>&nbsp;$BusinessDrivers<br>
<b>Other Business Drivers:</b>&nbsp;$OtherBusinessDrivers<br>
<b>What Do You Like Most?:</b>&nbsp;$WhatDoYouLikeMost<br>
<b>Is User Group Member?:</b>&nbsp;<% if UserGroupMember %>True<% else %> False <% end_if %> <br>
<% if UserGroupMember %>
<b>User Group Name:</b>&nbsp;$UserGroupName<br>
<% end_if %>
<b>Is Ok To Contact?:</b>&nbsp;<% if OkToContact %>Yes<% else %> No <% end_if %> <br>
<% if Deployments %>
<h3>Deployments</h3>
<hr>
<ul>
<% loop Deployments %>
    <li>
        <a href="$Top.Link(DeploymentDetails)/{$ID}" title="click to see deployment details">$Label</a>
    </li>
<% end_loop %>
</ul>
<% end_if %>