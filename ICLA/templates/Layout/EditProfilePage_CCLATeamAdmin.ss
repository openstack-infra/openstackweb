$SetCurrentTab(6)
<% require themedCSS(profile-section) %>
<h1>$Title</h1>
<% if CurrentMember %>
    <% include ProfileNav %>
    <% if CurrentMember.isCCLAAdmin %>
        <fieldset>
        <% include EditProfilePage_Teams  %>
        <br>
        <br>
        <% include EditProfilePage_TeamMembers %>
        </fieldset>
    <% else %>
        <p>You are not allowed to administer ICCLA/CCLA Team Members</p>
    <% end_if %>
<% else %>
    <p>In order to edit your community profile, you will first need to
        <a href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>. Don't have an account?
        <a href="/join/">Join The Foundation</a>
    </p>
    <p>
        <a class="roundedButton" href="/Security/login/?BackURL=%2Fprofile%2F">Login</a>
        <a href="/join/" class="roundedButton">Join The Foundation</a>
    </p>
<% end_if %>