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
    <link type="text/css" href="/{$ThemeDir}/css/presentation-voting.css" media="screen" rel="stylesheet" />

    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/jquery-1.8.0.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/jquery.pjax.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/bootstrap.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/mousetrap.min.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/voting.js"></script>
  </head>
  
  <body class="voting voting_index">

    <!-- ---------- Title Bar ---------- -->
<div class='container'>
  <div class='row'>
    <div class='col-lg-12'>  
      <h2 class='title'>OpenStack Presentation Voting</h2>
    </div>
  </div>
</div>
<div class='container'>

  <div class='navbar'>
    <button class='navbar-toggle' data-target='.navbar-responsive-collapse' data-toggle='collapse' type='button'>
      <span class='icon-bar'></span>
      <span class='icon-bar'></span>
      <span class='icon-bar'></span>
    </button>
    <div class='nav-collapse collapse navbar-responsive-collapse'>
      $SearchForm
      <ul class='nav navbar-nav pull-right'>
        <li class='dropdown'>
          <a class='dropdown-toggle' data-toggle='dropdown' href='#'>
            Select Category
            <b class='caret'></b>
          </a>
          <ul class='dropdown-menu'>
            <li>
              <a href='#'>All Categories</a>
            </li>
            <li class='divider'></li>

            <% loop CategoryLinks %>
            <li>
              <a href='{$Top.Link}Category/{$URLSegment}'>$Name</a>
            </li>
            <% end_loop %>

          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>


<div class='container' id='presentation-background'>
  <div class='row'>
    <div class='col-lg-12'>
      <div id='presentation-area'>

          <% if CategoryName %>
          <h2>Congratulations! You've voted on all the presentations in this category</h2>
          <h4>There are no more presentations in the "{$CategoryName}" Category that you haven't voted on.</h4>

          <p><a href="{$Top.Link}Category/All" class='btn btn-default'>Back To All Categories</a></p>
          <% else %>

          <h2>Wow! You've voted on every single presentation!</h2>
          <h4>There are no more presentations that you haven't voted on.</h4>

          <p>Thank you so much for your help and hard work!</p>


          <p><a href="http://www.openstack.org/" class='btn btn-default'>Back To The OpenStack Site</a></p>

          <% end_if %>

      </div>
    </div>
</div>

  </body>


</html>