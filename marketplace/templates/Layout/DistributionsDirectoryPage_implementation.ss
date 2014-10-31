<div class="grey-bar">
    <div class="container">
        <p class="back-label">
            <a href="$Top.Link">All Distros &amp; Appliances</a>
        </p>
        <h1 style="color: #{$Company.CompanyColor} !important;">$Name</h1>
    </div>
</div>
<div class="container marketplace-content">
<% include MarketPlaceCompany %>
<div class="col-lg-6">
    <div class="info-area">
        <% if Capabilities %>
        <h3 style="color: #{$Company.CompanyColor} !important;">OpenStack Services Enabled</h3>
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
        <hr>
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
            <h3 style="color: #{$Company.CompanyColor} !important;">Supported Guests</h3>
            <p>
            <% loop Guests %>
                <% if First == 0  %>,<% end_if %>
                $Type
            <% end_loop %>
            </p>
        <% end_if %>
        <% if RegionalSupports %>
            <hr>
            <h3 style="color: #{$Company.CompanyColor} !important;">Regions where support is offered</h3>
            <table class="regions">
                <tbody>
                    <% loop RegionalSupports %>
                    <tr>
                        <% with Region %>
                            <td>$Name</td>
                        <% end_with %>
                    </tr>
                    <% end_loop %>
                </tbody>
            </table>
        <% end_if %>
        <% include OpenStackImplementationCapabilities %>
    </div>
</div>
<div class="col-lg-6">
    <% include MarketPlaceDirectoryPage_Rating %>
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