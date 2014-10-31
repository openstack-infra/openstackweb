<% require themedCSS(conference) %> 



<div class="container summit">

    <% with Parent %>
    $HeaderArea
    <% end_with %>
    
  <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3">
            <p><strong>The OpenStack Summit</strong><br />$MenuTitle.XML</p>

                <div class="newSubNav">
                    <ul class="overviewNav">

                        <% loop Parent %>
                            <li id="$URLSegment"><a href="$Link" title="Go to the $Title.XML page"><span>Overview</span> <i class="fa fa-chevron-right"></i></a></li>
                        <% end_loop %>

                        <% loop Menu(3) %>
                            <li id="$URLSegment"><a href="$Link" title="Go to the &quot;{$Title}&quot; page"  class="$LinkingMode">$MenuTitle <i class="fa fa-chevron-right"></i></a></li>
                        <% end_loop %>
                    </ul>
                </div>
            <% with Parent %>
                <% include SummitVideos %>
                <% include HeadlineSponsors %>
            <% end_with %>


        </div> 

        <!-- News Feed -->

        <div class="col-lg-9 col-md-9 col-sm-9" id="news-feed">

                        <h1>Thank You To The OpenStack Summit Sponsors</h1>
            <p>&nbsp;</p>
            <!-- HeadlineSponsors -->
            <% if HeadlineSponsors %>
            <hr/>
            <h2>Headline Sponsors</h2>
            <p>
                <% loop HeadlineSponsors %>
                <a rel="nofollow" href="{$SubmitLandPageUrl}">
                    $SubmitLogo
                </a>
                <% end_loop %>
            </p>
            <p>&nbsp;</p>
            <% end_if %>
            <!-- PremierSponsors -->
            <% if PremierSponsors %>
            <hr/>
            <h2>Premier Sponsors</h2>
            <p>

                <% loop PremierSponsors %>
                <a rel="nofollow" href="{$SubmitLandPageUrl}">
                    $SubmitLogo
                </a>
                <% end_loop %>
            </p>
            <p>&nbsp;</p>
            <% end_if %>
            <!-- SpotlightSponsors -->
            <% if SpotlightSponsors %>
            <hr/>
            <h2>Spotlight Sponsors</h2>
            <p>
                <% loop SpotlightSponsors %>
                <a rel="nofollow" href="{$SubmitLandPageUrl}">
                    $SubmitLogo
                </a>
                <% end_loop %>
            </p>
            <p>&nbsp;</p>
            <% end_if %>
            <!-- EventSponsors -->
            <% if EventSponsors %>
            <hr/>
            <h2>Event Sponsors</h2>
            <p>

                <% loop EventSponsors %>
                <a rel="nofollow" href="{$SubmitLandPageUrl}">
                    $SubmitLogo
                </a>

                <% end_loop %>
            </p>
            <p>&nbsp;</p>
            <% end_if %>
            <!-- StartupSponsors -->
            <% if StartupSponsors %>
            <hr/>
            <h2>Startup Sponsors</h2>
            <p>
                <% loop StartupSponsors %>
                <a rel="nofollow" href="{$SubmitLandPageUrl}">
                    $SubmitLogo
                </a>
                <% end_loop %>
            </p>
            <p>&nbsp;</p>
            <% end_if %>
            <!-- InKindSponsors -->
            <% if InKindSponsors %>
            <hr/>
            <h2>Community Partners</h2>
            <p>
                <% loop InKindSponsors %>
                <a rel="nofollow" href="{$SubmitLandPageUrl}">
                    $SubmitLogo
                </a>
                <% end_loop %>
            </p>
            <p>&nbsp;</p>
            <% end_if %>


        </div>

    </div>
</div>

$GATrackingCode