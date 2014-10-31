 <table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;">
        <thead>
        <tr>
            <td>Title</td>
            <td>Start Date</td>
            <td>End Date</td>
            <td>Url</td>
            <td>City</td>
            <td>State</td>
            <td>Country</td>
            <td>&nbsp;</td>
        </tr>
        </thead>
    <tbody>
    <% loop RegistrationRequests %>
    <tr>
    <td>$Title</td>
    <td>$StartDate</td>
    <td>$EndDate</td>
    <td>$Url</td>
    <td>$City</td>
    <td>$State</td>
    <td>$Country</td>
    <td><a href="$Top.Details?evt=$ID">View Event Registration Request</a></td>
    </tr>
    <% end_loop%>
    </tbody>
</table>
