<h2 id="EditProfileForm_EditProfileForm_HeaderFieldFirst-Last-Name">Product Details</h2>
<div class="field dropdown " id="CompanyName">
    <label class="left">Company Name</label>
    <div class="middleColumn">
        <select name="company_id" id="company_id">
            <option  value="">--select--</option>
            <% if Companies %>
                <% loop Companies %>
                    <option  value="$ID">$Name</option>
                <% end_loop %>
            <% end_if %>
        </select>
    </div>
    <div class="field text " id="ProductName">
        <label class="left">Product Name</label>
        <div class="middleColumn">
            <input type="text" name="name" id="name" class="text" >
        </div>
    </div>
    <div class="field textarea " id="ProductOverview">
        <label class="left">Product Overview<em>(limit 250 char)</em></label>
        <div class="middleColumn">
            <textarea cols="15" rows="5" name="overview" id="overview"></textarea>
        </div>
    </div>
</div>
<div class="field text " id="ProductName">
    <label class="left">Call to Action Link (URL) for "Get More Information" (Link to your website)</label>
    <div class="middleColumn">
        <input type="text" value="" name="call_2_action_uri" id="call_2_action_uri" class="text">
    </div>
</div>
<div class="field text " id="ProductActive">
    <label class="left">Active</label>
    <div class="middleColumn">
        <input type="checkbox" name="active" checked id="active">
    </div>
</div>
<input id="id" name="id" type="hidden" value="0"/>
<input id="live_id" name="live_id" type="hidden" value="0"/>
