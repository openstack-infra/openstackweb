<div id="content-body">
    <h1 id="page-title">$MenuTitle.XML</h1>
    <% if CurrentMember %>
    <div class="loggedInBox">You are logged in as: <strong>$CurrentMember.Name</strong>&nbsp; &nbsp; <a class="roundedButton" href="{$Link}logout/">Logout</a></p>
    <% end_if %>
    $Content
    $Form
</div>