<div class="loggedInBox">You are logged in as: <strong>$CurrentMember.Name</strong>&nbsp; &nbsp; <a class="roundedButton" href="{$Link}logout">Logout</a></div>
<div style="display:block;clear:both">
    <h1 style="width:50%;float:left;">Marketplace Management</h1>
    <a id="add-new-product" name="add-new-product" style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" href="#">Add New Product</a>
    <div style="float:right;margin-right:50px;vertical-align:top;">
        <label for="marketplace_type_id"> select product type to add</label>
        &nbsp;
        <select id="marketplace_type_id" name="marketplace_type_id">
            <option value="">-- Select a Type --</option>
            <% if MarketPlaceTypes %>
                <% loop MarketPlaceTypes %>
                    <option value="{$ID}">$Name</option>
                <% end_loop %>
            <% end_if %>
        </select>
        &nbsp;
    </div>
</div>
<script type="application/javascript">
    var  add_link = "$Top.Link(add)";
</script>