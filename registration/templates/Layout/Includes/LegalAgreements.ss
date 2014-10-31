
			<% if LegalAgreements %>

			<h2>Your Current Legal Agreements</h2>
			<ul class="LegalAgreements">
				<% loop LegalAgreements %>
					<li><a href="{$DocumentLink}">$DocumentName</a> (Signed: {$Created.Month} $Created.format(d), $Created.Year)</li>
				<% end_loop %>
			</ul>
			<% else %>

			<p>You have no current legal agreements.</p>


			<% end_if %>
