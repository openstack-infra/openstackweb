<div id="content" class="typography">
	
	<h2>$Title</h2>
	
	<% if SearchQuery %>
		<% if Results.Count %>
		    <ul id="results">
		      <% loop Results %>
		        <li>
					<p><a href="{$Top.Link}profile/{$ID}">$FirstName $Surname</a></p>
		        </li>
		      <% end_loop %>
		    </ul>
		 <% else %>
		    <p>Sorry, your search query did not return any results</p>
		 <% end_if %>
	<% end_if %>	

	<h2>Search again</h2>
	$MemberSearchForm

</div>