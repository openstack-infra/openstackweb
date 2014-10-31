<% require themedCSS(legal) %>

<% loop Parent %>
<a href="$Link" class="breadcrumb">$Title</a> /
<% end_loop %>
<h1>$Title</h1>
$Content


<% if LegalDocumentFile %>

<ul id="legal-documents"><li><a href="$LegalDocumentFile.URL">$LegalDocumentFile.Title ($LegalDocumentFile.Size $LegalDocumentFile.FileType)</a></li>
</ul>

<% end_if %>


$Form