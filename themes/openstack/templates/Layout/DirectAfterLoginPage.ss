
<% if CurrentMember %>

<h1>Welcome, $CurrentMember.FirstName! You are now logged in to OpenStack.org</h1>

<% end_if %>

<hr/>

<h2>Edit Your Speaker Submissions</h2>
<p>You can edit your Summit speaker submissions</p><p><a class="roundedButton" href="{$CallForSpeakersLink}">Speaker Submissions</a></p>


<% if FoundationMember %>
<hr/>

<h2>Edit Your OpenStack Profile</h2>
<p>You can view and edit your OpenStack Foundation Profile</p><p><a class="roundedButton" href="/profile/">OpenStack Profile</a></p>

<% end_if %>