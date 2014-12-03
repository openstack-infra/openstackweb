<div class="loggedInBox">
    <div class="row">
        <div class="col-md-4" style="height: 35px;line-height: 35px;"><span style="display:inline-block; vertical-align:middle;">You are logged in as: <strong>$CurrentMember.Name</strong></span></div>
        <div class="col-md-8  text-right">
            <a class="roundedButton" href="{$ResignLink}">Resign Membership</a>
            <% if $CurrentMember.isFoundationMember %>
                <a class="roundedButton downgrade-2-community-member" href="$Top.Link(downgrade2communitymember)">Change to Community Member</a>
            <% else_if $CurrentMember.isCommunityMember %>
                <a class="roundedButton upgrade-2-foundation-member" href="$Top.Link(upgrade2foundationmember)">Make me a Foundation Member</a>
            <% end_if %>
        </div>
    </div>
    <div class="row">
        <% if $CurrentMember.isFoundationMember %>
            <div class="col-md-12" style="height: 35px;line-height: 35px;">
                <span style="display:inline-block; vertical-align:middle;">Current Member Level: <strong>Foundation Member</strong></span>
            </div>
        <% else_if $CurrentMember.isSpeaker %>
            <div c class="col-md-12" style="height: 35px;line-height: 35px;">
                <span style="display:inline-block; vertical-align:middle;">Current Member Level: <strong>Speaker</strong></span>
            </div>
        <% else_if $CurrentMember.isCommunityMember %>
            <div c class="col-md-12" style="height: 35px;line-height: 35px;">
                <span style="display:inline-block; vertical-align:middle;">Current Member Level: <strong>Community Member</strong></span>
            </div>
        <% end_if %>
        </div>
</div>