	<% if Menu(2) %>
		<div id="subnav" class="span-5">
			<% include SubMenu %>
		</div>
		<div class="span-19 last">
	<% else %>
		<div class="span-24 last">
	<% end_if %>
 
    $Content
    
        <% if CommMembers %>
        <ul id="memberlist-list">
            <% loop CommMembers %>
            <li>
                <a href="$Link">$Photo.CroppedImage(200,100)</a>
                <h2><a href="$Link">$Name</a></h2>
            </li>
            <% end_loop %>
        </ul>
        <% end_if %>