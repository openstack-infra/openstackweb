<html>
<body>
	<% with Speaker %>
	<p>Hello $FirstName $Surname --</p>
	<% end_with %>

<%-- Confirmation Reminder --%>
<p> ---------------------------- </p>

<strong><p>On Friday we emailed the speakers who had submitted presentations for The OpenStack Summit in Hong Kong. Please see the text of that email below.</p>

<p>We're sending this follow up email because you have not yet confirmed that you are a speaker using the confirmation link below. Please review the email and let us know you'll be attending by clicking the following link: <a href="http://www.openstack.org/summit/openstack-summit-hong-kong-2013/become-a-speaker/ConfirmSpeaker/?key={$ConfirmationHash}" />http://www.openstack.org/summit/openstack-summit-hong-kong-2013/become-a-speaker/ConfirmSpeaker/?key={$ConfirmationHash}</a></p>

<p>Also, please don't forget to provide us an on site phone number in case we need to reach you while you are in Hong Kong (you can provide your phone number using the link above). Thanks so much for your help. We're really excited about the summit and having you as a speaker.</p></strong>

<p> ---------------------------- </p>

<%-- Accepted talks --%>
<% if AcceptedTalks %>

<p>Congratulations! Your <% if AcceptedTalksCount=1 %>submission has<% else %>submissions have<% end_if %> been accepted for inclusion in the OpenStack Summit in Hong Kong.</p> 

<p>Accepted For The Summit:</p>

<ul>
<% loop AcceptedTalks %>
<li>$PresentationTitle</li>
<% end_loop %>
</ul>

<% if AlternateTalks %>
	<p>Also, <% if AlternateTalksCount=1 %>one more presentation has been<% else %>some other presentations have<% end_if %> been approved as an <strong>Alternate</strong> Session for potential inclusion in the OpenStack Summit in Hong Kong.  If, for various reasons, other chosen speakers cannot attend the Summit and a slot becomes available on the agenda for your session to be included -  then we will reach out to you at that time.  Please be patient.</p> 

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
 
<p><strong><i>If for any reason you are unable to attend the Summit or cannot attend for the entire duration (Tuesday - Friday) please reply to this email immediately to inform us.</i></strong></p>

<p>In the coming days we will be in touch again with your session day/time and presentation details. Going forward your main contact will be Pete Cappa.  He can be reached by calling 1+415-845-2125 or by emailing pete@fntech.com. Pete will be able to help you with all your conference needs.</p>

<p>If you have not already registered for the Summit, please register at <a href="http://www.eventbrite.com/event/6786581849/">http://www.eventbrite.com/event/6786581849/</a> by using the complimentary registration code provided below.  In order to register for FREE you must use the below code no later than October 4, 2013. </p>
 
<p><strong>FREE REGISTRATION CODE (IT'S UNIQUE &amp; SINGLE-USE): $RegistrationCode</strong></p>
 
<p>In Eventbrite there is a blue "Enter Promotional Code" option just above the Order Now button, where you may redeem the code for a free registration pass. Please reference this image for clarity: <a href="https://www.dropbox.com/s/ycglkrogyahfppn/RegisterPromotionalCode.png">https://www.dropbox.com/s/ycglkrogyahfppn/RegisterPromotionalCode.png</a></p>

<% end_if %>
<%-- end accepted talks --%>


<%-- only alternates --%>
<% if AlternateTalks %>
	<% if AcceptedTalks %><% else %>

		<p>We received an incredible 600+ submissions for the Hong Kong Summit, and had to make some tough decisions for the schedule.</p>

		<p><% if AlternateTalksCount=1 %>Your presentation has<% else %>Some of your presentations have<% end_if %> been approved as an <strong>Alternate</strong> Session for potential inclusion in the OpenStack Summit in Hong Kong.  If, for various reasons, other chosen speakers cannot attend the Summit and a slot becomes available on the agenda for your session to be included -  then we will reach out to you at that time.  Please be patient.</p> 

		<p><strong>Meanwhile, please click this link to confirm your attendance as a speaker: <a href="http://www.openstack.org/summit/openstack-summit-hong-kong-2013/become-a-speaker/ConfirmSpeaker/?key={$ConfirmationHash}" />http://www.openstack.org/summit/openstack-summit-hong-kong-2013/become-a-speaker/ConfirmSpeaker/?key={$ConfirmationHash}</a></strong></p>

		<p><% if AlternateTalksCount=1 %>Selected As An Alternate:<% else %>Selected As Alternates:<% end_if %></p>

		<ul>

		<% loop AlternateTalks %>
		<li>$PresentationTitle</li>
		<% end_loop %>

		</ul>

		<% if UnacceptedTalks %>
			<p>Unfortunately, <% if UnacceptedTalksCount == 1 %>this other submission was<% else %>these other submissions were<% end_if %> not chosen to be part of the official agenda this time around.</p>

			<p>Not selected:</p>

			<ul>

			<% loop UnacceptedTalks %>
			<li>$PresentationTitle</li>
			<% end_loop %>

			</ul>
		<% end_if %>
 
		<p><strong><i>If for any reason you are unable to attend the Summit or cannot attend for the entire duration (Tuesday - Friday) please reply to this email immediately to inform us.</i></strong></p>

		<p>If you have not already registered for the Summit, please register at <a href="http://www.eventbrite.com/event/6786581849/">http://www.eventbrite.com/event/6786581849/</a> by using the complimentary registration code provided below.  In order to register for FREE you must use the below code no later than October 4, 2013. </p>
		 
		<p><strong>FREE REGISTRATION CODE: $RegistrationCode</strong></p>
		 
		<p>In Eventbrite there is a blue "Enter Promotional Code" option just above the Order Now button, where you may redeem the code for a free registration pass. Please reference this image for clarity: <a href="https://www.dropbox.com/s/ycglkrogyahfppn/RegisterPromotionalCode.png">https://www.dropbox.com/s/ycglkrogyahfppn/RegisterPromotionalCode.png</a></p>	


	<% end_if %>
<% end_if %>
<%-- end only alternates --%>

<p>We look forward to seeing you at the OpenStack Summit, November 5-8, at the AsiaWorld-Expo, located near the Hong Kong International Airport, Lantau, Hong Kong, China. </p>
 
<p>Thanks!<br/>
OpenStack Summit Team</p>

</body>
</html>