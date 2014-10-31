<h2>Event Registration Requests List</h2>
<% if EventRegistrationRequest %>
    <table id="event-registration-requests-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Post Date</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Url</th>
                <th>City</th>
                <th>State</th>
                <th>Country</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <% loop EventRegistrationRequest %>
            <tr>
                <td class="title"><a id="evt{$ID}" href="#"></a>$Title</td>
                <td class="post-date">$PostDate</td>
                <td class="start-date">$StartDate</td>
                <td class="end-date">$EndDate</td>
                <td class="url">$Url</td>
                <td class="city">$City</td>
                <td class="state">$State</td>
                <td class="country">$Country</td>
                <td width="23%">
                    <a href="#" data-request-id="{$ID}" class="edit-event roundedButton addDeploymentBtn">Edit</a>
                    &nbsp;
                    <a href="#" data-request-id="{$ID}" class="reject-event roundedButton addDeploymentBtn">Reject</a>
                    &nbsp;
                    <a href="#" data-request-id="{$ID}" class="post-event roundedButton addDeploymentBtn">Post</a>
                </td>
            </tr>
        <% end_loop %>
        </tbody>
    </table>
<% else %>
    <p>* There are not any Event Registration Requests yet.</p>
<% end_if %>
<div id="edit_dialog" title="Edit Event Registration Request" style="display: none;">
    $EventRegistrationRequestForm
</div>

<div id="dialog-confirm-post" title="Post Event?" style="display: none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure to post this event request?</p>
</div>

<div id="dialog-reject-post" title="Reject Post ?" style="display: none;">
    <form>
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure to reject this event request?</p>
        <div>
            <input id="send_rejection_email" name="send_rejection_email" type="checkbox">send email on rejection to contact point<br>
            <label for"custom_reject_message">Additional Reject Message:</label>
            <textarea style="height: 150px; width: 410px;resize:none;" id="custom_reject_message" name="custom_reject_message"></textarea>
        </div>
    </form>
</div>