<% if CurrentMember %>
    <div class="loggedInBox">You are logged in as: <strong>$CurrentMember.Name</strong>&nbsp;&nbsp;<a class="roundedButton" href="{$Link}logout/">Logout</a></div>
<% end_if %>
<div>
    <h1>Post a New Job</h1>
    <p></p>
</div>

<% if Saved %>
    <div class="siteMessage" id="SuccessMessage" style="padding: 10px;">
        <p style="float:left;">Your Job has been saved!</p>
        <input type="button" title="Add New Job" value="Add New Job" data-url="{$Top.Link}" name="add-new-job" id="add-new-job" class="action">
    </div>
<% else %>
    <div>
        $JobRegistrationRequestForm
    </div>
<% end_if %>