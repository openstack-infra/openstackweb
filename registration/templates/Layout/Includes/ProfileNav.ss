<div class="loggedInBox">
    You are logged in as: <strong>$CurrentMember.Name</strong>&nbsp; &nbsp; <a class="roundedButton" href="{$Link}logout/">Logout</a> &nbsp; <a class="roundedButton" href="{$Link}resign/">Resign Membership</a>
</div>
<h2 class="profile-tabs">
<a href="{$Link}" <% if CurrentTab=1 %>class="active"<% end_if %> >Your Details</a>
<% if FoundationMember %>
<a href="{$Link}election" <% if CurrentTab=2 %>class="active"<% end_if %> >Election</a>
<% end_if %>
<a href="{$Link}agreements"  <% if CurrentTab=3 %>class="active"<% end_if %> >Legal Agreements</a>
<% if CurrentMember.isTrainingAdmin %>
	<a href="{$Link}training"  <% if CurrentTab=4 %>class="active"<% end_if %> >Training</a>
<% end_if %>
<% if CurrentMember.isMarketPlaceAdmin %>
    <a href="{$Link}marketplace-administration"  <% if CurrentTab=5 %>class="active"<% end_if %> >MarketPlace Administration</a>
<% end_if %>
<a href="{$Link}speaker"  <% if CurrentTab=7 %>class="active"<% end_if %> >Speaker Details</a>
$NavActionsExtensions
</h2>