<% require javascript(themes/openstack/javascript/filter.js) %>
<% require themedCSS(filter) %>

<h1>Members (with IDs)</h1>

<div id="search">
        <label for="filter">Filter</label> <input type="text" name="filter" value="" id="filter" />
</div>

<h3 id="filterHeading">Search Results</h3>

<% loop MemeberList.GroupedBy(SurnameFirstLetter) %>
	<div class="filter">
    <h3 class="groupHeading">$SurnameFirstLetter</h3>
    <ul>
        <% loop Children %>
        	<% if MoreThanTen %>
            <li>ID: $ID - <strong>$FirstName $Surname</strong> ($CurrentOrgName) $Email</li>
            <% end_if %>
        <% end_loop %>
    </ul>
	</div>
<% end_loop %>
