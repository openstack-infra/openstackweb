<% if CurrentMember.isMarketPlaceAdmin %>
    <% if canAdmin(private_clouds) %>
        $setCurrentTab(4)
        <% include MarketPlaceAdminPage_CreateBox %>
        <% include MarketPlaceAdminPage_NavBar %>
        <% include MarketPlaceAdminPage_private_clouds_list %>
        <script type="text/javascript">
            var listing_url = "$Top.Link(private_clouds)";
        </script>
    <% else %>
        <p>You are not allowed to administer Private Clouds.</p>
    <% end_if %>
<% else %>
    <p>You are not allowed to administer MarketPlace.</p>
<% end_if %>
