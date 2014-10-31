<h2>Activity feed</h2>
<dl>
	<% loop Feeds %>
		<dt <% if First %>class="first"<% end_if %> >$ClassName</dt>
		<dd><a href="$Data">$Name</a></dd>
	<% end_loop %>
</dl>