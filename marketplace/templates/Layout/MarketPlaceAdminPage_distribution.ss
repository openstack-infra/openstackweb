<% if canAdmin(distributions) %>
<div class="container">
    <div style="clear:both">
        <h1 style="width:50%;float:left;">Distribution - Product Details</h1>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center" class="roundedButton save-distribution" href="#" id="save-distribution">Save</a>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton publish-distribution" href="#" id="publish-distribution">Publish</a>
        <% if CurrentDistribution %>
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" target="_blank" href="$Top.Link(distribution)/$CurrentDistribution.ID/draft_preview">Preview</a>
        <% end_if %>
        <% if CurrentDistribution %>
            <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" target="_blank" href="$Top.Link(distribution)/$CurrentDistribution.ID/pdf">Download PDF</a>
        <% end_if %>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" href="$Top.Link">&lt;&lt; Back to Products</a>
    </div>
    <div style="clear:both">
        <fieldset>
        <form id="distribution_form" name="distribution_form">
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
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center" class="roundedButton save-distribution" href="#" id="save-distribution2">Save</a>
        <a style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;margin-bottom:50px;text-align:center;margin-right:50px;" class="roundedButton addDeploymentBtn" href="$Top.Link">&lt;&lt; Back to Products</a>
    </div>
    <script type="text/javascript">
            <% if CurrentDistribution %>
                var distribution = $CurrentDistributionJson;
            <% end_if %>
            var component_releases = $ReleasesByComponent;
            var listing_url = "{$Top.Link}";
        </script>
    </div>
</div>
<% else %>
    <p>You are not allowed to administer Distributions.</p>
<% end_if %>