<% if ConfigurationManagementTypes %>
    <hr>
    <h2>Config Management Expertise</h2>
    <p>Check all that apply</p>
    <form id="configuration_management_form" name="configuration_management_form">
        <table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;max-width:99%"  width="100%" >
            <thead>
            <tr>
                <% loop ConfigurationManagementTypes %>
                    <th style="border: 1px solid #ccc;background:#eaeaea;max-width:100px;" width="10%">
                        $Type
                    </th>
                <% end_loop %>
            </tr>
            </thead>
            <tbody>
            <tr>
                <% loop ConfigurationManagementTypes %>
                    <td style="border: 1px solid #ccc;background:#fff;text-align:center;">
                        <input type="checkbox" class="checkbox configuration-management-checkbox" name="configuration_management_{$ID}" id="configuration_management_{$ID}" data-configuration-management-id="{$ID}" />
                    </td>
                <% end_loop %>
            </tr>
            </tbody>
        </table>
    </form>
<% end_if %>
