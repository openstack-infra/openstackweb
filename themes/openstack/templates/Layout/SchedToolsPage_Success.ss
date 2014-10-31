<div class="container">
<% require themedCSS(presentationupload) %>


<h1>Success!</h1>


<div class="success-box">
<% if IsFile %>
<p>Your file <strong>{$Filename}</strong> for the presentation <strong>{$Presentation.eventtitle}</strong> was uploaded successfully!</p>
<% else %>
<p>The URL for the presentation <strong>{$Presentation.eventtitle}</strong> was successfully set to <a href="$PresentationURL">$PresentationURL</a></p>
<% end_if %>
</div>

<a class="roundedButton" href="{$Top.link}Presentations/">Back To Your Presentations</a>
</div>