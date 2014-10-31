
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>$Title</title>
    
    <% base_tag %>

    
    <!-- Google Fonts -->
	<link href='http://fonts.googleapis.com/css?family=PT+Sans&subset=latin' rel='stylesheet' type='text/css'>
    
    <!-- OpenStack Specific CSS -->
    <link rel="stylesheet" href="themes/openstack/css/pdf.css" type="text/css" media="screen, projection, print">
    
    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="themes/openstack/css/$PageCSS" type="text/css" media="screen, projection, print">
           
  </head>
  <body>
  	  	  	
    <div class="container">
		<div id="header">
			<div class="logoArea">
				<h1 id="logo"><a href="http://www.openstack.org/">Open Stack</a></h1>
			</div>
			<div class="topBorder">
				<hr />
			</div>
	  	</div>
	</div>
	<!-- Page Content -->

    <div class="container">
    	<h1 class="pageTitle">$Title</h1>
    	<h2 class="subTitle">$SubTitle</h2>
    </div>
    <div class="container">
    	<div class="content">
  		$Content  	
  		</div>
  		<div class="sidebar">
  		$Sidebar
  		</div>
	</div>

<div class="container">
	<div id="footer">
		<hr>
		<p>The OpenStack project is provided under the Apache 2.0 license. Openstack.org is powered by <a href="http://www.rackspacecloud.com/">Rackspace Cloud Computing</a>.</p>
	</div>
</div>

</body>
</html>