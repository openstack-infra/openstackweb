<div class="container">
<% require themedCSS(presentationupload) %>

<% if HasError %>

<h2>Error</h2>
<p>There seems to be an error uploading this presentation.</p>

<% else %>

	<% if Presentation.HasAttachmentOrLink %>
	<h2>Replace Presentation File For "$Presentation.eventtitle"</h2>

		<% if Presentation.IsFile %>
		<p>The presentation <strong>{$Presentation.eventtitle}</strong> currently has the file <strong><a href="{$Presentation.UploadedMedia.URL}">{$Presentation.UploadedMedia.Name}</a></strong> uploaded. If you proceed, your new file or URL will replace the current one.</p>
		<% else %>
		<p>The presentation <strong>{$Presentation.eventtitle}</strong> currently has slides set to be available at <a href="{$Presentation.HostedMediaURL}">{$Presentation.HostedMediaURL}</a>. If you proceed, your new file or URL will replace the current one.</p>
		<% end_if %>

	<% else %>

		<h2>Upload Your Slides For "$Presentation.eventtitle"</h2>
		<p>Please upload a file with your slides or provide a link to where your slides are hosted online. Thank you for the help!</p>

	<% end_if %>

	<h2 class="upload-tabs"><a href="{$Link}Upload/{$Presentation.ID}">Upload a file</a><a href="{$Link}LinkTo/{$Presentation.ID}" class="active">Link to an online presentation</a></h2>

<div id="url-well">
</div>

$LinkToForm

<% end_if %>

<p></p>
<hr/>
<p>If you have any problems with this form, please contact <a href="mailto:events@openstack.org">events@openstack.org</a> and we'll work to help you out. Thanks so much for uploading your presentation.</p>
</div>