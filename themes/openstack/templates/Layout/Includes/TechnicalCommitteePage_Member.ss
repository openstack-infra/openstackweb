<hr/>

<a name="39"></a>
<div class="span-4">
    <div class="photo">$ProfilePhoto</div>
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
    <h3>$FirstName $Surname</h3>
    <% if Role %>
        <h4 class="role">$Role</h4>
    <% end_if %>
    <div class="span-2"><strong>Company</strong></div>
    <div class="span-18 last">$CurrentCompanies&nbsp;</div>
    <div class="span-2"><strong>Bio</strong></div>
    <div class="span-18 last">
        $Bio
        <p>&nbsp;</p>
    </div>
</div>
<div class="span-24 last"><p></p></div>
