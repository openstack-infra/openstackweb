<!-- Header block -->
<div class="span-24 last conference-title">
	<h1>OpenStack Conference Spring 2012</h1>
	<h2>April 16-20, 2012, San Francisco, California</h2>
</div>

<!-- Conference Calendar -->

<div class="span-24 last conference-summary">
	<div class="span-8 conference-calendar">
			<p class="date">April 16, 17, 18: Design Summit</p>
			<p class="date">April 19 &amp; 20: Conference</p>
			<p>&nbsp;</p>
	</div>
	<div class="span-10" id="two-events">
		<p><strong>Design Summit:</strong> (Mon, Tues, Wed) For OpenStack Developers</p>
		<p><strong>Conference:</strong> (Thrus &amp; Fri) For Users, Vendors, CIOs, Developers</p>
	</div>
	<div class="span-6 last register">
		<p><a href="/conference/san-francisco-2012/register/">Register</a></p>
	</div>
</div>

<% if HideSideBar %>

<!-- Content -->

<div class="pan-22 last">

<% else %>

<div class="span-5">
	<p><strong>Design Summit &amp; Conference</strong><br />San Francisco 2012</p>
	<ul class="navigation">
		<% loop Parent %>
		  		<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>Overview</span></a></li>		
		<% end_loop %>
		<% loop Menu(3) %>
		  		<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>$MenuTitle.XML</span></a></li>
	   	<% end_loop %>
	</ul>

	<div class="headline-sponsors">
		<hr />
		<h3>Our Headline Sponsors</h3>
		<a href="http://www.hpcloud.com"><img src="/themes/openstack/images/conferences/hp-sponsor-logo.jpg" width="182" height="87" /></a>
		<a href="http://nebula.com/"><img src="/themes/openstack/images/conferences/nebula-sponsor-logo.jpg" width="182" height="58" /></a>
		<a href="http://cloud.ubuntu.com/"><img src="/themes/openstack/images/conferences/ubuntu-sponsor-logo.png" width="182" height="66" /></a>
	</div>

</div>

<!-- Content -->

<div class="prepend-1 span-18 last" id="news-feed">

<% end_if %>

