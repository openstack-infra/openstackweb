<div class="container">
<% require themedCSS(presentationupload) %>

<p class="message">Thanks for the help, $Speaker.name! Please click on a presentation below to get your slides uploaded.</p>

<h1>Your Presentations</h1>

<% loop Presentations %>
	<% if HasAttachmentOrLink %>
	<div class="row">
	<div class="col-lg-9">
		<div class="presentation">
			<a href="{$Top.link}Upload/{$ID}" class="presentation-title">$eventtitle</a>
			<% if isFile %>
				<% if UploadedMedia %><br/>Currently attached file: <strong>{$UploadedMedia.Name}</strong><% end_if %>
			<% else %>
				<% if HostedMediaURL %><br/>Current presentation link: <strong><a href="{$HostedMediaURL}">{$HostedMediaURL}</a></strong><% end_if %>
			<% end_if %>
		</div>
	</div>
	<div class="col-lg-3">
		<a href="{$Top.link}Upload/{$ID}" class="roundedButton add-slides">Change...</a>
	</div>
	</div>

	<% else %>
	<div class="row">
	<div class="col-lg-9">
		<div class="presentation no-media">
			<a href="{$Top.link}Upload/{$ID}" class="presentation-title">$eventtitle</a>
			<br/>No slides have been provided yet. Please <a href="{$Top.link}Upload/{$ID}">upload</a> your slides.</strong>
		</div>
	</div>
	<div class="col-lg-3">
		<a href="{$Top.link}Upload/{$ID}" class="roundedButton add-slides">Add Slides</a>
	</div>	
	</div>

	<% end_if %>
<% end_loop %>

<p></p>
<hr/>
<p>If you have any problems with this form, please contact <a href="mailto:events@openstack.org">events@openstack.org</a> and we'll work to help you out. Thanks so much for uploading your presentation.</p>
</div>
