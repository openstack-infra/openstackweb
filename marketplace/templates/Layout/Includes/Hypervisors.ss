<% if getHyperVisors %>
    <form name="hypervisors_form" id="hypervisors_form">
        <table class="admin-table" style="max-width:99%;" width="100%">
            <thead>
            <tr>
                <th style=";width:20%;">Hypervisors</th>
                <% loop getHyperVisors %>
                    <th style="width:10%;">$Type</th>
                <% end_loop %>
            </tr>
            </thead>
            <tbody>
            <tr>
            <td style="border: 1px solid #ccc;">Mark all that apply with an X</td>
                <% loop getHyperVisors %>
                    <td>
                        <input type="checkbox" class="checkbox hypervisor-type" value="{$ID}" name="hypervisor-type_{$ID}" id="hypervisor-type_{$ID}">
                    </td>
                <% end_loop %>
            </tr>
            </tbody>
        </table>
    </form>
<% end_if %>