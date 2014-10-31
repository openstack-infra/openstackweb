<% if CurrentMember %>
<div class="loggedInBox">You are logged in as: <strong>$CurrentMember.Name</strong>&nbsp;&nbsp;<a class="roundedButton" href="{$Link}logout/">Logout</a></div>
<% end_if %>
<div>
    <h1>Post a New Event</h1>
    <p></p>
</div>

<% if Saved %>
    <div class="siteMessage" id="SuccessMessage" style="padding: 10px;">
        <p style="float:left;">Your Event has been saved!</p>
        <input type="button" title="Add New Event" value="Add New Event" data-url="{$Top.Link}" name="add-new-event" id="add-new-event" class="action">
    </div>
<% else %>
<div>
    $EventRegistrationRequestForm
</div>
<% end_if %>