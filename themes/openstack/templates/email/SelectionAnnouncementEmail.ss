<html>
<body>
	<% loop Speaker %>
	<p>Hello $FirstName $Surname --</p>
	<% end_loop %>

<%--  Only unaccepted talks --%>
<% if UnacceptedTalks %>
	
	<% if AcceptedTalks %><% else %>
	<% if AlternateTalks %><% else %>

	<p>	Thank you for submitting a speaking proposal for the November OpenStack Summit In Paris.  We received an incredible 1,000+ submissions for the Paris Summit, and had to make some tough decisions for the schedule.</p> 

	<p>Unfortunately, your <% if UnacceptedTalksCount == 1 %>submission was<% else %>submissions were<% end_if %> not chosen to be part of the official agenda this time around. You submitted:</p>

	<ul>

	<% loop UnacceptedTalks %>
	<li>$PresentationTitle</li>
	<% end_loop %>

	</ul>

	<p>There is, however, an opportunity to have a speaking platform at the Summit that you may want to pursue. At the Paris Summit we will again offer the #vbrownbag Tech Talks platform for brief presentations. The TechTalks offer a forum for community members to give ten (10) minute presentations. TechTalks have a small in-person audience and will be recorded and published to YouTube If you are interested in participating in the #vbrownbag TechTalks please complete this submission form: <br/>
	<a href="https://openstack.prov12n.com/techtalks-at-openstack-summit-paris/">https://openstack.prov12n.com/techtalks-at-openstack-summit-paris/</a></p>
	        
	<p>We hope you will join us in Paris for the Summit and take the opportunity to register at the Early discounted rate before it expires on August 27 at 11:59pm CDT. Prices will increase on August 28.</p>

	<p>Please register at <a href="https://openstacksummitnov2014.eventbrite.com/">https://openstacksummitnov2014.eventbrite.com/</a>. </p>


	<% end_if %>
	<% end_if %>

<% end_if %>

<%-- Accepted talks --%>
<% if AcceptedTalks %>

<p>Congratulations! Your <% if AcceptedTalksCount == 1 %>submission has<% else %>submissions have<% end_if %> been accepted for inclusion in the November OpenStack Summit in Paris.</p> 

<p><strong>Please click this link to confirm your attendance as a speaker: <a href="https://www.openstack.org/summit/openstack-paris-summit-2014/call-for-speakers/ConfirmSpeaker/?key={$ConfirmationHash}" />https://www.openstack.org/summit/openstack-paris-summit-2014/call-for-speakers/ConfirmSpeaker/?key={$ConfirmationHash}</a></strong></p>

<p>Accepted For The Summit:</p>

<ul>
<% loop AcceptedTalks %>
<li>$PresentationTitle</li>
<% end_loop %>
</ul>

<% if AlternateTalks %>
	<p>Also, <% if AlternateTalksCount == 1 %>one more presentation has been<% else %>some more presentations have<% end_if %> been approved as an <strong>Alternate</strong> Session for potential inclusion in the OpenStack Summit in Paris.  If, for various reasons, other chosen speakers cannot attend the Summit and a slot becomes available on the agenda for your session to be included -  then we will reach out to you at that time.  Please be patient.</p> 

	<p>Selected As Alternates:</p>

	<ul>

	<% loop AlternateTalks %>
	<li>$PresentationTitle</li>
	<% end_loop %>

	</ul>
<% end_if %>


<% if UnacceptedTalks %>
	<p>Unfortunately, <% if UnacceptedTalksCount == 1 %>this other submission was<% else %>these other submissions were<% end_if %> not chosen to be part of the official agenda this time around.</p>

	<p>Not selected:</p>

	<ul>

	<% loop UnacceptedTalks %>
	<li>$PresentationTitle</li>
	<% end_loop %>

	</ul>
<% end_if %>
 
<p><strong><i>If for any reason you are unable to attend the Summit or cannot attend for the entire duration (Monday - Thursday) please reply to this email immediately to inform us.</i></strong></p>

<p>In the coming days we will be in touch again with your session day/time and presentation details. Going forward your main contact will be {$SpeakerManagerName}. She can be reached by emailing speakersupport@fntech.com. They will be able to help you with all your conference needs.</p>

<p>If you have not already registered for the Summit, please register at <a href="http://openstacksummitmay2014.eventbrite.co.uk/">http://openstacksummitmay2014.eventbrite.co.uk/</a> by using the complimentary Full Access level registration code provided below. In order to register for FREE you must use the below code no later than October 3, 2014.</p>
 
<p><strong>FREE REGISTRATION CODE (IT'S UNIQUE &amp; SINGLE-USE): $RegistrationCode</strong></p>
 
<p>In Eventbrite there is a blue "Enter Promotional Code" option just above the Order Now button, where you may redeem the code for a free registration pass. Please reference this image for clarity: <a href="https://www.dropbox.com/s/kfs3hijyq1g9aq5/HowToEnterRegCodeImage.png">https://www.dropbox.com/s/kfs3hijyq1g9aq5/HowToEnterRegCodeImage.png</a></p>

<% end_if %>
<%-- end accepted talks --%>


<%-- only alternates --%>
<% if AlternateTalks %>
	<% if AcceptedTalks %><% else %>

		<p>Thank you for submitting a speaking proposal for the November OpenStack Summit in Paris. We received an incredible 1,100+ submissions for the Paris Summit, and had to make some tough decisions for the schedule. <% if AlternateTalksCount == 1 %>Your presentation has<% else %>Some of your presentations have<% end_if %> been approved as an Alternate Session for potential inclusion in the OpenStack Summit in Paris. If, for various reasons, other chosen speakers cannot attend the Summit and a slot becomes available on the agenda for your session to be included - then we will reach out to you at that time. Please be patient.</p> 

		<p><strong>Meanwhile, please click this link to confirm your attendance as a speaker: <a href="https://www.openstack.org/summit/openstack-paris-summit-2014/call-for-speakers/ConfirmSpeaker/?key={$ConfirmationHash}" />https://www.openstack.org/summit/openstack-paris-summit-2014/call-for-speakers/ConfirmSpeaker/?key={$ConfirmationHash}</a></strong></p>

		<p><% if AlternateTalksCount == 1 %>Selected As An Alternate:<% else %>Selected As Alternates:<% end_if %></p>

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
 
		<p><strong><i>If for any reason you are unable to attend the Summit or cannot attend for the entire duration (Monday - Thursday) please reply to this email immediately to inform us.</i></strong></p>

		<p>If you have not already registered for the Summit, please register at <a href="http://openstacksummitmay2014.eventbrite.co.uk/">http://openstacksummitmay2014.eventbrite.co.uk/</a> by using the complimentary Full Access level registration code provided below. In order to register for FREE you must use the below code no later than October 3, 2014.</p>
		 
		<p><strong>FREE REGISTRATION CODE: $RegistrationCode</strong></p>
		 
		<p>In Eventbrite there is a blue "Enter Promotional Code" option just above the Order Now button, where you may redeem the code for a free registration pass. Please reference this image for clarity: <a href="https://www.dropbox.com/s/kfs3hijyq1g9aq5/HowToEnterRegCodeImage.png">https://www.dropbox.com/s/kfs3hijyq1g9aq5/HowToEnterRegCodeImage.png</a></p>


	<% end_if %>
<% end_if %>
<%-- end only alternates --%>

<p>We look forward to seeing you at the <a href="http://www.openstack.org/summit/">OpenStack Summit</a>, November 3-7, at the Palais des congrès de Paris in Paris, France.</p>
 
<p>Cheers,<br/>
OpenStack Summit Team</p>

</body>
</html>