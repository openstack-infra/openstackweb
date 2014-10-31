<div class="container">
    <h1>How the OpenStack Foundation can help</h1>
    <hr>
    <div class="main-content span-16">
        <% if LatestSectionLinks %>
            <div class="promoted-content span-16">
                <% include Marketing_SectionLinks %>
            </div>
        <% end_if %>
        <% if LatestGraphics %>
            <div class="graphics span-16">
                <% include Marketing_Graphics %>
            </div>
        <% end_if %>
        <% if LatestPresentations %>
            <div class="collateral span-16">
                <% include Marketing_Collateral_Presentations %>
            </div>
        <% end_if %>
        <% if LatestYouTubeVideos %>
            <div class="videos span-16">
                <% include Marketing_YouTubeVideos %>
            </div>
        <% end_if %>
        <% if LatestEventsMaterial %>
            <div class="collateral span-16">
                <% include Marketing_Events_Materials %>
            </div>
        <% end_if %>
    </div>
    <div class="secondary-content span-7 prepend-1 last">
        <% if LatestAnnouncements %>
            <div class="announcements">
                <% include Marketing_Announcements %>
            </div>
        <% end_if %>
        <% if Feeds %>
            <div class="feed">
                <% include Marketing_Feeds %>
            </div>
        <% end_if %>
        <% if LatestCases %>
            <div class="case-studies span-7">
                <% include Marketing_Cases %>
            </div>
        <% end_if %>
    </div>
</div>