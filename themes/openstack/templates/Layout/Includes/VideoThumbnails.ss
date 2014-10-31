<div class="sort-row">
  <div class="container">
    <div class="sort-left">
      <i class="fa fa-th active"></i>
      <i class="fa fa-th-list"></i>
    </div>
    <div class="sort-right">
      <div class="dropdown video-dropdown">
        <a data-toggle="dropdown" href="#">Select A Day <i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">

        <% control Presentations.GroupedBy(PresentationDay) %>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="{$Top.Link}#day-{$Pos}">$PresentationDay</a></li>
        <% end_control %>

        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <!-- Start Videos -->
  <div class="row">
    <div class="col-lg-12">

      <% control Presentations.GroupedBy(PresentationDay) %>
          <div class="row">
          <h2 id="day-{$Pos}">$PresentationDay</h2>
          <ul>
              <% control Children %>
                  <!-- Video Block -->
                  <div class="col-lg-3 col-md-3 col-sm-3">
                    <a href="{$Top.Link}presentation/{$URLSegment}">
                      <div class="video-thumb">
                        <div class="thumb-play"></div>
                        <img class="video-thumb-img" src="//img.youtube.com/vi/{$YouTubeID}/0.jpg">
                      </div>
                      <p class="video-thumb-title">
                        $Name
                      </p>
                      <p class="video-thumb-speaker">
                        $Speakers
                      </p>
                    </a>
                  </div>
              <% end_control %>
          </ul>
        </div>
      <% end_control %>

    </div>
  </div>
  <!-- End Videos -->

</div>
    <!-- End Page Content -->
