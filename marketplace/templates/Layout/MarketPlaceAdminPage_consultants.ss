<% if CurrentMember.isMarketPlaceAdmin %>
<% if canAdmin(consultants) %>
    $setCurrentTab(3)
    <% include MarketPlaceAdminPage_CreateBox %>
    <% include MarketPlaceAdminPage_NavBar %>
    <% include MarketPlaceAdminPage_consultants_list %>
    <script type="text/javascript">
        var listing_url = "$Top.Link(consultants)";
    </script>
<% else %>
    <p>You are not allowed to administer Consultants.</p>
<% end_if %>
<% else %>
<p>You are not allowed to administer MarketPlace.</p>
<% end_if %>
