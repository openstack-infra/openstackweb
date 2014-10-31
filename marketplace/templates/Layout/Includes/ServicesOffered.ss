<% if ServicesOffered %>
    <hr>
    <h1>Services and Support Details</h1>
    <h2>Services Offered</h2>
    <p>Check all that apply</p>
    <form id="services_offered_form" name="services_offered_form">
        <table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;max-width:99%"  width="100%" >
            <thead>
            <tr>
                <% loop ServicesOffered %>
                    <th style="border: 1px solid #ccc;background:#eaeaea;max-width:100px;" width="10%">
                        $Type
                    </th>
                <% end_loop %>
            </tr>
            </thead>
            <tbody>
            <tr>
                <% loop ServicesOffered %>
                    <td style="border: 1px solid #ccc;background:#fff;text-align:center;">
                        <input type="checkbox" class="checkbox service-offered-checkbox" name="service_offered_{$ID}" id="service_offered_{$ID}" data-service-offered-id="{$ID}" />
                    </td>
                <% end_loop %>
            </tr>
            </tbody>
        </table>

        <h2>Regions where services offered</h2>
        <p>Check all that apply</p>
            <table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;max-width:99%"  width="100%" >
            <thead>
            <tr>
                <% loop AvailableRegions %>
                    <th style="border: 1px solid #ccc;background:#eaeaea;max-width:100px;" width="10%">
                        $Name
                    </th>
                <% end_loop %>
            </tr>
            </thead>
            <tbody>
            <tr>
                <% loop AvailableRegions %>
                    <td style="border: 1px solid #ccc;background:#fff;text-align:center;">
                        <input type="checkbox" class="checkbox service-offered-region-checkbox" name="service_offered_region_{$ID}" id="service_offered_region_{$ID}" data-service-offered-region-id="{$ID}" />
                    </td>
                <% end_loop %>
            </tr>
            </tbody>
        </table>
    </form>
<% end_if %>