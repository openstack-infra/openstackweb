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
            <% loop Capabilities %>
            <tr>
                <td>
                    <% loop ReleaseSupportedApiVersion %>
                        <% loop OpenStackComponent %>
                            $Name
                        <% end_loop %>
                    <% end_loop %>
                </td>
                <td>
                    <% loop ReleaseSupportedApiVersion %>
                        <% loop Release %>
                            $Name
                        <% end_loop %>
                    <% end_loop %>
                </td>
            </tr>
            <% end_loop %>
            </tbody>
        </table>
        <% end_if %>
        <% if Top.PricingSchemas %>

            <h3 style="color: #{$Company.CompanyColor} !important;">Pricing Options</h3>
            <table class="pricing">
            <tbody>
            <% loop Top.PricingSchemas %>
                <tr>
                    <td>$Type</td>
                    <td id="enabled_{$ID}"></td>
                </tr>
            <% end_loop %>
            </tbody>
            </table>
        <% end_if %>
        <% if HyperVisors %>
            <h3 style="color: #{$Company.CompanyColor} !important;">Supported Hypervisors</h3>
            <p>
                <% loop HyperVisors %>
                    <% if First == 0  %>,<% end_if %>
                    $Type
                <% end_loop %>
            </p>
        <% end_if %>

        <% if Guests %>
            <hr>
            <h3 style="color: #{$Company.CompanyColor} !important;">Supported Guests</h3>
            <p>
                <% loop Guests %>
                    <% if First == 0  %>,<% end_if %>
                    $Type
                <% end_loop %>
            </p>
        <% end_if %>

        <% if DataCenterRegions %>
            <hr>
            <h3 style="color: #{$Company.CompanyColor} !important;">Regions</h3>
            <table class="regions">
            <tbody>
            <% loop DataCenterRegions %>
            <tr>
                <td class="region-key">
                    <span style="background-color: #{$Color}"></span>
                </td>
                <td>$Name</td>
            </tr>
            <% end_loop %>
            </tbody>
            </table>
        <% end_if %>
        <% if DataCenters %>
            <hr>
            <h3 style="color: #{$Company.CompanyColor} !important;" >Data Center Locations</h3>
            <p>
                <% loop DataCenters %>
                    <% if First == 0  %>,<% end_if %>
                    $City
                <% end_loop %>
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
                    <% loop RegionalSupports %>
                    <tr>
                        <% loop Region %>
                            <td>$Name</td>
                        <% end_loop %>
                    </tr>
                    <% end_loop %>
                </tbody>
            </table>
        <% end_if %>
        <% if Capabilities %>
            <hr>
            <h3 style="color: #{$Company.CompanyColor} !important;">OpenStack API Coverage</h3>
            <table class="api-coverage">
                <tbody>
                    <% loop Capabilities %>
                        <% if SupportsVersioning %>
                            <% loop ReleaseSupportedApiVersion %>
                                <% if ApiVersion %>
                                <% loop OpenStackComponent %>
                                <tr>
                                <td style="max-width:200px;" width="60%">
                                $Name API
                                <% if SupportsExtensions %> & Extensions<% end_if %>
                                </td>
                                <td width="30%">
                                $CodeName
                                <% end_loop %>
                                <% loop ApiVersion %> $Version<% end_loop %>
                                </td>
                                <td width="10%" class="coverage"></td>
                                </tr>
                                <% end_if %>
                            <% end_loop %>
                        <% end_if %>
                    <% end_loop %>
                </tbody>
            </table>
        <% end_if %>
    </div>
</div>
<div class="col-lg-6">
        <% include MarketPlaceDirectoryPage_Rating_Placeholder %>
        <% if Videos %>
            <div id="videos">
                <% loop Videos %>
                    <h3 style="color: #{$Top.Company.CompanyColor} !important;" class="video-title">$Name<span class="video-duration">($FormattedLength)</span></h3>
                    <iframe frameborder="0" width="250" height="200" allowfullscreen=""
                            src="//www.youtube.com/embed/{$YouTubeId}?rel=0&amp;showinfo=0&amp;modestbranding=1&amp;controls=2">
                    </iframe>
                <% end_loop %>
            </div>
        <% end_if %>

        <% if Resources %>
            <div id="more-resources">
                <h3 style="color: #{$Company.CompanyColor} !important;">More Resources</h3>
                <ul>
                    <% loop Resources %>
                        <li><a href="{$Uri}" style="color: #{$Company.CompanyColor} !important;" target="_blank" class="outbound-link">$Name</a></li>
                    <% end_loop %>
                </ul>
            </div>
        <% end_if %>

    </div>
</div>
</div>