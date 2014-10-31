<h2>$AnnouncementsNotes</h2>
<dl>
<% loop LatestAnnouncements %>
	<dt <% if First %>class="first"<% end_if %> ></dt>
	<dd>$Content</dd>
<% end_loop %>
</dl> 