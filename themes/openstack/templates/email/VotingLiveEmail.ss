<html>
<body>

<p>$Recipient.FirstName $Recipient.Surname --</p>

<p>Your <% if MultipleTalks %>presentations are<% else %>presentation is<% end_if %> now available to the OpenStack community for online voting! Please encourage your friends and colleagues in the OpenStack community to vote for your <% if MultipleTalks %>presentations<% else %>presentation<% end_if %>.</p>

<% if AdminTalks %>

<ul>
	<% loop AdminTalks %>
		<li>$PresentationTitle <% if Speakers %> ( <% if SpeakerCount=1 %>Speaker:<% else %>Speakers:<% end_if %> <% loop Speakers %><% if Last %>$FirstName $Surname<% else %>{$FirstName} {$Surname}, <% end_if %> <% end_loop %>) <% end_if %> <br/>
			<a href="https://www.openstack.org/vote-paris/Presentation/{$URLSegment}">https://www.openstack.org/vote-paris/Presentation/{$URLSegment}</a>
	<% end_loop %>
</ul>

<% end_if %>

<% if SpeakerTalks %>

<p>Here <% if MultipleTalks %>are the presentations<% else %>is the presentation<% end_if %> someone else submitted with you as a speaker:</p>

<ul>
	<% loop SpeakerTalks %>
		<li>$PresentationTitle <% if Speakers %> submitted by $Owner.FirstName $Owner.Surname ( <% if SpeakerCount=1 %>Speaker:<% else %>Speakers:<% end_if %> <% loop Speakers %><% if Last %>$FirstName $Surname<% else %>{$FirstName} {$Surname}, <% end_if %> <% end_loop %>) <% end_if %><br/>
			<a href="https://www.openstack.org/vote-paris/Presentation/{$URLSegment}">https://www.openstack.org/vote-paris/Presentation/{$URLSegment}</a>
	<% end_loop %>
</ul>

<% end_if %>

<p>To review and edit presentations, please log in with your email address and password here:
<a href="https://www.openstack.org/summit/openstack-paris-summit-2014/call-for-speakers/">https://www.openstack.org/summit/openstack-paris-summit-2014/call-for-speakers/</a></p>

<p>If you don't remember your password, you can have it reset:<br/>
<a href="https://www.openstack.org/Security/lostpassword">https://www.openstack.org/Security/lostpassword</a></p>

<p>Two more quick items:</p>

<ul>
	<li>If you are interested in sponsoring the summit as well, it's not too late. Please email events@openstack.org no later than September 19, 2014.</li>
	<li>Finally, Discounted Early Registration closes August 27, 2014. All speakers who are selected will receive a free code to register for the Summit in mid-August.  All others who were not selected will have time to register at the early discounted rate.</li>
</ul>

<p>Continue to check <a href="http://www.openstack.org/summit/">http://www.openstack.org/summit/</a> for updates.</p>

<p>Good luck with your speaking submissions! If you have any questions along the way, please don't hesitate to ask. You can reach us at events@opesntack.org.</p>

<p>The OpenStack Summit Team</p>

</body>
</html>