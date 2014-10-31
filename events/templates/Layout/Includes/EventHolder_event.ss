<% if IsEmpty %>
	<h3>Sorry, there are no upcoming events listed at the moment.</h3>
	<p class="details">Wow! It really rare that we don't have any upcoming events on display. Somewhere in the world there's sure to be an OpenStack event in the near future&mdash;We probably just need to update this list. Please check back soon for more details.</p>
<% else %>
<a rel="nofollow" href="$EventLink" target="_blank" class="single-event">
    <div class="left-event">
        <div class="date">$formatDateRange</div>
    </div>
    <div class="event-details">
        <div class="event-name">$Title</div>
        <div class="location">$EventLocation</div>
    </div>
    <div class="right-event">
        <div class="right-arrow"><i class="fa fa-chevron-right"></i></div>
    </div>
</a>
<% end_if %>
