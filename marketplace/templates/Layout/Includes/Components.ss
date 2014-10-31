<% if OpenStackAvailableComponents %>
    <hr>
    <form id="components_form" name="components_form">
    <table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;max-width:99%"  width="100%" >
        <tbody><tr>
            <th style="border: 1px solid #ccc;width:15%;">OpenStack-powered Capabilities Offered</th>
            <% loop OpenStackAvailableComponents %>
                <th style="border: 1px solid #ccc;background:#eaeaea;max-width:100px;" width="10%">
                    $Name ($CodeName)
                </th>
            <% end_loop %>
        </tr>
        <tr>
            <th style="border: 1px solid #ccc;">Mark all that apply with an X</th>
            <% loop OpenStackAvailableComponents %>
                <th style="border: 1px solid #ccc;background:#fff;text-align:center;">
                    <input type="checkbox" class="checkbox available-component" id="component_{$ID}" value="{$ID}" data-supports-versioning="{$SupportsVersioning}" name="component_{$ID}">
                </th>
            <% end_loop %>
        </tr>
        <tr>
            <th style="border: 1px solid #ccc;width:20%;">Version of OpenStack used (e..g Grizzly, Havana)</th>
            <% loop OpenStackAvailableComponents %>
                <th style="border: 1px solid #ccc;background:#fff;width:10%;">
                    <div style="display:inline-block;max-width:90%;">
                        <select style="width:100%" id="releases_component_{$ID}" name="releases_component_{$ID}" class="component-releases" data-component-id="{$ID}" data-component-supports-versioning="{$SupportsVersioning}" data-component-codename="{$CodeName}">
                        </select>
                    </div>
                </th>
            <% end_loop %>
        </tr>
        <tr>
            <th style="border: 1px solid #ccc;">API Version Supported</th>
            <% loop OpenStackAvailableComponents %>
                <th style="border: 1px solid #ccc;background:#fff;">
                    <div style="display:inline-block;max-width:90%;">
                    <% if SupportsVersioning %>
                        <select style="width:100%" id="release_api_version_component_{$ID}" name="release_api_version_component_{$ID}" class="release-api-versions" data-component-id="{$ID}">
                            <option value="">-- select --</option>
                        </select>
                    <% else %>
                        <input type="text" name="api_coverage_amount_{$ID}" id="api_coverage_amount_{$ID}" value="N/A" style="border:0; color:#f6931f; font-weight:bold;width: 100%; max-width: 90%;text-align:center;">
                    <% end_if %>
                    </div>
                </th>
            <% end_loop %>
        </tr>
        <tr>
            <th style="border: 1px solid #ccc;">API supported</th>
            <% loop OpenStackAvailableComponents %>
            <th style="border: 1px solid #ccc;background:#fff;">
                    <% if SupportsVersioning %>
                        <select style="width:100%" id="api_coverage_amount_{$ID}" name="api_coverage_amount_{$ID}" class="api-coverage">
                            <option value="">-- select --</option>
                            <option value="0">None</option>
                            <option value="50">Partial</option>
                            <option value="100">Full</option>
                        </select>
                    <% else %>
                        <input type="text" name="api_coverage_amount_{$ID}" id="api_coverage_amount_{$ID}" value="N/A" style="border:0; color:#f6931f; font-weight:bold;width: 100%; max-width: 90%;text-align:center;">
                    <% end_if %>
            </th>
            <% end_loop %>
        </tr>
    </table>
    </form>
<% end_if %>