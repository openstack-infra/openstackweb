<h2>CLA/ICLA Companies</h2>
<% if Companies %>
    <a href="$Link(exportCCLACompanies)">export to excel</a>
    <table id="ccla-companies-table">
        <thead>
            <tr>
                <th>Company Name</th>
                <th>CCLA Date</th>
                <th>CCLA Signed</th>
            </tr>
        </thead>
        <tbody>
        <% loop Companies %>
            <tr class="row-{$Modulus(2)}">

                <td>$Name</td>
                <td id="ccla_date_{$ID}">$CCLADate</td>
                <td>
                    <input type="checkbox" class="ccla_checkbox" data-company-id="{$ID}" id="ccla_signed_checkbox_{$ID}" name="ccla_signed_checkbox_{$ID}" <% if isICLASigned %> checked <% end_if %>>
                </td>
            </tr>
        <% end_loop %>
        </tbody>
    </table>
<% end_if %>