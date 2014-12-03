<div class="container">
    <% require themedCSS(profile-section) %>

    <h1>Downgrade To Community Member</h1>
    <% if CurrentMember %>
        <p>If you select this option, you will be revoking your right to vote in elections and to commit code to OpenStack via Gerrit. Additionally, any administrative rights to the Marketplace Admin or Company Admin will be revoked.</p>
        <p><a href="{$Top.Link}downgrade2communitymember/?confirmed=1" class="roundedButton">Yes, Agree</a> &nbsp; <a
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