<% if CurrentMember.isMarketPlaceAdmin %>
<% if canAdmin(public_clouds) %>
    $setCurrentTab(2)
    <% include MarketPlaceAdminPage_CreateBox %>
    <% include MarketPlaceAdminPage_NavBar %>
    <% include MarketPlaceAdminPage_public_clouds_list %>
    <script type="text/javascript">
        var listing_url = "$Top.Link(public_clouds)";
    </script>
<% else %>
    <p>You are not allowed to administer Public Clouds.</p>
<% end_if %>
<% else %>
    <p>You are not allowed to administer MarketPlace.</p>
<% end_if %>
