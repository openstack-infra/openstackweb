<hr>
<div class="span-4">
    <div class="photo">
        $ProfilePhoto
    </div>
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
</div>
<div class="span-20 last">
    <h3>$FullName</h3>
     <div class="span-2"><strong>Company</strong></div>
    <div class="span-18 last">$CurrentCompanies&nbsp;</div>
    <div class="span-2"><strong>Bio</strong></div>
    <div class="span-18 last">$Bio&nbsp;</div>
</div>
<div class="span-24 last"><p>&nbsp;</p></div>
