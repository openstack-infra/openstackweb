<div class="container">
    <% require themedCSS(profile-section) %>

    <h1>Upgrade To Foundation Member</h1>
    <% if CurrentMember %>
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
        <p><a href="{$Top.Link}upgrade2foundationmember/?confirmed=1" class="roundedButton">Yes, Agree</a> &nbsp; <a
                href="{$Top.Link}" class="roundedButton">Cancel</a></p>

    <% else %>
        <p>In order to edit your community profile, you will first need to <a
                href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>. Don't have an account? <a
                href="/join/">Join The Foundation</a></p>

        <p><a class="roundedButton" href="/Security/login/?BackURL=%2Fprofile%2F">Login</a> <a href="/join/"
                                                                                               class="roundedButton">Join
            The Foundation</a></p>
    <% end_if %>
</div></div>