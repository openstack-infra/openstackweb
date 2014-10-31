<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    
    <!-- Always force latest IE rendering engine or request Chrome Frame -->
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    
    <!-- Use title if it's in the page YAML frontmatter -->
    <title>OpenStack Presentation Editor</title>
    
    <link type="text/css" href="/{$ThemeDir}/css/bootstrap3.css" media="screen" rel="stylesheet" />
    <link type="text/css" href="/{$ThemeDir}/css/track-chair.css" media="screen" rel="stylesheet" />

	<% loop SelectedTalkList %>
    <script type="text/javascript">
      var selectedTalkListID = {$ID};
      var processingLink = "{$Top.Link}SaveSortOrder/{$ID}/?";
    </script>
    <% end_loop %>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>    
	<script type="text/javascript" src="/{$ThemeDir}/javascript/trackchair/sorting.js"></script>
  </head>
  
  <body class="index">
    <!-- ========== Title Bar ========== -->
<div class='container'>

  <div class="row">
  <div class='col-lg-1'></div>
  <div class='col-lg-11'>
  <h2 class='title'>Track Chair Directory</h2>
  <div id="info" style="display:none;"></div>
  </div>
  <div>

  <div class='row'>
    <div class='col-lg-1' id='left-sidebar'>
      <% include TrackChairsSideNav %>
    </div>
    <div class='col-lg-11'>
      <div class='row'>
  <div class='col-lg-8'>

<table class="table track-chair-table">
<tr><th>Name</th><th>Track</th></tr>
<% loop AllTrackChairs %>

<tr>
  <td><a href="mailto:{$Member.Email}">$Member.FirstName $Member.Surname</a></td>
  <td><a href="{$Top.Link}Category/$Category.ID">$Category.Name</a></td>
</tr>

<% end_loop %>
</table>

</div>
<div class='col-lg-4'>
<h4>Track Chairs</h4>
<p>Click on the name of any track chair to contact that person via email.</p>
</div>

</div>

    </div>
  </div>


</div>

  </body>
</html>