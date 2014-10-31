<% if PricingSchemas %>
        <form id="pricing_schema_form" name="pricing_schema_form">
        <table class="admin-table" style="max-width:99%"  width="100%">
            <thead>
            <tr>
                <th style=";width:20%;">Pricing Scheme</th>
                <% loop PricingSchemas %>
                    <th style="max-width:100px;" width="10%">
                        $Type
                    </th>
                <% end_loop %>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="border: 1px solid #ccc;">Mark all that apply with an X</td>
                <% loop PricingSchemas %>
                    <td>
                        <input type="checkbox" class="checkbox pricing-schema-checkbox" name="pricing_schema_{$ID}" id="pricing_schema_{$ID}" data-pricing-schema-id="{$ID}" />
                    </td>
                <% end_loop %>
            </tr>
            </tbody>
        </table>
    </form>
<% end_if %>