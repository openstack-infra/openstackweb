<div class="grey-bar">
    <div class="container">
        &nbsp;
    </div>
</div>
<div class="container">

  $Content

<% cached 'drivertable', ID %>

<table class="driver-table" id="releaseTable">
      <tbody><tr>
        <th class="project">Project</th>
        <th class="vendor">Vendor</th>
        <th class="driver">Driver</th>
        <th class="ships">Ships with OpenStack</th>
      </tr>

      <% loop DriverTable %>

      <tr>
        <td>$Project</td>
        <td>$Vendor</td>
        <td>
          <a href="{$Url}">$Name</a>
          <p>$Description</p>
        </td>
        <td class="releases">
          <% if Releases %>
            <% loop Releases %>
              <a href="{$Url}">$Name</a>
            <% end_loop %>
          <% end_if %>
        </td>
      </tr>

      <% end_loop %>

</tbody></table>

<% end_cached %>
</div>