
<div class="container">
	<% if Menu(2) %>
		<div class="row">
			<div class="col-lg-9 col-lg-push-3">
	<% end_if %>

	$Preamble
	
	<div class="termsBox">
	$Content
	</div>
	
	<p>Click <strong>Agree &amp; Continue</strong> to proceed to the next step.</p>
	$GuidelinesForm

	<% if Menu(2) %>
			</div> <!-- Close content div -->
			<div class="col-lg-3 col-lg-pull-9">
				<% include SubMenu %>
			</div>

		</div> <!-- Close row div -->
	<% end_if %>
</div>