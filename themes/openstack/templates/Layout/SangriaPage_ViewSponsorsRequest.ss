<div stlye="display:block;clear:both">
	<h1>Sponsor Requests</h1>
    <% if SponsorsRequest %>
	<% control SponsorsRequest %>
	<ul>
		<li>$CompanyName</li>
		<li><a href="sangria/SponsorApprove?sponsor_id=$ID">Approve or Reject</a></li>
	</ul>
	<% end_control %>
    <% end_if %>
</div>
