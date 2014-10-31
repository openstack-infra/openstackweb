<div class="span-24 last">
    <div class="span-4">
        <div class="photo">$ProfilePhoto(100)</div>
        <p>
            <% if TwitterName %>
            <a target="_blank" href="https://twitter.com/{$TwitterName}">
                <img width="25" height="25" src="/themes/openstack/images/icons/icon_twitter.png">
            </a>
            <% end_if %>
            <% if LinkedInProfile %>
            <a href="http://linkedin.com/in/{$LinkedInProfile}">
                <img width="25" height="25" src="/themes/openstack/images/icons/icon_linkedin.png">
            </a>
            <% end_if %>
            <a href="/community/members{$Link}{$ID}">
                <img width="25" height="25" src="/themes/openstack/images/icons/icon_openstack.png">
            </a>
        </p>
    </div>
    <div class="span-20 last">
        <h3>$FullName</h3>
        <div class="span-2"><strong>Job Title</strong></div>
        <div class="span-18 last">$JobTitle&nbsp;</div>
        <div class="span-2"><strong>&nbsp;</strong></div>
        <div class="span-18 last">&nbsp;</div>
        <div class="span-2"><strong>Bio</strong></div>
        <div class="span-18 last">
            $Bio
            <p>&nbsp;</p>
        </div>
    </div>
</div>