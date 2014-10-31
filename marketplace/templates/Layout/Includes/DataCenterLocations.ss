<hr>
<form id="data-centers-form" name="data-centers-form">
    <h2 id="">Data Center Regions</h2>
    <div style="clear:both;">
        <table id="datacenter-regions-table" style="border: 1px solid #ccc; border-collapse:collapse;clear:both;width:70%;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ccc !important;background:#eaeaea;width:40%;">Region Name</th>
                    <th style="border: 1px solid #ccc !important;background:#eaeaea;width:25%;">Region Color</th>
                    <th style="border: 1px solid #ccc !important;background:#eaeaea;width:30%;">API Endpoint</th>
                    <th style="border: 1px solid #ccc !important;background:#eaeaea;width:5%;">Add/Remove</th>
                </tr>
            </thead>
            <tbody>
            <tr class="add-additional-datacenter-region">
                <td style="border: 1px solid #ccc;width:40%;background:#fff;">
                    <input type="text" class="text autocompleteoff add-control add-region-control" id="add_region_name" name="add_region_name" value="" style="width:300px;">
                </td>
                <td style="border: 1px solid #ccc;width:25%;background:#fff;">
                    <input type="text" class="text autocompleteoff add-control add-region-control" id="add_region_color" name="add_region_color" value="" style="width:50px;" maxlength="6">
                </td>
                <td style="border: 1px solid #ccc;width:30%;background:#fff;">
                    <input type="text" class="text autocompleteoff add-control add-region-control" id="add_region_endpoint" name="add_region_endpoint" value="" style="width:300px;">
                </td>
                <td style="border: 1px solid #ccc;background:#eaeaea;width:5%;color:#cc0000;">
                    <a href="#" id="add-new-datacenter-region">+&nbsp;Add</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <h2 id="">DC Locations (fill out fields per each Datacenter)</h2>
    <div id="data-center-locations-container"></div>
    <div style="padding-top:40px;display:inline;width:80%;margin-bottom:30px;" class="location-info-container">
        <div style="float:left;display:inline;width:15%;">
            <label for="add-datacenter-location-city" class="left">City:</label>
            <input type="text" class="text autocompleteoff add-control add-location-control location-city"
                   id="add-datacenter-location-city"
                   name="add-datacenter-location-city" value="" style="width:80%;" maxlength="125">
        </div>
        <div style="float:left;display:inline;width:15%;">
            <label for="add-datacenter-location-state" class="left">State:</label>
            <input type="text" class="text autocompleteoff add-control add-location-control location-state"
                   id="add-datacenter-location-state" name="add-datacenter-location-state" value="" style="width:80%;" maxlength="125">
        </div>
        <div style="float:left;display:inline;padding-left:10px">
            <label for="add-datacenter-location-country" class="left">Country:</label><br>
            <div style="display:inline-block;max-width:200px;">
                $getCountriesDDL(add-datacenter-location-country)
            </div>
        </div>
        <div style="float:left;display:inline;padding-left:10px">
            <label for="add-datacenter-location-region" class="left">Region Name:</label><br>
            <div style="display:inline-block;max-width:200px;">
                <select id="add-datacenter-location-region"
                        name="add-datacenter-location-region"
                        class="add-control add-location-control add-datacenter-location-region location-region"
                        style="width:100%">
                    <option value="">--select region--</option>
                </select>
            </div>
        </div>
        <div style="float:left;width:20%;">
            <a href="#" class="roundedButton addDeploymentBtn" style="overflow:hidden;white-space: nowrap;font-weight:normal;float:right;display:inline;" name="add-datacenter-location" id="add-datacenter-location">Add Data Center</a>
        </div>
    </div>
    <div style="border: 1px solid #ccc; border-collapse:collapse;clear:both;width:70%;padding-left:30px;padding-top:10px;padding-bottom:10px;">
        <strong>Zones covered by your Data Center</strong>
        Please tell us which zones your data center services:<br>
        <table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;width:90%;margin:0;padding:0;" id="az-table">
            <thead>
            <tr>
                <th style="border: 1px solid #ccc;background:#eaeaea;width:10%;">Zone Name</th>
                <th style="border: 1px solid #ccc;background:#eaeaea;width:10%;">Add/Remove</th>
            </tr>
            </thead>
            <tbody>
            <tr class="add-az-row">
                <td style="border: 1px solid #ccc;width:45%;">
                    <input type="text" class="zone-name text add-control add-location-control add-az-control" id="add-datacenter-location-zone-name" name="add-datacenter-location-zone-name" maxlength="125" value="" style="width:90%;">
                </td>
                <td style="border: 1px solid #ccc;;width:10%;color:#cc0000;">
                    <a href="#" name="add-az" id="add-az">+&nbsp;Add</a>
                </td>
            </tr>
            </tbody></table>
    </div>
</form>