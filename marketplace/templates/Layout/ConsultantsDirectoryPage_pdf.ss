<div class="grey-bar">
    <h1>$Name</h1>
</div>
<% control Consultant %>
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
                        <% if PreviousClients %>
                            <h3 style="color: #{$Company.CompanyColorRGB} !important;">Select Clients</h3>
                            <ul>
                                <% control PreviousClients %>
                                    <li>$Name</li>
                                <% end_control %>
                            </ul>
                        <% end_if %>
                        <% if ConfigurationManagementExpertises %>
                            <hr>
                            <h3 style="color: #{$Company.CompanyColorRGB} !important;">Configuration Management Expertise</h3>
                            <ul>
                                <% control ConfigurationManagementExpertises %>
                                <li>$Type</li>
                                <% end_control %>
                            </ul>
                        <% end_if %>
                        <% if SpokenLanguages %>
                            <hr>
                            <h3 style="color: #{$Company.CompanyColorRGB} !important;">Languages</h3>
                            <ul>
                                <% control SpokenLanguages %>
                                    <li>$Name</li>
                                <% end_control %>
                            </ul>
                        <% end_if %>
                        <% if Top.Regions %>
                            <hr>
                            <h3  style="color: #{$Company.CompanyColorRGB} !important;">Regions with local offices</h3>
                            <ul>
                                <% control Top.Regions %>
                                    <li>$Name</li>
                                <% end_control %>
                            </ul>
                        <% end_if %>
                        <% if Offices %>
                            <hr>
                            <h3  style="color: #{$Company.CompanyColorRGB} !important;">Offices</h3>
                            <div style="width: 300px; height: 200px; position: relative;" tabindex="0">
                                <img src="$Top.CurrentOfficesLocationsStaticMapForPDF" />
                            </div>
                            <p>
                                Click any map pin to see office address
                            </p>
                        <% end_if %>
                        <% if RegionalSupports %>
                            <hr>
                            <h3 style="color: #{$Company.CompanyColorRGB} !important;">Regions where support is offered</h3>
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
                    </div>
                </td>
                <td valign="top" style="width:200px;padding-left:15px;padding-right:15px">
                    <% if ExpertiseAreas %>
                        <h3  style="color: #{$Company.CompanyColorRGB} !important;">Areas of OpenStack Expertise</h3>
                        <table>
                            <tbody>
                                <% control ExpertiseAreas %>
                                    <tr>
                                        <td>$Name</td>
                                        <td>$CodeName</td>
                                    </tr>
                                <% end_control %>
                            </tbody>
                        </table>
                        <hr>
                    <% end_if %>
                    <% if Top.Services %>
                        <h3 style="color: #{$Company.CompanyColorRGB} !important;">Services Offered</h3>
                        <ul>
                            <% control Top.Services %>
                                <li>$Type</li>
                            <% end_control %>
                        </ul>
                        <hr>
                    <% end_if %>
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
<% end_control %>
