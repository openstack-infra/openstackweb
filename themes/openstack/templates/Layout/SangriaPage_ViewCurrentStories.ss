<style>
.container{
	width:90%;
}
</style>
<h1 style="width:75%;float:left;">User Stories</h1>
<a href="$Top.Link(ViewDeploymentDetails)" class="roundedButton" style="white-space: nowrap;text-align:center;font-weight:normal;float:right;width:20%;margin-bottom:15px">View Deployment List</a>

<hr/>
<% loop UserStoriesIndustries %>
<% if Stories %>
<h3>$IndustryName</h3>
<form method="POST" action="$Top.Link(UpdateStories)" class="UpdateStories">
<table style="border: 1px solid #ccc; border-collapse:collapse;" class="stories">
  <tr>
	<th style="border: 1px solid #ccc;width:1%"></th>
    <th style="border: 1px solid #ccc;width:10%">User Story</th>
    <th style="border: 1px solid #ccc;width:5%">Org</th>
    <th style="border: 1px solid #ccc;width:3%">Country</th>
	<th style="border: 1px solid #ccc;width:15%">Type</th>
	<th style="border: 1px solid #ccc;width:20%">Owner</th>
	<th style="border: 1px solid #ccc;width:10%">Industry</th>
	<th style="border: 1px solid #ccc;width:19%">Video URL</th>
	<th style="border: 1px solid #ccc;width:17%">Admin Panel</th>
  </tr>
  	<tbody>
  	
	<% loop Stories %>
	  <tr>
	  	<td style="border: 1px solid #ccc;font-size:20px;cursor:move" class="dragHandle">≡</td>
		<td style="border: 1px solid #ccc;" class="userStoryTitle">
		<input type="hidden" name="order[$ID]" class="order">
			<% if ShowInAdmin == 1 %><a href="/admin/pages/edit/show/{$ID}?locale=en_US" target="_blank"><% end_if %>
			<span>$Title</span>
			<% if ShowInAdmin == 1 %></a><% end_if %>
			<input type="text" style="display:none;width:100%" value="$Title" name="title[$ID]">
		</td>
		<td style="border: 1px solid #ccc;">$Deployment.Org.Name</td>
		<td style="border: 1px solid #ccc;">$Country</td>
		<td style="border: 1px solid #ccc;">$DeploymentType</td>

		<td style="border: 1px solid #ccc;">
			$Deployment.getMember.getTitle<br/>$Deployment.getMember.Email 
		</td>

		<td style="border: 1px solid #ccc;">
	    	<select name="industry[$ID]">
	    	
	    	<option value="$UserStoriesIndustryID">$UserStoriesIndustry.IndustryName</option>
	    	
	    	<% loop Top.UserStoriesIndustries %>
	    	<!-- Can't access to $Parent to get the CountryIndustry of the Story -->
	    	<option value="$ID">$IndustryName</option>
	    	<% end_loop %>
	    	</select>
	    </td>
		<td style="border: 1px solid #ccc;">
			<input style="width:100%" type="text" name="video[$ID]" value="$Video">
		</td>
	    <td style="border: 1px solid #ccc;">
	    	<% if ShowInAdmin == 1 %>
	    	<a href="/sangria/SetAdminSS?ID=$ID&Set=0" class="roundedButton" style="white-space: nowrap;">Disable as SS Page</a>
	    	<% else %>
	    	<a href="/sangria/SetAdminSS?ID=$ID&Set=1" class="roundedButton editss" data-id='$ID' style="white-space: nowrap;">Enable as SS Page</a>
	    	<% end_if %>
	    </td>
	  </tr>
	<% end_loop %>
	
	</tbody>
</table>
<input type="submit" class="roundedButton" value="Save" style="margin:20px;">
</form>
<% end_if %>
<% end_loop %>

<style>
.tDnD_whileDrag{
	background-color:#ccc;
}
td.userStoryTitle{
	cursor:text;
}
	td.userStoryTitle:hover{
	background-color:#F6FF96;
	}
</style>