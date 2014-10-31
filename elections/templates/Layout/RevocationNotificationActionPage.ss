<div class="loggedInBox">You are logged in as: <strong>$CurrentMember.Name</strong>&nbsp; &nbsp; <a class="roundedButton" href="/revocation-notifications/logout/">Logout</a>
<br>
<br>
<p>Your account is scheduled to be changed from "Foundation Member" to "Community Member" in <% with Notification %>$remainingDays<% end_with %> days. You have two options:</p>
<ol>
	<li><span class="action-button-container">Request to <a href="/revocation-notifications/{$Token}/renew" class="roundedButton">CONTINUE AS A FOUNDATION MEMBER</a></span></li>
    <!-- <li><span class="action-button-container">Move to <a href="/revocation-notifications/{$Token}/revoke" class="roundedButton">"COMMUNITY MEMBER" ONLY</a></span></li> -->
    <li><span class="action-button-container"><a href="/revocation-notifications/{$Token}/delete" class="roundedButton">DELETE ACCOUNT</a></span></li>
</ol>
<p>If you take no action, we will automatically change your account status from &quot;Foundation Member&quot; to &quot;Community Member&quot;. As a "Community Member" you can continue to engage in many activities on OpenStack.org including submitting talks for Summits. However, you will not have all of the rights and responsibilities of being a full Foundation Member, including voting in future Board of Directors elections.</p>