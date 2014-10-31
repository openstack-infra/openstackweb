<div class="container">
	<% if Menu(2) %>
		<div class="row">
			<div class="col-lg-9 col-lg-push-3">
	<% end_if %>

	$Content

	<% include FeatureTable %>

	$Form

	<% if Menu(2) %>
			</div> <!-- Close content div -->
			<div class="col-lg-3 col-lg-pull-9">
				<% include SubMenu %>
			</div>

		</div> <!-- Close row div -->
	<% end_if %>
</div>