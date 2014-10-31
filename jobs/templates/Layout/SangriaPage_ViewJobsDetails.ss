<h2>Job Registration Requests List</h2>
<% if JobRegistrationRequests %>
    <table id="job-registration-requests-table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Post Date</th>
            <th>Url</th>
            <th>Company</th>
            <th>Location Type</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
            <% loop JobRegistrationRequests %>
            <tr>
                <td class="title"><a id="job{$ID}" href="#"></a>$Title</td>
                <td class="post-date">$PostDate</td>
                <td class="url">$Url</td>
                <td class="company-name">$CompanyName</td>
                <td class="location_type">$LocationType</td>
                <td width="23%">
                    <a href="#" data-request-id="{$ID}" class="edit-job roundedButton addDeploymentBtn">Edit</a>
                    &nbsp;
                    <a href="#" data-request-id="{$ID}" class="reject-job roundedButton addDeploymentBtn">Reject</a>
                    &nbsp;
                    <a href="#" data-request-id="{$ID}" class="post-job roundedButton addDeploymentBtn">Post</a>
                </td>
            </tr>
            <% end_loop %>
        </tbody>
    </table>
<% else %>
    <p>* There are not any Job Registration Requests yet.</p>
<% end_if %>
<div id="edit_dialog" title="Edit Job Registration Request" style="display: none;">
    $JobRegistrationRequestForm
</div>

<div id="dialog-confirm-post" title="Post Job?" style="display: none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure to post this job request?</p>
</div>

<div id="dialog-reject-post" title="Reject Post ?" style="display: none;">
    <form>
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure to reject this job request?</p>
        <div>
            <input id="send_rejection_email" name="send_rejection_email" type="checkbox">send email on rejection to contact point<br>
            <label for"custom_reject_message">Additional Reject Message:</label>
            <textarea style="height: 150px; width: 410px;resize:none;" id="custom_reject_message" name="custom_reject_message"></textarea>
        </div>
    </form>
</div>