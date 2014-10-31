
<% require themedCSS(bio) %>

<h1>$Title</h1>

$Content

<% loop Children %>

<hr/>
<a name="{$ID}"></a>
<div class="span-4">
  <div class="photo">$Photo.SetWidth(100)</div>
  <p>&nbsp;</p>
</div>


<div class="span-20 last">

  <h3>$FirstName $LastName</h3>
  <% if Role %><h4 class="role">$Role</h4><% end_if %>

  <div class="span-2"><strong>Job Title</strong></div>
  <div class="span-18 last">$JobTitle &nbsp;</div>

  <div class="span-2"><strong>Company</strong></div>
  <div class="span-18 last">$Company &nbsp;</div>

  <% if Bio %><div class="span-2"><strong>Bio</strong></div>
  <div class="span-18 last">$Bio &nbsp;</div><% end_if %>

</div>


<div class="span-24 last"><p></p></div>


<% end_loop %>