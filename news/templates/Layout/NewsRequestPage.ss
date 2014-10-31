<% if CurrentMember %>
    <% if CurrentMember.isNewsManager %>
        <div class="newsHome">
            <a href="/news-manage">Back to Manager</a>
        </div>
    <% end_if %>
<% else %>
    <div class="newsHome">
        <a href="/news">Back to News</a>
    </div>
<% end_if %>

<div>
    <h1>Add New Press Release</h1>
    <p></p>
</div>

<% if Saved %>
    <div class="siteMessage" id="SuccessMessage" style="padding: 10px;">
        <p style="float:left;">Your news article has been saved!</p>
        <input type="button" title="Add New Article" value="Add New Article" data-url="/news-add/" class="action link_button">
        Or
        <% if CurrentMember %>
            <% if CurrentMember.isNewsManager %>
                <input type="button" title="Back to Manage News" value="Back to Manage News" data-url="/news-manage/" class="action link_button">
            <% else %>
                <input type="button" title="Back to News" value="Back to News" data-url="/news/" class="action link_button">
            <% end_if %>
        <% else %>
            <input type="button" title="Back to News" value="Back to News" data-url="/news/" class="action link_button">
        <% end_if %>
    </div>
<% else %>
    <% if Error %>
        <div class="siteMessage" id="ErrorMessage" style="padding: 10px;">
            Check below for errors.
        </div>
    <% end_if %>
    <div>
        $NewsRequestForm
    </div>
<% end_if %>