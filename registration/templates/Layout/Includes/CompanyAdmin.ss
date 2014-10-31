<% if CompanyAdmin %>
			<p></p>
			<hr/>
			<h2>Company Profiles You Administer</h2>
			<ul class="LegalAgreements">
				<% loop CompanyAdmin %>
					<li><a href="{$EditLink}">$Name</a></li>
				<% end_loop %>
			</ul>
<% end_if %>
