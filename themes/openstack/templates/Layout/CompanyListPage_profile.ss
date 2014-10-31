<% require themedCSS(profile) %>
<% with Company %>

<div class="span-20"><h3>Company Profile: $Name</h3></div>
<div class="span-4 last supporters"><h4><a href="/foundation/companies/">See All Supporters</a></h4></div>


<hr/>
<div class="span-4 logo">
  <div class="logo">$Logo.SetWidth(100)</div>
  <p></p>
  <p><a href="$URL" class="roundedButton">Website</a></p>

</div>


<div class="span-20 last">

<div class="company-profile">
<h1>$Name</h1>
<% if Industry %><h3 id="Industry">$Industry</h3><% end_if %>
<h4><% if City %>$City<% end_if %>
  <% if Country %>, $Country<% end_if %> </h4>


  <% if Description %>
  	<hr/>
 	<div id="Description">
 	 	<h4>Description</strong></h4>
 	 	<div>$Description</div>
 	 	<p></p>

	</div>
  <% end_if %>

  <% if Contributions %>
  	<hr/>
 	<div id="Contributions">
 	 	<h4>Contributions To OpenStack From $Name</strong></h4>
 	 	<div>$Contributions</div>
 	 	<p></p>
	</div>
  <% end_if %>  

  <% if Products %>
  	<hr/>
 	<div id="Products">
 	 	<h4>Products &amp; Services</strong></h4>
 	 	<div>$Products</div>
 	 	<p></p>

	</div>
  <% end_if %>

  <% if ContactEmail %>
  	<hr/>
 	<div id="Contact">
 	 	<h4>For More Information</strong></h4>
 	 	<div>Please contact us at <a href="mailto:$ContactEmail">$ContactEmail</div>
 	 	<p></p>

	</div>
  <% end_if %>  

</div>

<div class="span-24 last"><p></p></div>
</div>

<% end_with %>