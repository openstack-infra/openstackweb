<table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;">
    <thead>
    <tr>
        <td>Title</td>
        <td>Url</td>
        <td>Company</td>
        <td>Location Type</td>
        <td>&nbsp;</td>
    </tr>
    </thead>
    <tbody>
        <% loop RegistrationRequests %>
        <tr>
            <td>$Title</td>
            <td>$Url</td>
            <td>$CompanyName</td>
            <td>$LocationType</td>
            <td><a href="$Top.Details?job=$ID">View Job Registration Request</a></td>
        </tr>
        <% end_loop %>
    </tbody>
</table>
