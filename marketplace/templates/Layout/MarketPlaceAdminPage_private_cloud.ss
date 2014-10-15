<% if canAdmin(private_clouds) %>
    <div class="container">
        <div style="clear:both">
            <h1 style="width:50%;float:left;">Private Cloud - Product Details</h1>
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center" class="roundedButton save-private-cloud" href="#" id="save-private-cloud1">Save</a>
            <% if CurrentPrivateCloud %>
                <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" target="_blank" href="$Top.Link(private_cloud)/$CurrentPrivateCloud.ID/preview">Preview</a>
            <% end_if %>
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" href="$Top.Link(private_clouds)">&lt;&lt; Back to Products</a>
        </div>
        <div style="clear:both">
            <fieldset>
                <form id="private_cloud_form" name="private_cloud_form">
                    <% include MarketPlaceAdminPage_CompanyServiceHeader %>
                </form>
                <% include Components %>
                <% include PricingSchema %>
                <% include Hypervisors %>
                <% include GuestOSSupport %>
                <% include Videos %>
                <% include DataCenterLocations %>
                <% include SupportChannels %>
                <% include AdditionalResources %>
            </fieldset>
        </div>
        <div style="clear:both">
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center" class="roundedButton save-private-cloud" href="#" id="save-private-cloud2">Save</a>
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" href="$Top.Link(private_clouds)">&lt;&lt; Back to Products</a>
        </div>
        <script type="text/javascript">
            <% if CurrentPrivateCloud %>
            var private_cloud = $CurrentPrivateCloudJson;
            <% end_if %>
            var component_releases = $ReleasesByComponent;
            var listing_url = "$Top.Link(private_clouds)";
        </script>
    </div>
<% else %>
    <p>You are not allowed to administer Private Clouds.</p>
<% end_if %>