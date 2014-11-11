<% if canAdmin(appliances) %>
<div class="container">
    <div style="clear:both">
        <h1 style="width:50%;float:left;">Appliances - Product Details</h1>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center" class="roundedButton save-appliance" href="#" id="save-appliance">Save</a>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton publish-appliance" href="#" id="publish-appliance">Publish</a>
        <% if CurrentAppliance %>
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" target="_blank" href="$Top.Link(appliance)/$CurrentAppliance.ID/<% if CurrentAppliance.isDraft %>draft_<% end_if %>preview">Preview</a>
        <% end_if %>
        <% if CurrentAppliance %>
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" target="_blank" href="$Top.Link(appliance)/$CurrentAppliance.ID/<% if CurrentAppliance.isDraft %>draft_<% end_if %>pdf">Download PDF</a>
        <% end_if %>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" href="$Top.Link">&lt;&lt; Back to Products</a>
    </div>
    <div style="clear:both">
        <fieldset>
        <form id="appliance_form" name="appliance_form">
        <% include MarketPlaceAdminPage_CompanyServiceHeader %>
        </form>
        <% include Components %>
        <% include Hypervisors %>
        <% include GuestOSSupport %>
        <% include Videos %>
        <% include SupportChannels %>
        <% include AdditionalResources %>
        </fieldset>
    </div>
    <div style="clear:both">
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center" class="roundedButton save-appliance" href="#" id="save-appliance2">Save</a>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" href="$Top.Link">&lt;&lt; Back to Products</a>
    </div>
    <script type="text/javascript">
        <% if CurrentAppliance %>
        var appliance = $CurrentApplianceJson;
        <% end_if %>
        var component_releases = $ReleasesByComponent;
        var listing_url = "{$Top.Link}";
    </script>
</div>
<% else %>
    <p>You are not allowed to administer Appliances.</p>
<% end_if %>