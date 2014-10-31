<div class="container">
	
	<% require themedCSS(conference) %>
	<% require themedCSS(chosen) %>
	<% require javascript(themes/openstack/javascript/chosen.jquery.min.js) %>
	
		<h2>$Title</h2>
	
		$Content	
		$RegistrationForm
	    <div id="affiliation-edition-dialog">
	        $AffiliationEditForm
	    </div>
	
	<script type="text/javascript"> $("#Form_RegistrationForm_Country").chosen();</script>
</div></div>