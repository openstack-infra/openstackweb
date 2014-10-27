<div class="grey-bar">
    <h1 style="color: {$Company.CompanyColorRGB} !important;">$Name</h1>
</div>
<div class="container marketplace-content">
    <table width="540px">
        <tr>
            <td rowspan="5" valign="top" style="width:140px;padding-right:15px">
                $Company.SmallLogoPreview(150)
                <h2 style="color: {$Company.CompanyColorRGB} !important;">About $Company.Name</h2>
                <p>$Company.Overview</p>
                <hr>
                <div class="pullquote">
                    <h2 style="color: {$Company.CompanyColorRGB} !important;">$Company.Name Commitment</h2>
                    <div <% if Company.CommitmentAuthor %>class="commitment"<% end_if %>>$Company.Commitment</div>
                    <% if Company.CommitmentAuthor %>
                    <p class="author">&mdash;$Company.CommitmentAuthor, $Company.Name</p>
                    <% end_if %>
                </div>
            </td>
            <td colspan="2" valign="top" style="width:400px;padding-left:15px;">
                <div class="info-area">
                    <h1 style="color: {$Company.CompanyColorRGB} !important;">
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
                        <h3 style="color: {$Company.CompanyColorRGB} !important;">OpenStack Services Enabled</h3>
                        <table>
                            <tbody>
                            <tr>
                                <th width="50%">Service</th>
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
                    <% if HyperVisors %>
                        <hr>
                        <h3 style="color: {$Company.CompanyColorRGB} !important;">Supported Hypervisors</h3>
                        <p>
                        <% control HyperVisors %>
                            <% if First == 0  %>,<% end_if %>
                            $Type
                        <% end_control %>
                        </p>
                    <% end_if %>
                    <% if Guests %>
                        <h3 style="color: {$Company.CompanyColorRGB} !important;">Supported Guests</h3>
                        <p>
                        <% control Guests %>
                            <% if First == 0  %>,<% end_if %>
                            $Type
                        <% end_control %>
                        </p>
                    <% end_if %>
                    <% if RegionalSupports %>
                        <hr>
                        <h3 style="color: {$Company.CompanyColorRGB} !important;">Regions where support is offered</h3>
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
                    <% if Capabilities %>
                        <hr>
                        <h3 style="color: {$Company.CompanyColorRGB} !important;">OpenStack API Coverage</h3>
                        <table class="api-coverage">
                            <tbody>
                                <% control Capabilities %>
                                    <% if SupportsVersioning %>
                                        <% control ReleaseSupportedApiVersion %>
                                            <% if ApiVersion %>
                                                <% control OpenStackComponent %>
                                                    <tr>
                                                        <td>
                                                            $Name API
                                                            <% if SupportsExtensions %> & Extensions<% end_if %>
                                                        </td>
                                                        <td>
                                                            $CodeName
                                                <% end_control %>
                                                            <% control ApiVersion %> $Version<% end_control %>
                                                        </td>

                                            <% end_if %>
                                        <% end_control %>
                                        <td class="coverage">
                                            <span>$CoveragePercent %</span>
                                        </td>
                                    </tr>
                                    <% end_if %>
                                <% end_control %>
                            </tbody>
                        </table>
                    <% end_if %>
                </div>
            </td>
            <td valign="top" style="width:200px;padding-left:15px;padding-right:15px">
                <div id="reviews" style="min-height: 400px;">
                    <h3 style="color: {$Company.CompanyColorRGB} !important;">Reviews</h3>
                    <p>* No Reviews available on preview mode.</p>
                    <!--This script should be places anywhere on a page you want to see rating box-->
                    <div style='font-size:10px'>Powered by Rating-System.com</div>
                    <!-- DO NOT REMOVE THE LAST LINE, please contact us first if you need to do it -->
                </div>
                <% if Videos %>
                    <hr>
                    <div id="videos">
                    <% control Videos %>
                        <h3 style="color: {$Top.Company.CompanyColorRGB} !important;" class="video-title">$Name<span class="video-duration">($FormattedLength)</span></h3>
                        <a href="//www.youtube.com/embed/{$YouTubeId}"> Video </a>
                    <% end_control %>
                   </div>
                <% end_if %>

                <% if Resources %>
                    <hr>
                    <div id="more-resources">
                        <h3 style="color: {$Company.CompanyColorRGB} !important;">More Resources</h3>
                        <ul>
                            <% control Resources %>
                                <li style="color: {$Company.CompanyColorRGB}>$Name</li>
                            <% end_control %>
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