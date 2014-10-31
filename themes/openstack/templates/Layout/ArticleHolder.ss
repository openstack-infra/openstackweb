<div id="Content" class="typography">
  		
  $Content
  <ul id="NewsList">
    <% loop DateSortedChildren %>
      <li class="newsDateTitle"><a href="$Link" title="Read more on &quot;{$Title}&quot;">$Title</a></li>
      <li class="newsDateTitle">$Date.Nice</li>
      <% if InPast(Date) %>
      	<li>Past Event</li>
      <% end_if %>
      <li class="newsSummary">$Content.FirstParagraph <a href="$Link" title="Read more on &quot;{$Title}&quot;">Read more &gt;&gt;</a></li>
    <% end_loop %>
  </ul>
</div>