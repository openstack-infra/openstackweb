<div class="grey-bar">
    <div class="container">
        <p class="back-label">
            <a href="$Top.Link">All Clouds</a>
        </p>
        <h1>$Name</h1>
    </div>
</div>

<div class="container marketplace-content">
<% include MarketPlaceCompany %>
<div class="col-lg-6">
    <div class="info-area">
        <% if Capabilities %>
        <h3 style="color: #{$Company.CompanyColor} !important;">OpenStack Services Offered</h3>
        <table>
            <tbody>
            <tr>
                <th>Service</th>
                <th>OpenStack Version</th>
            </tr>
            <% control Capabilities %>
            <tr>
                <td>
                    <% control ReleaseSupportedApiVersion %>
                        <% control OpenStackComponent %>
                            $Name
                        <% end_control %>
                    <% end_control %>
                </td>
                <td>
                    <% control ReleaseSupportedApiVersion %>
                        <% control Release %>
                            $Name
                        <% end_control %>
                    <% end_control %>
                </td>
            </tr>
            <% end_control %>
            </tbody>
        </table>
        <% end_if %>
        <% if Top.PricingSchemas %>

            <h3 style="color: #{$Company.CompanyColor} !important;">Pricing Options</h3>
            <table class="pricing">
            <tbody>
            <% control Top.PricingSchemas %>

                <tr>
                    <td>$Type</td>
                    <td id="enabled_{$ID}"></td>
                </tr>
            <% end_control %>
            </tbody>
            </table>
            <script>
                var enabled_schemas = $Top.EnabledPricingSchemas;
            </script>
        <% end_if %>
        <% if HyperVisors %>
            <h3 style="color: #{$Company.CompanyColor} !important;">Supported Hypervisors</h3>
            <p>
                <% control HyperVisors %>
                    <% if First == 0  %>,<% end_if %>
                    $Type
                <% end_control %>
            </p>
        <% end_if %>

        <% if Guests %>
            <hr>
            <h3 style="color: #{$Company.CompanyColor} !important;">Supported Guests</h3>
            <p>
                <% control Guests %>
                    <% if First == 0  %>,<% end_if %>
                    $Type
                <% end_control %>
            </p>
        <% end_if %>

        <% if DataCenterRegions %>
            <hr>
            <h3 style="color: #{$Company.CompanyColor} !important;">Regions</h3>
            <table class="regions">
            <tbody>
            <% control DataCenterRegions %>
            <tr>
                <td class="region-key">
                    <span style="background-color: #{$Color}"></span>
                </td>
                <td>$Name</td>
            </tr>
            <% end_control %>
            </tbody>
            </table>
        <% end_if %>
        <% if DataCenters %>
        <script type="text/javascript">
                var dc_locations_per_cloud_instance = $Top.CurrentDataCenterLocationsJson;
        </script>
        <hr>
        <h3 style="color: #{$Company.CompanyColor} !important;" >Data Center Locations</h3>
        <p>
            <% control DataCenters %>
                <% if First == 0  %>,<% end_if %>
                $City
            <% end_control %>
        </p>
        <div style="width: 300px; height: 200px; position: relative;" id="mini-map" tabindex="0">
        </div>
        <p>Click any location to see availability zones and API endpoints</p>
        <% end_if %>
        <% if RegionalSupports %>
            <hr>
            <h3 style="color: #{$Company.CompanyColor} !important;">Regions where support is offered</h3>
            <table class="regions">
                <tbody>
                    <% control RegionalSupports %>
                    <tr>
                        <% control Region %>
                            <td>$Name</td>
                        <% end_control %>
                    </tr>
                    <% end_control %>
                </tbody>
            </table>
        <% end_if %>
        <% include OpenStackImplementationCapabilities %>
    </div>
</div>
<div class="col-lg-6">
        <% if IsPreview  %>
            <% include MarketPlaceDirectoryPage_Rating_Placeholder %>
        <% else %>
            <% include MarketPlaceDirectoryPage_Rating %>
        <% end_if %>
        <% if Videos %>
            <div id="videos">
                <% control Videos %>
                    <h3 style="color: #{$Top.Company.CompanyColor} !important;" class="video-title">$Name<span class="video-duration">($FormattedLength)</span></h3>
                    <iframe frameborder="0" width="250" height="200" allowfullscreen=""
                            src="//www.youtube.com/embed/{$YouTubeId}?rel=0&amp;showinfo=0&amp;modestbranding=1&amp;controls=2">
                    </iframe>
                <% end_control %>
            </div>
        <% end_if %>

        <% if Resources %>
            <div id="more-resources">
                <h3 style="color: #{$Company.CompanyColor} !important;">More Resources</h3>
                <ul>
                    <% control Resources %>
                        <li><a href="{$Uri}" style="color: #{$Company.CompanyColor} !important;" target="_blank" class="outbound-link">$Name</a></li>
                    <% end_control %>
                </ul>
            </div>
        <% end_if %>

    </div>
</div>
</div>