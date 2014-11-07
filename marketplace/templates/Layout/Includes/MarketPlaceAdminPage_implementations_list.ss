<div style="clear:both;">
    <h2>Search Company Products</h2>
    <div class="addDeploymentForm">
        <form id="search_distributions" name="search_distributions" action="{$Top.Link}">
            <table class="main-table">
                <thead>
                <tr>
                    <th>Filter Products</th>
                    <th>Type</th>
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
                        <select name="implementation_type_id" id="implementation_type_id">
                            <option  value="">--select--</option>
                            <% if DistributionMarketPlaceTypes %>
                                <% control DistributionMarketPlaceTypes %>
                                    <option  value="$ID">$Name</option>
                                <% end_control %>
                            <% end_if %>
                        </select>
                    </td>
                    <td>
                        <select name="company_id" id="company_id">
                            <option  value="">--select--</option>
                            <% if Companies %>
                                <% control Companies %>
                                    <option  value="$ID">$Name</option>
                                <% end_control %>
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
        <h2>Company Products</h2>
        <p>Click heading to sort:</p>
        <table class="main-table">
            <thead>
                <tr>
                    <th><a href="$Top.Link?sort=company">Company ^</a></th>
                    <th><a href="$Top.Link?sort=name">Product Name ^</a></th>
                    <th><a href="$Top.Link?sort=name">Product Type ^</a></th>
                    <th><a href="$Top.Link?sort=status">Status ^</a></th>
                    <th><a href="$Top.Link?sort=updated">Last Update ^</a></th>
                    <% if Top.isSuperAdmin %>
                    <th><a href="$Top.Link?sort=updatedby">Updated By ^</a></th>
                    <% end_if %>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <% if Distributions %>
                    <% control Distributions %>
                    <tr>
                        <td>
                            $Company.Name
                        </td>
                        <td>
                            $Name
                        </td>
                        <td>
                            $MarketPlace.Name
                        </td>
                        <td>
                            <% if Active %>Active<% else %>Deactivated<% end_if %>
                        </td>
                        <td>$LastEdited</td>
                        <% if Top.isSuperAdmin %>
                        <td>
                            <% if EditedBy %>
                            <% control EditedBy %>
                                $Email ($CurrentCompany)
                            <% end_control %>
                            <% else %>
                                N/A
                            <% end_if %>
                        </td>
                        <% end_if %>
                        <td style="min-width: 200px" width="30%">
                            <a class="product-button roundedButton addDeploymentBtn" href="<% control MarketPlace  %><% if Name == "Appliance"  %>$Top.Link(appliance)<% end_if %><% if Name == "Distribution"  %>$Top.Link(distribution)<% end_if %><% end_control %>?id=$ID">Edit Product Details</a>
                            <a target="_blank" class="product-button roundedButton addDeploymentBtn" href="<% control MarketPlace  %><% if Name == "Appliance"  %>$Top.Link(appliance)<% end_if %><% if Name == "Distribution"  %>$Top.Link(distribution)<% end_if %><% end_control %>/$ID/preview">Preview Product</a>
                            <a target="_blank" class="product-button roundedButton addDeploymentBtn" href="<% control MarketPlace  %><% if Name == "Appliance"  %>$Top.Link(appliance)<% end_if %><% if Name == "Distribution"  %>$Top.Link(distribution)<% end_if %><% end_control %>/$ID/pdf">PDF</a>
                            <a class="roundedButton delete-implementation product-button addDeploymentBtn" href="#"
                               data-id="{$ID}"
                               data-class="<% control MarketPlace  %><% if Name == "Appliance"  %>appliance<% end_if %><% if Name == "Distribution"  %>distribution<% end_if %><% end_control %>">Delete Product</a>
                        </td>
                    </tr>
                    <% end_control %>
                <% end_if %>
            </tbody>
        </table>
    </div>
</div>
