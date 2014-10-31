
<h2>Standardize Member and User Organizations</h2>

<h3>Organizations</h3>
<p>$NonStandardizedOrgs.count non-standardized organizations remaining</p>
<form method="post" action="/sangria/RemoveDuplicateOrg">
<table style="border: 1px solid #ccc; border-collapse:collapse;">
  <tr>
    <th style="border: 1px solid #ccc;">Organization</th>
    <th style="border: 1px solid #ccc;">Standardized?</th>
    <th style="border: 1px solid #ccc;">Move all participants to standardized org</th>
  </tr>
<% loop NonStandardizedOrgs %>
  <tr>
    <td style="border: 1px solid #ccc;">$Name ($Members.count) - $ID</td>
    <td style="border: 1px solid #ccc;"><a href="/sangria/MarkOrgStandardized?orgId=$ID">Yes</a></td>
    <td style="border: 1px solid #ccc;">
      <select name="oldOrgIds[$ID]">
        <option value="0"> -- Select One -- </option>
        <option value="STANDARDIZE"> -- MAKE STANDARD ORG -- </option>
        <% loop Top.StandardizedOrgs %>
                <option value="$ID">$Name</option>
        <% end_loop %>
      </select>
    </td>
  </tr>
<% end_loop %>
</table>
<input type="submit" value="Update Organizations" />
</form>
