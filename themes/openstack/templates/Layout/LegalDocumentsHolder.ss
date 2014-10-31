
$Content

<% if Children %>
	<h2>OpenStack Legal Documents</h2>
	<ul id="legal-documents">
	<% loop Children %>
		<li><a href="{$Link}">$Title</a></li>
	<% end_loop %>
	</ul>
<% end_if %>