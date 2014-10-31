<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>$Title &raquo; OpenStack Open Source Cloud Computing Software</title>

    <% base_tag %>

    <!-- Google Fonts -->
    <link href='{$CurrentProtocol}fonts.googleapis.com/css?family=PT+Sans&subset=latin' rel='stylesheet' type='text/css'>

    <!-- Framework CSS -->
    <link rel="stylesheet" href="/themes/openstack/css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="/themes/openstack/css/blueprint/print.css" type="text/css" media="print">

    <!-- IE CSS -->
    <!--[if lt IE 8]>
    <link rel="stylesheet" href="/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
    <link rel="stylesheet" type="text/css" href="themes/openstack/css/sangria.css" />
    <!-- OpenStack Specific CSS -->
    <% require themedCSS(main) %>
    <link rel="stylesheet" href="/themes/openstack/css/dropdown.css" type="text/css" media="screen, projection, print">
</head>
<body id="$URLSegment">

<div class="container">
    <div id="header">
        <div class="span-5">
            <h1 id="logo"><a href="/">Open Stack</a></h1>
        </div>
        <div class="span-19 last blueLine">

            <div id="navigation" class="span-19">
                <ul id="Menu1">
                    <li><a href="/markeplaceadmin/">MarketPlace Admin</a></li>
                </ul>
            </div>

        </div>
    </div>
</div>
<!-- Page Content -->

<div class="container">
    $Message
    $Layout
</div>
</body>
</html>