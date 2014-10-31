<% if GuestsOS %>
    <form name="guest_os_form" id="guest_os_form">
            <table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;max-width:35%;" width="100%">
            <tbody>
            <tr>
            <th style="border: 1px solid #ccc;width:20%;">Guest OS Support</th>
            <% loop getGuestsOS %>
                <th style="border: 1px solid #ccc;background:#eaeaea;width:10%;">$Type</th>
            <% end_loop %>
            </tr>
            <tr>
                <th style="border: 1px solid #ccc;">Mark all that apply with an X</th>
                <% loop getGuestsOS %>
               <th style="border: 1px solid #ccc;background:#fff;text-align:center;">
                   <input type="checkbox" class="checkbox guest-os-type" value="$ID" id="guest_os_type_{$ID}" name="guest_os_type_{$ID}" >
               </th>
                <% end_loop %>
            </tr>
            </tbody>
            </table>
    </form>
<% end_if %>