<h2>Foundation Members</h2>

<form method="get" id="form-export-foundation-members" name="form-export-foundation-members" action="$Link(exportFoundationMembers)">
    <span>Fields</span><br>
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="ID"/>ID
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="FirstName"/>FirstName
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="SurName"/>SurName
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="Email"/>Email<br>
    <input id="ext" name="ext" type="hidden" value="">
    <button style="padding: 5px" id="btn1_xls">Export Foundation Members (XLS)</button>
    <button style="padding: 5px" id="btn1_csv">Export Foundation Members (CSV)</button>
</form>
<br/>
<br/>
<h2>Company Data</h2>
<form method="get" id="form-export-company-data" name="form-export-company-data" action="$Link(exportCorporateSponsors)">
    <span>Fields</span><br>
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="MemberLevel"/>MemberLevel
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="Name"/>Name
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="City"/>City
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="State"/>State
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="Country"/>Country
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="Industry"/>Industry
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="ContactEmail"/>ContactEmail
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="AdminEmail"/>AdminEmail
    <BR>
    <span>Levels</span><br>
    <input id="levels[]" name="levels[]"  checked type="checkbox" value="Platinum"/>Platinum
    <input id="levels[]" name="levels[]"  checked type="checkbox" value="Gold"/>Gold
    <input id="levels[]" name="levels[]"  checked type="checkbox" value="Startup"/>Startup
    <input id="levels[]" name="levels[]"  checked type="checkbox" value="Mention"/>Mention<br>
    <input id="ext" name="ext" type="hidden" value="">
    <button style="padding: 5px" id="btn2_xls">Export Company Data (XLS)</button>
    <button style="padding: 5px" id="btn2_csv">Export Company Data (CSV)</button>
</form>
<br/>
<br/>
<h2>CLA Users</h2>
<form method="get" id="form-export-cla-users-data" name="form-export-cla-users-data" action="$Link(exportCLAUsers)">
    <% if Groups %>
        <span>Group Filter </span><input id="status_all" class="all_group" checked  name="status_all"  type="checkbox"  value/>Check All<br>
        <ul style="list-style: none;">
            <% loop Groups %>
            <li><input id="status[]" name="status[]" class="group"  type="checkbox" checked value="$Code"/>$Title</li>
        <% end_loop %>
        </ul>
    <% end_if %>
    <span>Fields</span><br>
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="ID"/>ID
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="FirstName"/>FirstName
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="SurName"/>SurName
    <input id="fields[]" name="fields[]"  checked type="checkbox" value="Email"/>Email<br>
    <input id="ext" name="ext" type="hidden" value="">
    <BR>
    <button style="padding: 5px" id="btn3_xls">Export CLA Users (XLS)</button>
    <button style="padding: 5px" id="btn3_csv">Export CLA Users (CSV)</button>
</form>
<br/>
<br/>
<h2>Gerrit Users with Foundation Member Status</h2>
<form method="get" id="form-export-gerrit-users-data" name="form-export-gerrit-users-data" action="$Link(exportGerritUsers)">
    <span>Status Filter </span><br>
    <input id="status[]" name="status[]"  type="checkbox" checked value="foundation-members"/>Foundation Members
    <input id="status[]" name="status[]"  type="checkbox" checked value="community-members"/>Community Members
    <input id="ext" name="ext" type="hidden" value="">
    <BR>
    <BR>
    <button style="padding: 5px" id="btn4_xls">Export Gerrit Users (XLS)</button>
    <button style="padding: 5px" id="btn4_csv">Export Gerrit Users (CSV)</button>
</form>




