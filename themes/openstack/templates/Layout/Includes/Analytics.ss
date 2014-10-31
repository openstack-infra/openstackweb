<script type="text/javascript">

// Used to record outbound links before the browser resets to the new site

function recordOutboundLink(link, category, action) {
  try {
  _gaq.push(['._trackEvent', category , action ]);
  setTimeout('document.location = "' + link.href + '"', 100)
  }catch(err){}
  }
  
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-17511903-1']);
  _gaq.push(['_setDomainName', '.openstack.org']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();  

</script>

