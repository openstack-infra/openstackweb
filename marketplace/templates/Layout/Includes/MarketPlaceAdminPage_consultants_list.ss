<div style="clear:both;">
    <h2>Search Consultants</h2>
    <div class="addDeploymentForm">
        <form id="search_consultants" name="search_consultants" action="$Top.Link(consultants)">
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
        <h2>Companies Consultant Services</h2>
        <p>Click heading to sort:</p>
        <table class="main-table">
            <thead>
                <tr>
                    <th><a href="$Top.Link(consultants)?sort=company">Company ^</a></th>
                    <th><a href="$Top.Link(consultants)?sort=name">Service Name ^</a></th>
                    <th>Published</th>
                    <th><a href="$Top.Link(consultants)?sort=status">Status ^</a></th>
                    <th><a href="$Top.Link(consultants)?sort=updated">Last Update ^</a></th>
                    <% if Top.isSuperAdmin %>
                        <th><a href="$Top.Link(consultants)?sort=updatedby">Updated By ^</a></th>
                    <% end_if %>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <% if Consultants %>
                    <% loop Consultants %>
                    <tr>
                        <td>
                            $Company.Name
                        </td>
                        <td>
                            $Name
                        </td>
                        <td>
                            <% if LiveServiceID == 0 %>Draft<% else %>Published<% end_if %>
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
                            <a class="product-button roundedButton addDeploymentBtn" href="$Top.Link(consultant)?id=$ID&is_draft=$isDraft">Edit Product Details</a>
                            <a target="_blank" class="product-button roundedButton addDeploymentBtn" href="$Top.Link(consultant)/$ID/<% if isDraft  %>draft_<% end_if %>preview">Preview Product</a>
                            <a target="_blank" class="product-button roundedButton addDeploymentBtn" href="$Top.Link(consultant)/$ID/<% if isDraft  %>draft_<% end_if %>pdf">PDF</a>
                            <a class="roundedButton delete-consultant product-button addDeploymentBtn" href="#" data-id="{$ID}" data-is_draft="{$isDraft}">Delete Product</a>
                        </td>
                    </tr>
                    <% end_loop %>
                <% end_if %>
            </tbody>
        </table>
    </div>
</div>
