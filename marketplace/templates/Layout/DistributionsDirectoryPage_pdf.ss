<div class="grey-bar">
    <h1 style="color: #{$Company.CompanyColor} !important;">$Name</h1>
</div>
<div class="container marketplace-content">
    <table width="540px">
        <tr>
            <td rowspan="5" valign="top" style="width:140px;padding-right:15px">
                $Company.SmallLogoPreview(150)
                <h2 style="color: #{$Company.CompanyColor} !important;">About $Company.Name</h2>
                <p>$Company.Overview</p>
                <hr>
                <div class="pullquote">
                    <h2 style="color: #{$Company.CompanyColor} !important;">$Company.Name Commitment</h2>
                    <div <% if Company.CommitmentAuthor %>class="commitment"<% end_if %>>$Company.Commitment</div>
                    <% if Company.CommitmentAuthor %>
                    <p class="author">&mdash;$Company.CommitmentAuthor, $Company.Name</p>
                    <% end_if %>
                </div>
            </td>
            <td colspan="2" valign="top" style="width:400px;padding-left:15px;">
                <div class="info-area">
                    <h1 style="color: #{$Company.CompanyColor} !important;">
                        $Name
                    </h1>
                    <p>$Overview</p>
                </div>
            </td>
        </tr>
        <tr>
            <td valign="top" style="width:200px;padding-left:15px;">
                <div class="info-area">
                    <% if Capabilities %>
                    <h3 style="color: #{$Company.CompanyColor} !important;">OpenStack Services Enabled</h3>
                        <table>
                            <tbody>
                            <tr>
                                <th width="50%">Service</th>
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
                                                        <td>
                                                            $Name API
                                                            <% if SupportsExtensions %> & Extensions<% end_if %>
                                                        </td>
                                                        <td>
                                                            $CodeName
                                                <% end_loop %>
                                                            <% loop ApiVersion %> $Version<% end_loop %>
                                                        </td>

                                            <% end_if %>
                                        <% end_loop %>
                                        <td class="coverage">
                                            <span>$CoveragePercent %</span>
                                        </td>
                                    </tr>
                                    <% end_if %>
                                <% end_loop %>
                            </tbody>
                        </table>
                    <% end_if %>
                </div>
            </td>
            <td valign="top" style="width:200px;padding-left:15px;padding-right:15px">
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
            </td>
        </tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
    </table>
</div>