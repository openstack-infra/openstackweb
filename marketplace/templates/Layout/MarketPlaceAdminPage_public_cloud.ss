<% if canAdmin(public_clouds) %>
<div class="container">
    <div style="clear:both">
        <h1 style="width:50%;float:left;">Public Cloud - Product Details</h1>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center" class="roundedButton save-public-cloud" href="#" id="save-public-cloud1">Save</a>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton publish-public-cloud" href="#" id="publish-public-cloud1">Publish</a>
        <% if CurrentPublicCloud %>
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" target="_blank" href="$Top.Link(public_cloud)/$CurrentPublicCloud.ID/<% if CurrentPublicCloud.isDraft %>draft_<% end_if %>preview">Preview</a>
        <% end_if %>
        <% if CurrentPublicCloud %>
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" target="_blank" href="$Top.Link(public_cloud)/$CurrentPublicCloud.ID/<% if CurrentPublicCloud.isDraft %>draft_<% end_if %>pdf">Download PDF</a>
        <% end_if %>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" href="$Top.Link(public_clouds)">&lt;&lt; Back to Products</a>
    </div>
    <div style="clear:both">
        <fieldset>
        <form id="public_cloud_form" name="public_cloud_form">
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
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center" class="roundedButton save-public-cloud" href="#" id="save-public-cloud2">Save</a>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" href="$Top.Link(public_clouds)">&lt;&lt; Back to Products</a>
    </div>
    <script type="text/javascript">
    <% if CurrentPublicCloud %>
    var public_cloud = $CurrentPublicCloudJson;
    <% end_if %>
    var component_releases = $ReleasesByComponent;
    var listing_url = "$Top.Link(public_clouds)";
    </script>
</div>
<% else %>
    <p>You are not allowed to administer Public Clouds.</p>
<% end_if %>