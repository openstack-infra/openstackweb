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

    <script type="text/javascript">
      var CurrentPresentation = $Presentation.ID;
    </script>

    <script type="text/javascript" src="/{$ThemeDir}/javascript/trackchair/jquery-1.8.0.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/trackchair/jquery.pjax.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/trackchair/bootstrap.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/trackchair/mousetrap.min.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/trackchair/app.js"></script>
  </head>
  
  <body class="index">
    <!-- ========== Title Bar ========== -->
<div class='container'>

  <div class="row">
  <div class='col-lg-1'></div>
  <div class='col-lg-11'>
  <h2 class='title'>Browse Presentations</h2>
  </div>
  <div>

  <div class='row'>
    <div class='col-lg-1' id='left-sidebar'>

    <% include TrackChairsSideNav %>


    </div>
    <!-- ========== Left Sidebar ========== -->
    <% if Presentation %>
      <div class='col-lg-6' id='left-sidebar'>
    <% else %>
      <div class='col-lg-11' id='left-sidebar'>
    <% end_if %>  
      <h4 id='presentation-list-header'>Presentation List</h4>
      <div id='search'>
        $SearchForm
        <div class='btn-toolbar'>
          <div class='btn-group'>
            Track: &nbsp;
            <a data-toggle='dropdown' href='#'>
              <span>$CurrentCategory.Name </span>
              <span class='carrot'></span>
            </a>
            &nbsp; <% if CurrentCategory.MemberIsTrackChair %>(You Are A Track Chair)<% end_if %>
            <ul class='dropdown-menu'>
              <li>
                <a href='{$Top.Link}Category/All'>All Categories</a>
              </li>
              <li role="presentation" class="divider"></li>
              <% loop CategoryButtons %>
              <li <% if MemberIsTrackChair %> class="track-chair" <% end_if %> >
                <a href='{$Top.Link}Category/{$ID}'>$Name <span class='category-count'>($NumberOfAvailableTalks)</span></a>
              </li>
              <% end_loop %>
            </ul>
          </div>
        </div>
      </div>
      <div id='presentation-list'>
        <div class='row presentaiton'>

          <!-- Search Results -->
          <% if SearchResults %>
          <table class='table'>
            <th>Search Results</th>
          <% loop SearchResults %>
                        <tr id="$ID">
                <td>
                    <a href="{$Top.Link}Show/{$ID}">$PresentationTitle</a>
                </td>
              </tr>
          <% end_loop %>
            </table>
          <% else %>

          <!-- Presentation Display -->
          <% if Presentations %>

          <div class="fixed-table-container">
          <div class="header-background"> </div>
          <div class="fixed-table-container-inner" id='presentation-table'>

          <table class='table'>

            <tr>
              <% loop PresentationTableColumns %>
                <th><div class="th-inner"><a href="{$Top.Link}SetSortOrder/{$Column}/<% if SortOrder=ASC %>DESC<% else %>ASC<% end_if %>">$DisplayName</div></th>
              <% end_loop %>
            </tr>
          
              <% loop PresentationList %>

              <tr id="$ID">
                <td class = "title-column <% if IsSelected %>selected-presentation<% end_if %>">
                    <a href="{$Top.Link}Show/{$ID}">$PresentationTitle</a>
                </td>
                <td>$VoteCount</td>
                <td>$TotalPoints</td>
                <td>$VoteAverage</td>
              </tr>

              <% end_loop %>
          </table>

          </div>
          </div>

          <% else %>

            <div class='col-lg-12'>No presentations here.</div>

          <% end_if %>
          <% end_if %>
          <!-- End Presentation Display -->


          
        </div>
      </div>
    </div>
    <!-- ========== Presentation Area ========== -->

    <% if Presentation %>
    <% loop Presentation %>

    <div class='col-lg-5' id='presentation-area-container'>
      <div class='row'>
        <div class='col-lg-12' id='presentation-area-header'>
          <div class='section-label'>
            Presentation Title
          </div>
          <h4>$PresentationTitle</h4>
            <div class="row">
              <% if CanAssign %>
              <div class="col-lg-8">
              <div class='btn-group'>
                <% if IsSelected %>
                  <a class='btn btn-default' href='{$Top.Link}UnselectTalk/{$ID}' type='button'>Unselect</a>
                <% else %>                  
                  <a class='btn btn-default' href='{$Top.Link}SelectTalk/{$ID}' type='button'>Select</a>
                <% end_if %>
                <a class='btn btn-default' data-toggle='modal' href='#add-note' type='button'>
                  Flag
                </a>
                <a class='btn btn-default' data-toggle='modal' href='#subcategories' type='button'>
                  Subcategories
                </a>
                <% if IsAdmin %>
                  <a class='btn btn-default' href='/speaker-editor/Show/{$ID}' target="_blank" type='button'>
                    Edit
                  </a>
                <% end_if %>
              </div>
              </div>
              <% end_if %>
              <div class="col-lg-4">
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

      <!-- ========== Subcategories Modal ========== -->
      <div class='modal fade' id='subcategories'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <button class='close' data-dismiss='modal' type='button'>&times;</button>
              <h4>Manage Subcategories</h4>
            </div>
            <div class='modal-body'>
              <p>Add an optional subcategory topic to help attendees search for sessions of interest. Pleasse put a comma between each subcategory.</p>
              {$Top.SubcategoryForm}
            </div>
          </div>
        </div>
      </div>      

      <div class='row'>
        <div class='col-lg-12' id='presentation-area'>
          <% if StaffNote %>
            <div class="staff-note"><strong>Note:</strong> $StaffNote</div>
          <% end_if %>
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
              <p><a href="mailto:{$Member.Email}">$Member.Email</a></p>
              <div class='section-label'>
                Bio
              </div>
              <div>$Bio</div>

              <% end_loop %>

            </div>
          </div>
        </div>
      </div>
    </div>

  <% end_loop %>
  <% end_if  %>
    </div>


  <% if Presentation %>
  <!-- ========== Bottom Keyboard Shortcuts ========== -->
  <div class='row' id='keyboard-shortcuts'>
    <div class='col-lg-1'></div>
    <div class='col-lg-11'>
      <p>
        <span class='label'>P</span>
        <a id="Prev" href="#">Previous Presentation</a> | <a id="Next" href="#">Next Presentation</a>
        <span class='label'>N</span>
      </p>
    </div>
  </div>
</div>
<% end_if %>

  </body>
</html>