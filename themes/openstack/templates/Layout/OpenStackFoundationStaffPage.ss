<h1>The OpenStack Foundation</h1>
$Content
<% if OpenStackFoundationStaffMembers %>
    <h2 class="span-24 last">Open Stack Foundation Staff</h2>
    <hr>
    <% loop OpenStackFoundationStaffMembers %>
          <% include OpenStackFoundationStaffPage_Member %>
    <% end_loop %>
<% end_if %>

<% if SupportingCastMembers %>
<h2 class="span-24 last">Supporting Cast</h2>
<hr>
    <% loop SupportingCastMembers %>
        <% include OpenStackFoundationStaffPage_CastMember %>
    <% end_loop %>
<% end_if %>