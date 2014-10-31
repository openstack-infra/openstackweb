<% if SupportChannelTypes %>
    <hr>
    <h2>Regions where support is offered</h2>
    <p>Check all that apply</p>
    <form id="support-channels-form" name="support-channels-form">

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
                        <input type="checkbox" class="checkbox support-offered-region-checkbox" name="support_offered_region_{$ID}" id="support_offered_region_{$ID}" data-support-offered-region-id="{$ID}" />
                    </td>
                <% end_loop %>
            </tr>
            </tbody>
        </table>
        </table>
    </form>
<% end_if %>