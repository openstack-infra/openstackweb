<div class="newSubNav">
    <ul class="overviewNav">
        <% loop Level(1) %>
            <li id="$URLSegment"><a href="$Link" title="Go to the &Title&quot; page" class="$LinkingMode">$MenuTitle <i class="fa fa-chevron-right"></i></a></li>
        <% end_loop %>
        <% loop Menu(2) %>
            <li id="$URLSegment"><a href="$Link" title="Go to the &quot;{$Title}&quot; page"  class="$LinkingMode">$MenuTitle <i class="fa fa-chevron-right"></i></a></li>
        <% end_loop %>
    </ul>
</div>