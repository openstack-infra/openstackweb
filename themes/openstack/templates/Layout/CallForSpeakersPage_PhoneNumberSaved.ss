<% require themedCSS(conference) %> 

<% with Parent %>
$HeaderArea
<% end_with %>

<div class="span-5">
		<p><strong>The OpenStack Summit</strong><br />$Parent.MenuTitle.XML</p>
	<ul class="navigation">
		<% loop Parent %>
			<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>Overview</span></a></li>
		<% end_loop %>
		<% loop Menu(3) %>
		  		<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>$MenuTitle.XML</span></a></li>
	   	<% end_loop %>
	</ul>

</div> 

<!-- Content Area -->


<div class="prepend-1 span-11" id="news-feed">

	<div class="span-18 last">

        <h2>Your phone number was carefully saved. Thanks!</h2>

        <p>In the coming days we will be in touch again with important information including your session day/time and presentation details, so please review it carefully.</p>

	</div>
	
</div>