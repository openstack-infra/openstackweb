<% if OpenStackAvailableComponents %>
<hr>
<h2>Areas of OpenStack Expertise</h2>
<p>Check all that apply</p>
<form id="expertise_areas_form" name="expertise_areas_form">
    <table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;max-width:99%"  width="100%" >
        <thead>
        <tr>
            <% loop OpenStackAvailableComponents %>
            <th style="border: 1px solid #ccc;background:#eaeaea;max-width:100px;" width="10%">
                    $Name ($CodeName)
            </th>
            <% end_loop %>
        </tr>
        </thead>
        <tbody>
        <tr>
            <% loop OpenStackAvailableComponents %>
            <td style="border: 1px solid #ccc;background:#fff;text-align:center;">
                <input type="checkbox" class="checkbox expertise-area-checkbox" name="expertise_area_{$ID}" id="expertise_area_{$ID}" data-expertise-area-id="{$ID}" />
            </td>
            <% end_loop %>
        </tr>
        </tbody>
    </table>
</form>
<% end_if %>