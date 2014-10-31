<html>
<body>

<% loop Admin %>
<p>$FirstName $Surname --</p>
<% end_loop %>

<p>Hello! We're emailing you about your presentation submissions to the OpenStack Summit in Hong Kong. We wanted to provide you with a few quick updates.</p> 

<% if AdminIsASpeaker %>
<p>(Note: It looks like you not only entered some presentations, but you are also a speaker. You should have received a separate email with your speaking details.)</p>
<% end_if %>

<%-- Accepted talks --%>
<% if AcceptedTalks %>

<p>Congratulations! Your <% if AcceptedTalksCount=1 %>submission has<% else %>submissions have<% end_if %> been accepted for inclusion in the OpenStack Summit in Hong Kong.</p> 

<p>Accepted For The Summit:</p>

<ul>
<% loop AcceptedTalks %>
<li>$PresentationTitle <% if Speakers %> ( Speakers: <% with Speakers %><% if Last %>$FirstName $Surname<% else %>{$FirstName} {$Surname}, <% end_if %> <% end_with %>)<% end_if %></li>
<% end_loop %>
</ul>

<% if AlternateTalks %>
	<p>Also, <% if AlternateTalksCount=1 %>one more presentation has been<% else %>some more presentations have<% end_if %> been approved as an <strong>Alternate</strong> Session for potential inclusion in the OpenStack Summit in Hong Kong.  If, for various reasons, other chosen speakers cannot attend the Summit and a slot becomes available on the agenda for your session to be included -  then we will reach out to you at that time.  Please be patient.</p> 

	<p>Selected As Alternates:</p>

	<ul>

	<% loop AlternateTalks %>
	<li>$PresentationTitle</li>
	<% end_loop %>

	</ul>
<% end_if %>


<% if UnacceptedTalks %>
	<p>Unfortunately, <% if UnacceptedTalksCount=1 %>this other submission was<% else %>these other submissions were<% end_if %> not chosen to be part of the official agenda this time around.</p>

	<p>Not selected:</p>

	<ul>

	<% loop UnacceptedTalks %>
	<li>$PresentationTitle</li>
	<% end_loop %>

	</ul>
<% end_if %>
 
<% end_if %>
<%-- end accepted talks --%>


<%-- only alternates --%>
<% if AlternateTalks %>
	<% if AcceptedTalks %><% else %>

		<p><% if AlternateTalksCount=1 %>Your presentation has<% else %>Some of your presentations have<% end_if %> been approved as an <strong>Alternate</strong> Session for potential inclusion in the OpenStack Summit in Hong Kong.  If, for various reasons, other chosen speakers cannot attend the Summit and a slot becomes available on the agenda for your session to be included -  then we will reach out to the alternate speakers at that time.  Please be patient.</p> 

		<p><% if AlternateTalksCount=1 %>Selected As An Alternate:<% else %>Selected As Alternates:<% end_if %></p>

		<ul>

		<% loop AlternateTalks %>
		<li>$PresentationTitle</li>
		<% end_loop %>

		</ul>

		<% if UnacceptedTalks %>
			<p>Unfortunately, <% if UnacceptedTalksCount=1 %>this other submission was<% else %>these other submissions were<% end_if %> not chosen to be part of the official agenda this time around.</p>

			<p>Not selected:</p>

			<ul>

			<% loop UnacceptedTalks %>
			<li>$PresentationTitle</li>
			<% end_loopl %>

			</ul>
		<% end_if %>

	<% end_if %>
<% end_if %>
<%-- end only alternates --%>

<% if AcceptedOrAlternateTalks %>
<% else %>
	
		<% if UnacceptedTalks %>
			<p>Unfortunately, <% if UnacceptedTalksCount=1 %>this submission was<% else %>these submissions were<% end_if %> not chosen to be part of the official agenda this time around.</p>

			<p>Not selected:</p>

			<ul>

			<% loop UnacceptedTalks %>
			<li>$PresentationTitle</li>
			<% end_loop %>

			</ul>
		<% end_if %>


<% end_if %>

<% if AcceptedOrAlternateTalks %>
<p>Here are a few important details to help out your speakers:</p>

<p>SPEAKER NOTIFICATION:</p>
<p>Last Friday, we emailed each presentation speaker a confirmation link and a unique registration code to use when registering for the summit. </p>

<% if UnconfirmedSpeakers %>
<p>It looks like <% if Count = 1 %>one speaker has<% else %>a few speakers have<% end_if %> not yet confirmed. (We just sent a quick reminder today.) It would really help us out if you would provide a gentle nudge and encourage <% if Count = 1 %>this speaker<% else %>these speakers<% end_if %> to click the confirmation link in the email we sent. Confirming attendance as a speaker is an important step as we finalize summit preparations.
</p>

<p>Not yet confirmed:</p>

<ul>
	<% loop UnconfirmedSpeakers %><li>$FirstName $Surname</li><% end_loop %>
</ul>
<% end_if %>

<p>SPEAKER REGISTRATION:</p>
<p>Secondly, if any speaker has not registered for the Summit, they can do that here: <a href="http://www.eventbrite.com/event/6786581849/">http://www.eventbrite.com/event/6786581849/</a> by using the complimentary registration code we provided via email.  In order to register for FREE, speakers must use the special discount code we provided no later than October 4, 2013.
</p>
<p>SPEAKER ORGANIZER:</p>
<p>In the coming days we will be in touch withe each speaker with the session day/time and presentation details. Going forward the main contact will be Pete Cappa.  He can be reached by calling 1+415-845-2125 or by emailing pete@fntech.com. Pete will be able to help you with all your conference needs.</p>

<% if AdminIsASpeaker %>
<% else %>

<p>Hopefully, we'll also see you at the OpenStack Summit, November 5-8, at the AsiaWorld-Expo, located near the Hong Kong International Airport, Lantau, Hong Kong, China. You can find details about the event at: <a href="http://www.openstack.org/summit/">http://www.openstack.org/summit/</a></p>

<% end_if %>

<% else %>

<p>Even though you didn't have any talks accepted, hopefully we'll still see you at the OpenStack Summit, November 5-8, at the AsiaWorld-Expo, located near the Hong Kong International Airport, Lantau, Hong Kong, China. You can find details about the event at: <a href="http://www.openstack.org/summit/">http://www.openstack.org/summit/</a></p>

<% end_if %>
 
<p>Thanks!<br/>
OpenStack Summit Team</p>

</body>
</html>