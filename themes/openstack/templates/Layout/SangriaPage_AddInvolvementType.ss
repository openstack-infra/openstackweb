<h2>Add an Involvement Type</h2>
<h3>Involvement Types</h3>
<% if InvolvementTypes %>
<% loop InvolvementTypes %>
	<p>$Name</p>
<% end_loop %>
<% end_if %>
$AddInvolvementTypeForm