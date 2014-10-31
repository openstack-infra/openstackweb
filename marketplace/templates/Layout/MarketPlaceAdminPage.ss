<% if CurrentMember.isMarketPlaceAdmin %>
    $setCurrentTab(1)
    <% include MarketPlaceAdminPage_CreateBox %>
    <% include MarketPlaceAdminPage_NavBar %>
    <% if canAdmin(implementations) %>
        <% include MarketPlaceAdminPage_implementations_list %>
        <script type="text/javascript">
            var listing_url = "{$Link}";
        </script>
    <% else %>
        <p>You are not allowed to manage Appliance/Distributions.</p>
    <% end_if %>
<% else %>
    <p>You are not allowed to administer MarketPlace.</p>
<% end_if %>
