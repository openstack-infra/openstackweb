 <div class="span-19">


 	<% if VideoCurrentlyPlaying=Yes %>
 		<a href="/home/Video/"><img src="/themes/openstack/images/homepage/openstack-summit-live-video.gif" width="720" height="425"/></a>
 	<% else %>
 	 	<img src="/themes/openstack/images/homepage/openstack-summit-no-video.gif" width="720" height="425"/>
 	 <% end_if %>

 </div>


 <div class="span-5 last">
	<% include HomepageVideoDetails %>
 </div>