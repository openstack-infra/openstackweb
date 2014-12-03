
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="globalsign-domain-verification" content="tWFOHNAA_WMHmHfBMq38uTgupHFugV_dZ2rqyRxNMx" />
    <title>$Title &raquo; OpenStack Open Source Cloud Computing Software</title>

    <% base_tag %>

    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.openstack.org/blog/feed/" />

    <!-- Fonts -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>

    <!-- Framework CSS -->
    <% if BootstrapConverted %>
    <% else %>    
      <link rel="stylesheet" href="/themes/openstack/css/blueprint/screen.css" type="text/css" media="screen, projection">
    <% end_if %>
    <link rel="stylesheet" href="/themes/openstack/css/bootstrap.min.css" type="text/css" media="screen, projection">


    <!-- OpenStack Specific CSS -->
    <% if BootstrapConverted %>
    <% else %>
      <link href="/themes/openstack/css/main.css" rel="stylesheet">
    <% end_if %>
    <link href="/themes/openstack/css/combined.css" rel="stylesheet">
    <link rel="stylesheet" href="/themes/openstack/css/dropdown.css" type="text/css" media="screen, projection, print">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->    


    <% include Analytics %>

  </head>

  <body id="$URLSegment">
      <% include SiteBanner %>
      <% include Navigation %>
      
      <!-- Page Content -->
      <div class="container">
        $Message
        $Layout
      </div>

    <% include Footer %>
    <% include Quantcast %>
  </body>

</html>
