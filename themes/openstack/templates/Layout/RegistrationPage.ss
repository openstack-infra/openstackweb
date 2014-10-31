<% require themedCSS(conference) %>
<% require javascript(themes/openstack/javascript/tag-it.js) %>

$Content

<h2>1. Read over the terms of becoming an OpenStack Foundation Individual Member.</h2>
<div class="termsBox">

<% loop LegalTerms %>
$Content
<% end_loop %>

</div>
<p style="margin-top:40px;"></p>
<h2>2. Complete The Individual Member Application.</h2>
<p>By completing the application and creating an account, you agree to the terms of the Individual Member Agreement above.</p>
$RegistrationForm
<div id="affiliation-edition-dialog">
    $AffiliationEditForm
</div>

