<% require themedCSS(carousel) %>

 <div class="span-19">

	<div id="bg_player_location">
	<a href="http://www.adobe.com/go/getflashplayer">
	<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
	</a>
	</div>
	<script type="text/javascript" src="http://player.bc.cdn.bitgravity.com/10/jquery.js"></script>
	<script type="text/javascript" src="http://player.bc.cdn.bitgravity.com/10/functions.js"></script>
	<script type="text/javascript" src="http://player.bc.cdn.bitgravity.com/10/swfobject.js"></script>
	<script type="text/javascript">
	var flashVars = {};
	flashVars.File = "http://itechsherpalive2.live.cdn.bitgravity.com/itechsherpalive2/live/OSS14";
	flashVars.Mode = "live";
	flashVars.AutoPlay = "true";
	flashVars.streamType = "live";
	flashVars.ForceReconnect = "0";
	var params = {};
	params.allowFullScreen = "true";
	params.allowScriptAccess = "always";
	swfobject.embedSWF(info.BitGravityswf, "bg_player_location", "960", "540", info.swfVersionStr, info.xiSwfUrlStr, flashVars, params, attributes);
	</script>


 </div>

 <div class="span-5 last">
	<% include HomepageVideoDetails %>
 </div>


<div class="tabSet span-24 last">
    
<ul class="tabs">
	<% if ReturningVisitor %>
		<li class="active">
			<a href="#tabActivity">Latest Activity</a>
		</li>
		<li>
			<a href="#tabWhatIs">What is OpenStack?</a>
		</li>
	<% else %>
		<li>
			<a href="#tabActivity">Latest Activity</a>
		</li>
		<li class="active">
			<a href="#tabWhatIs">What is OpenStack?</a>
		</li>
	<% end_if %>
</ul>
    
	    	<div id="tabActivity" class="tabContent">
	    
			    <div class="feeds span-15">
				    <div id="openStackFeed">$FeedData</div>
			    </div>
				
				<div class="events prepend-1 span-6 last"><!-- Events Container -->
				
					<% if UpcomingEvents %>
				
						<h2>Come See Us</h2>
						
						<% loop UpcomingEvents %>
						
						<p><strong>NEXT UP:</strong> <a href="$EventLink">{$Title}</a>, $formatDateRange in {$EventLocation}.</p>
						
						<% end_loop %>
					
					<% else %>
						
						<h2>Did you see us? We just attended...</h2>
						
							<% loop PastEvents %>
							
							<p><a href="$EventLink">{$Title}</a>, $formatDateRange in {$EventLocation}.</p>
							
							<% end_loop %>
					
					<% end_if %>
					
									
					
					
					<a href="/events/" class="roundedButton">More Events...</a>
				
				</div><!-- Events Container -->
				
		    
		    
		    </div><!-- tabActivity -->
		    
	    	<div id="tabWhatIs" class="tabContent"><!-- tabWhatIs -->
	    	
	    		<h2 class="prepend-1">OpenStack: The 5-minute Overview</h2>
	    				    <div class="overview span-10 prepend-1"><!-- overview -->		    
	    				    <p class="point"><strong>OpenStack</strong> OpenStack is a global collaboration of developers and cloud computing technologists producing the ubiquitous open source cloud computing platform for public and private clouds. The project aims to deliver solutions for all types of clouds by being simple to implement, massively scalable, and feature rich.  The technology consists of a series of <a href="/projects/">interrelated projects</a> delivering various components for a cloud infrastructure solution.
	    				    </p>
	    				    
	    				    
	    				    <p class="point"><strong>Who's behind OpenStack?</strong> Founded by Rackspace Hosting and NASA, OpenStack has grown to be a <a href="/community/">global software community</a> of developers collaborating on a standard and massively scalable open source cloud operating system. Our mission is to enable any organization to create and offer cloud computing services running on standard hardware.</p>
	    				    
	    					 </div>
	    				    
	    				    <div class="overview span-10 prepend-1"><!-- overview -->
	    				    
	    				    <p class="point"><strong>Who uses OpenStack?</strong> Corporations, service providers,  VARS, SMBs, researchers, and global data centers looking to deploy large-scale cloud deployments for private or public clouds leveraging the support and resulting technology of a global open source community.</p>
	    				    	    				    		    
	    				    <p class="point"><strong>Why open matters:</strong> All of the code for OpenStack is freely available under the Apache 2.0 license. Anyone can run it, build on it, or submit changes back to the project. We strongly believe that an open development model is the only way to foster badly-needed cloud standards, remove the fear of proprietary lock-in for cloud customers, and create a large ecosystem that spans cloud providers.</p>
	    				    
	    				    <p class="point">For more information, visit the <a href="/projects/openstack-faq/">OpenStack Community Q&amp;A</a>.</p>
	    				    </div><!-- overview -->
	    	
	    	</div><!-- tabWhatIs -->
    
    	<p class="clear"></p>
</div><!-- tabSet -->
