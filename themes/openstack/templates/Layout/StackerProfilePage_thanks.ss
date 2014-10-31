<% require themedCSS(conference) %> 

<% loop Parent %>
$HeaderArea
<% end_loop %>

<div class="span-5">
	<p><strong>Design Summit &amp; Conference</strong><br />San Francisco 2012</p>
	<ul class="navigation">
		<% loop Parent %>
			<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>Overview</span></a></li>
		<% end_loop %>
		<% loop Menu(3) %>
		  		<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>$MenuTitle.XML</span></a></li>
	   	<% end_loop %>
	</ul>

	
	<% loop Parent %>
		<% include HeadlineSponsors %>
	<% end_loop %>


</div> 

<!-- Content Area -->

<div class="prepend-1 span-11" id="news-feed">

	<div class="span-18 last">
		<h2>Thanks! Now, here's your OpenStack Summit Registration Code</h2>

		<div class="summit-code">
			<h3>Your OpenStack Summit registration code is: <span class="code">$RegistrationCode</span></h3>
			<p><strong>The Conference:</strong> You can register and claim your free pass for The OpenStack Summit here:<br/> <a href="http://openstacksummitfall2012.eventbrite.com/?discount={$RegistrationCode}">http://openstacksummitfall2012.eventbrite.com/?discount={$RegistrationCode}</a></p>
			<p><strong>The Hotel:</strong> Also, we have a special OpenStack rate available at the Grand Hyatt where the conference will be hosted. You can book your room here: 
			<a href="http://bit.ly/sandiegohyatt">http://bit.ly/sandiegohyatt</a>
			<p><a class="roundedButton" href="http://openstacksummitfall2012.eventbrite.com/?discount={$RegistrationCode}">Register Now On EventBrite</a>&nbsp;<a class="roundedButton" href="http://bit.ly/sandiegohyatt">Book Your Hotel Stay</a></p>
		</div>
		<p>Thanks again for helping us out. We'll see you at The OpenStack Summit!</p>
	</div>
	
</div>