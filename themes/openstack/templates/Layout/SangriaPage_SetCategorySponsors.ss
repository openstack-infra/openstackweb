<div stlye="display:block;clear:both">
	<h1>Set Category for Sponsors</h1>
    <% if SponsorsApproved %>
        <% control SponsorsApproved %>
        <ul>
            <li><h2>$CompanyName</h2>
            <form method="POST" action="/sangria/SetSponsorMarketplaces">
            <input type="hidden" name="SponsorID" value="$ID">
            <p>$ApproveCategoriesForm</p>
            <p><input type="submit" value="Update" class="roundedButton" /><p>
            </form>
            </li>
        </ul>
        <% end_control %>
    <% end_if %>
</div>
