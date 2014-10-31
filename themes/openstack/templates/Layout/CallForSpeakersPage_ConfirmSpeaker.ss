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

        <h2>Hello $ConfirmedSpeaker.FirstName $ConfirmedSpeaker.Surname! Thanks for confirming your availability to speak at the Paris Summit.</h2>

		<p><strong>ONE MORE STEP: To help ensure great communication and coordination, please provide a phone number that we can reach you at while onsite at the Summit.</strong></p>

		$OnsitePhoneForm

	</div>
	
</div>