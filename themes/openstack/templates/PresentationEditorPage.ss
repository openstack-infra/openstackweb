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
    <link type="text/css" href="/{$ThemeDir}/css/presentation-editor.css" media="screen" rel="stylesheet" />

    <script type="text/javascript">
      var CurrentPresentation = $Presentation.ID;
    </script>

    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/jquery-1.8.0.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/jquery.pjax.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/bootstrap.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/mousetrap.min.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/app.js"></script>
  </head>
  
  <body class="index">
    <!-- ========== Title Bar ========== -->
<div class='navbar navbar-inverse navbar-fixed-top'>
  <div class='navbar-inner'>
    <div class='container'>
      <h2 class='title'>OpenStack Presentation Editor</h2>
    </div>
  </div>
</div>
<div class='container'>
  <div class='row'>
    <!-- ========== Left Sidebar ========== -->
    <div class='col-lg-5' id='left-sidebar'>
      <h4 id='presentation-list-header'>Presentation List</h4>
      <div id='search'>
        $SearchForm
        <div class='btn-toolbar'>
          <div class='btn-group'>
            <button class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown' type='button' width='100%'>
              <span>$CurrentCategory</span>
              <span class='carrot'></span>
            </button>
            <ul class='dropdown-menu'>
              <li>
                <a href='{$Top.Link}Category/All'>All Categories</a>
                <a href='{$Top.Link}Category/None'>No Category Assigned <span class='category-count'>($NumTalksWithNoCategory)</span></a>
              </li>
              <li role="presentation" class="divider"></li>
              <% loop CategoryButtons %>
              <li>
                <a href='{$Top.Link}Category/{$Number}'>$Name <span class='category-count'>($Count)</span></a>
              </li>
              <% end_loop %>
              <li role="presentation" class="divider"></li>
              <li>
                <a href='{$Top.Link}Category/Deleted'>Deleted Presentations</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div id='presentation-list'>
        <div class='row presentaiton'>

          <% if Presentations %>
          
          <% loop PresentationList %>

          <div class='row presentaiton' id="$ID">
            <div class='col-lg-8'>
              <p>
                <a href="{$Top.Link}Show/{$ID}">$PresentationTitle</a>
              </p>
            </div>
            <div class='col-lg-4'>
              <% if MainTopic %>
              <div class='label'>
                $MainTopic
              </div>
              <% end_if %>
            </div>
          </div>

          <% end_loop %>

          <% else %>

            <div class='col-lg-12'>No presentations here.</div>

          <% end_if %>

          
        </div>
      </div>
    </div>
    <!-- ========== Presentation Area ========== -->

    <% loop Presentation %>

    <div class='col-lg-7'>
      <div class='row'>
        <div class='col-lg-12' id='presentation-area-header'>
          <div class='section-label'>
            Presentation Title
          </div>
          <h4>$PresentationTitle</h4>
            <div class="row">
              <div class="col-lg-4">
              <div class='btn-group'>
                <a class='btn btn-default' type='button' href='{$BaseHref}summit/openstack-summit-hong-kong-2013/become-a-speaker/TalkDetails/{$ID}' target='new'>Edit</a>
                <% if MarkedToDelete %>
                  <a class='btn btn-default' data-toggle='modal' href='{$Top.Link}Restore/{$ID}' type='button'>Undelete</a>
                <% else %>
                  <a class='btn btn-default' data-toggle='modal' href='#confirm-delete' type='button'>Delete</a>
                <% end_if %>
                <a class='btn btn-default' data-toggle='modal' href='#add-note' type='button'>
                  Flag
                </a>
              </div>
              </div>
              <div class="col-lg-8">
              <% if FlagComment %><p class="flag-comment">$FlagComment</p><% end_if %>
              </div>
          </div>
        </div>
      </div>
      <!-- ========== Flag Modal ========== -->
      <div class='modal fade' id='add-note'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button class='close' data-dismiss='modal' type='button'>&times;</button>
              <h4>Flag Presentation</h4>
            </div>
            <div class='modal-body'>
              {$Top.FlagForm}
            </div>
          </div>
        </div>
      </div>

      <!-- ========== Delete Modal ========== -->
      <div class="modal fade" id="confirm-delete">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Mark This Presentation To Be Deleted?</h4>
            </div>
            <div class="modal-body">
              <p>(The presentation data won't be lost. It will just be flagged to be deleted later and hidden from view.)</p>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <a class="btn btn-primary" href="{$Top.Link}Delete/$ID">Ok, Delete</a>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

      <div class='row'>
        <div class='col-lg-8' id='presentation-area'>
          <ul class='nav nav-tabs' id='presentation-tabs'>
            <li class='active'>
              <a data-toggle='tab' href='#details'>Details</a>
            </li>
            <li>
              <a data-toggle='tab' href='#speakers'>Speakers</a>
            </li>
          </ul>
          <div class='tab-content'>
            <div class='tab-pane active' id='details'>
              <div class='section-label'>
                Title
              </div>
              <h4>$PresentationTitle</h4>
              <div class='section-label'>
                Abstract
              </div>
              <div>$Abstract</div>
            </div>

            <div class='tab-pane' id='speakers'>
              <% loop Speakers %>

              <div class='section-label'>
                Name
              </div>
              <h4>$FirstName $Surname</h4>
              <div class='section-label'>
                Bio
              </div>
              <div>$Bio</div>

              <% end_loop %>

            </div>
          </div>
        </div>
        <div class='col-lg-4' id='presentation-labels'>
          <h4>Main Topic</h4>

          <% loop Top.CategoryButtons %>

            <a id='cat-{$Number}' class='category $Class' href='{$Top.Link}SetMainTopic/{$Top.Presentation.ID}/{$Number}'>
              <span>
                $Number
              </span>
              $Name
            </a>

          <% end_loop %>

        </div>
      </div>
    </div>
  </div>

  <% end_loop %>

  <!-- ========== Bottom Keyboard Shortcuts ========== -->
  <div class='row' id='keyboard-shortcuts'>
    <div class='col-lg-12'>
      <p>
        <span class='label'>P</span>
        <a id="Prev" href="{$Top.Link}Previous">Previous Presentation</a> | <a id="Next" href="{$Top.Link}Next">Next Presentation</a>
        <span class='label'>N</span>
      </p>
    </div>
  </div>
</div>

  </body>
</html>