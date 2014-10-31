<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    
    <!-- Always force latest IE rendering engine or request Chrome Frame -->
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    
    <% if SearchMode %> 
      <title>Search Results</title>
    <% else %>
      <title>$Presentation.PresentationTitle</title>
    <% end_if %>
    
    <link type="text/css" href="/{$ThemeDir}/css/bootstrap3.css" media="screen" rel="stylesheet" />
    <link type="text/css" href="/{$ThemeDir}/css/presentation-voting.css" media="screen" rel="stylesheet" />

    <% if SearchMode %> 
    <% else %>

    <script type="text/javascript">
      var CurrentPresentation = $Presentation.ID;
    </script>

    <% end_if %>

    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/jquery-1.8.0.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/jquery.pjax.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/bootstrap.js"></script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/mousetrap.min.js"></script>
    <script type="text/javascript">
      var showIntro = '{$ShowIntro}';
    </script>
    <script type="text/javascript" src="/{$ThemeDir}/javascript/presentationeditor/voting.js"></script>

    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="//ws.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "9a0f4e06-914d-453a-bde8-7ee85944c2bf", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

  </head>
  
  <body class="voting voting_index">

    <!-- ---------- Title Bar ---------- -->
<div class='container'>
  <h2 class='title'>OpenStack Presentation Voting</h2>
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
              <a href='{$Top.Link}Category/All'>All Categories</a>
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

<% if SearchMode %>
<% else %>

<div class='container'>
  <div class='row' id='instructions'>
    <div class='col-lg-8'>
      <div id='instruction-text'>
        <h4>Help this presentation get to the OpenStack Summit!</h4>
        <p>OpenStack community members are voting on presentations to be presented at the <strong>OpenStack Summit, November 3-7, in Paris, France</strong>. We received hundreds of high-quality submissions, and your votes can help us determine which ones to include in the schedule.</p>
      </div>
    </div>
    <div class='col-lg-2'></div>
    <div class='modal fade' id='instructions-modal'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <button aria-hidden='true' class='close' data-dismiss='modal' type='button'>&times;</button>
            <h4 class='modal-title'>Vote For Speakers</h4>
          </div>
          <div class='modal-body'>
            <div class='pull-left hidden-sm'>
              <img src="/themes/openstack/images/anne.png" />
            </div>
            <div class='details'>
              <h5>Thanks for helping us pick the best presentations to include the OpenStack Summit!</h5>
              <p class='callout'>Hundreds of speakers have submitted potential presentations. With your help, we can select the very best ones.</p>
              <p>Once you cast your vote, another randomly selected presentation will automatically appear.</p>
              <p>Vote away--and thank you very much for your input!</p>
              <button class='btn btn-default' data-dismiss='modal' type='button'>Let's Start Voting!</button>
              <a class='btn btn-default' href='http://www.openstack.org/summit/'>More About The Summit</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<% end_if %>

<% if CategoryName %>
<div class='container'>
  <div class='row'>
    <div class='col-lg-12'>
      <p>Veiwing presentations in the category: <strong class='label'>$CategoryName <a href="{$Top.Link}Category/{$URLSegment}">&times;</a></strong></p>
    </div>
  </div>
</div>
<% end_if %>

