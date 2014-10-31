<h2>$CompanyName Team Members Administration</h2>

<form name="ccla_teams_form" id="ccla_teams_form">
<div class="status-legend-container">
    <div style="float:left" class="status-base needs-registration"></div><div class="status-legend">Needs Registration</div>
    <div style="float:left" class="status-base needs-confirmation"></div><div class="status-legend">Needs Confirmation</div>
    <div style="float:left" class="status-base member"></div><div class="status-legend">Is Member</div>
</div>
<table id="ccla_teams">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Team</th>
            <th>Date Added</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <tr id="add_member_row">
            <td>
                <input type="text" id="add_member_fname" name="add_member_fname">
            </td>
            <td>
                <input type="text" id="add_member_lname" name="add_member_lname">
            </td>

            <td>
                <input type="text" id="add_member_email" name="add_member_email">
            </td>
            <td>
                $TeamsDLL
            </td>
            <td colspan="3">
                <button id="add_member">Add</button>
            </td>
        </tr>
    </thead>
    <tfoot>
    </tfoot>
    <tbody>
    <% if TeamMembers %>
        <% loop TeamMembers %>
            <tr data-id="{$Id}">
                <td>$FirstName</td>
                <td>$LastName</td>
                <td>$Email</td>
                <td>$TeamName</td>
                <td>$DateAdded</td>
                <td><div class="status-base {$Status}" title="{$Status}"></div></td>
                <td><button class="delete_member" data-team-id="{$TeamId}" data-id="{$Id}" data-status="{$Status}">Delete</button></td>
            </tr>
        <% end_loop %>
    <% end_if %>
    </tbody>
</table>
</form>