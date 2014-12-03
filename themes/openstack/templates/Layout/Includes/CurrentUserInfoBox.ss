<div class="loggedInBox">
    <div class="row">
        <div class="col-md-4" style="height: 35px;line-height: 35px;"><span style="display:inline-block; vertical-align:middle;">You are logged in as: <strong>$CurrentMember.Name</strong></span></div>
        <div class="col-md-1"><a class="roundedButton" href="{$LogOutLink}">Logout</a></div>
        <div class="col-md-7"><a class="roundedButton" href="{$ResignLink}">Resign Membership</a></div>
    </div>
    <div class="row">
        <% if $CurrentMember.isFoundationMember %>
            <div class="col-md-4" style="height: 35px;line-height: 35px;">
                <span style="display:inline-block; vertical-align:middle;">Current Member Level: <strong>Foundation Member</strong></span>
            </div>
             <div class="col-md-8">
                 <a class="roundedButton downgrade-2-community-member" href="{$Downgrade2CommunityMemberLink}">Change to Community Member</a>
             </div>
        <% else_if $CurrentMember.isSpeaker %>
            <div c class="col-md-12" style="height: 35px;line-height: 35px;">
                <span style="display:inline-block; vertical-align:middle;">Current Member Level: <strong>Speaker</strong></span>
            </div>
        <% else_if $CurrentMember.isCommunityMember %>
            <div c class="col-md-4" style="height: 35px;line-height: 35px;">
                <span style="display:inline-block; vertical-align:middle;">Current Member Level: <strong>Community Member</strong></span>
            </div>
            <div class="col-md-6">
                <a class="roundedButton upgrade-2-foundation-member" href="{$Upgrade2FoundationMemberLink}">Make me a Foundation Member</a>
            </div>
        <% end_if %>
        </div>
</div>
<div id="dialog-confirm-downgrade" title="Change to Community Member?" style="display: none;">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>If you select this option, you will be revoking your right to vote in elections and to commit code to OpenStack via Gerrit.</p>
</div>

<div id="dialog-confirm-upgrade" title="Change to Foundation Member?" style="display: none;">
    <h2>1. Read over the terms of becoming an OpenStack Foundation Individual Member.</h2>
    <div class="termsBox">
        <p>This Individual Member Agreement (“IM Agreement”) between me and the OpenStack Foundation (the “Foundation”) governs my rights and obligations as an Individual Member of the Foundation and is effective on the date that I sign below. I agree that:</p>
        <p>1. My rights and obligations as an Individual Member are defined in the Certificate of Incorporation and Bylaws (including policies which are exhibits to the bylaws, including the Community Code of Conduct) of the Foundation located at <a href="http://openstack.org/legal/">http://www.openstack.org/legal/</a> on the date that I submitted the Individual Member Application (“IM Application”) , as they may be amended from time to time (the “Certificate”, “Bylaws,” and the “Community Code of Conduct” respectively). The changes to the Certificate, Bylaws and Community Code of Conduct shall be effective when posted at <a href="/legal/">http://www.openstack.org/legal/</a> and I will regularly check the Certificate, Bylaws and Community Code of Conduct to ensure that I understand my obligations. I will become an Individual Member effective on the date provided in the Bylaws;</p>
        <p>2. The information that I provided in the IM Application is complete and accurate on the date of submission and I will continue to update such information to ensure that it remains complete and accurate. In particular, I will promptly update any change in my Affiliate status as defined in the Bylaws and my email contact address;</p>
        <p>3. I will comply with the obligations of Individual Members in the Certificate, Bylaws and the Community Code of Conduct;</p>
        <p>4. I consent to making available to the public my name, my Statement of Interest (as provided in the IM Application) and Affiliate status. In addition, I consent to the use of other information in the IM Application as provided in the Bylaws; and</p>
        <p>5. I consent to communication by electronic means to my email contact address. My membership may be terminated for breach of this IM Agreement or my obligations under the Certificate, Bylaws and Community Code of Conduct. Upon such breach, the Foundation shall provide notice to me at my email contact address describing the alleged breach and the thirty day period permitted for response. If I do not respond within such thirty (30) day period, I will automatically cease to be an Individual Member.</p>
        <p>6. This IM Agreement is governed by the laws of the State of Delaware, but not including its conflict of law principles. This IM Agreement is personal to me and may not be transferred to any other party, whether by operation of law or otherwise. This IM Agreement (including the IM Application, Certificate, Bylaws and Community Code of Conduct) constitutes the entire agreement between the parties concerning membership in the Foundation and supersedes all written or oral prior agreements or understandings with respect thereto. No modification, extension or waiver of or under this IM Agreement is valid unless it is made in a writing which identifies itself as an amendment to this IM Agreement and that writing is signed by an authorized representative of each party. No waiver will constitute, or be construed as, a waiver of any other obligation or condition of this IM Agreement.</p>
    </div>
</div>