<div class='container' id='presentation-background'>
  <div class='row'>
    <div class='col-lg-8'>
      <div id='presentation-area'>

        <% if SearchMode %>

        <% if SearchResults %>
          <h3>Search Results</h3>

          <ul>
          <% loop SearchResults %>

            <li><a href="{$Top.Link}Presentation/{$URLSegment}">$PresentationTitle</a></li>

          <% end_loop %>
          </ul>
        <% else %>
        <p>No results found.</p>
        <% end_if %>


        <% else %>

        <% loop Presentation %>

          <% if SummitCategory %>
            <h3>"$SummitCategory.Name"</h3>
          <% end_if %>
          
        <h1>$PresentationTitle</h1>
        <div class='row'>
          <!-- ---------- Talk Details ---------- -->
          <div class='col-lg-8' id='presentation-pane'>
            <div class='frame'>
              <div class='abstract-area'>
                <p>$Abstract</p>
              </div>
            </div>
            <div class='frame'>
              <% if Speakers %>
              <hr class="quiet-divider"/>
              <h3 id='presentation-list-header'>Speaker Bios</h3>

              <% loop Speakers %>
              <h4>$FirstName $Surname</h4>
                <% if Bio %>
                <div class='bio-area'>
                  <div>$Bio</div>
                </div>
                <% end_if %>
              <% end_loop %>
              <% end_if %>
            </div>
          </div>
          <div class='col-lg-4' id='speaker-photos'>
            <div class='frame'>
              <% if Tag %>
                <h3>Level</h3>
                <p>$Tag</p>
              <% end_if %>

              <% if Speakers %>
              <h3 id='presentation-list-header'>Speakers</h3>
              <% loop Speakers %>
              <% if Photo.Exists %>
                $Photo.SetWidth(100)
              <% else %>
                <img src="/themes/openstack/images/generic-profile-photo.png">
              <% end_if %>
              <h4>$FirstName $Surname</h4>
              <h6>$Title</h6>
              <% end_loop %>
              <% end_if %>
            </div>
          </div>
        </div>
        <% end_loop %>


        <% end_if %>

      </div>
    </div>

<% if SearchMode %>
<% else %>


    <div class='col-lg-4'>
      <div data-offset-top='250px' data-spy='affix' id='voter-tools' style='width: 260px'>

        <% if CurrentMember %>

        <h3>{$CurrentMember.FirstName}, cast your vote:</h3>
        <div class='btn-group-vertical'>
          <a href="{$Top.Link}SaveRating/?id={$Presentation.ID}&rating=3" class='btn btn-default <% if VoteValue = 3 %>current-vote<% end_if %>' id='vote-3'>
            <span class='label'>3</span>
            Would Love To See This!
          </a>
          <a href="{$Top.Link}SaveRating/?id={$Presentation.ID}&rating=2" class='btn btn-default <% if VoteValue = 2 %>current-vote<% end_if %>' id='vote-2'>
            <span class='label'>2</span>
            Would Try To See
          </a>
          <a href="{$Top.Link}SaveRating/?id={$Presentation.ID}&rating=1" class='btn btn-default <% if VoteValue = 1 %>current-vote<% end_if %>' id='vote-1'>
            <span class='label'>1</span>
            Might See This
          </a>
          <a href="{$Top.Link}SaveRating/?id={$Presentation.ID}&rating=-1" class='btn btn-default <% if VoteValue = -1 %>current-vote<% end_if %>' id='vote-0'>
            <span class='label'>0</span>
            Would Not See
          </a>
        </div>
          <a href="{$Top.Link}SaveRating/?id={$Presentation.ID}&rating=0" class='btn btn-default' id='skip'>
          <span class='label'>S</span>
          Skip (No Opinion)
        </a>
        <p>Cast a vote to see another presentation. You can also vote with your keyboard using the keys on the buttons above.</p>
        <hr>
        <% else %>

          <h3>Ready to vote on this presentation?</h3>
          $SpeakerVotingLoginForm
          <p><a href="{$Link}register-to-vote/?BackURL={$Presentation.URLSegment}">Create A New Account</a></p>

        <% end_if %>
        <h3>Share and promote this presentation</h3>
        <span class='st_twitter_large' displayText='Tweet'></span>
        <span class='st_googleplus_large' displayText='Google +'></span>
        <span class='st_linkedin_large' displayText='LinkedIn'></span>
        <span class='st_email_large' displayText='Email'></span>

        <h3>Attend The Summit</h3>
        <p><a id='summit-registration' class='btn btn-default' href='https://www.eventbrite.com/e/openstack-summit-november-2014-paris-tickets-12051477293?aff=presentationvoting'>Summit Registration</a></p>
        <p><a id='summit-details' class='btn btn-default' href='/summit/'>Full Summit Details</a></p>
      </div>
    </div>

 <% end_if %>   


  </div>
</div>

  </body>


</html>