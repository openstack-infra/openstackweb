      <% loop SideNavItems %>
        <a href="{$Top.Link}{$URLSegment}" class="side-nav <% if Selected %>side-nav-selected<% end_if %>" id="{$Icon}">{$Name}</a>
      <% end_loop%>