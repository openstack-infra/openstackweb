<div style="clear:both;">
    <h2>Search Company Private Clouds</h2>
    <div class="addDeploymentForm">
        <form id="search_private_clouds" name="search_private_clouds" action="$Top.Link(private_clouds)">
            <table class="main-table">
                <thead>
                <tr>
                    <th>Filter Products</th>
                    <th>Company Name</th>
                    <th>Search</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="text" value="" name="name" id="name">
                    </td>
                    <td>
                        <select name="company_id" id="company_id">
                            <option  value="">--select--</option>
                            <% if Companies %>
                                <% loop Companies %>
                                    <option  value="$ID">$Name</option>
                                <% end_loop %>
                            <% end_if %>
                        </select>
                    </td>
                    <td>
                        <input type="submit" style="white-space: nowrap;" value="Search" class="roundedButton">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    <div style="clear:both;">
        <h2>Company Pirvate Clouds</h2>
        <p>Click heading to sort:</p>
        <table class="main-table">
            <thead>
            <tr>
                <th><a href="$Top.Link(private_clouds)?sort=company">Company ^</a></th>
                <th><a href="$Top.Link(private_clouds)?sort=name">Product Name ^</a></th>
                <th><a href="$Top.Link(private_clouds)?sort=status">Status ^</a></th>
                <th><a href="$Top.Link(private_clouds)?sort=updated">Last Update ^</a></th>
                <% if Top.isSuperAdmin %>
                    <th><a href="$Top.Link(private_clouds)?sort=updatedby">Updated By ^</a></th>
                <% end_if %>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
                <% if PrivateClouds %>
                    <% loop PrivateClouds %>
                    <tr>
                        <td>
                            $Company.Name
                        </td>
                        <td>
                            $Name
                        </td>
                        <td>
                            <% if Active %>Active<% else %>Deactivated<% end_if %>
                        </td>
                        <td>$LastEdited</td>
                        <% if Top.isSuperAdmin %>
                            <td>
                                <% if EditedBy %>
                                    <% with EditedBy %>
                                        $Email ($CurrentCompany)
                                    <% end_with %>
                                <% else %>
                                    N/A
                                <% end_if %>
                            </td>
                        <% end_if %>
                        <td style="min-width: 200px" width="30%">
                            <a class="product-button roundedButton addDeploymentBtn" href="$Top.Link(private_cloud)?id=$ID">Edit Product Details</a>
                            <a class="roundedButton delete-private-cloud product-button addDeploymentBtn" href="#" data-id="{$ID}">Delete Product</a>
                        </td>
                    </tr>
                    <% end_loop %>
                <% end_if %>
            </tbody>
        </table>
    </div>
</div>
