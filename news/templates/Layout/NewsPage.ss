<div class="grey-bar news">
    <div class="container">
        <div class="row">
            <% if CurrentMember %>
                <% if CurrentMember.isNewsManager %>
                    <a class="manage-news-link" href="/news-manage"><i class="fa fa-cog"></i> Manage News</a>
                <% end_if %>
            <% end_if %>
            <a href="/news-add"><i class="fa fa-plus-circle"></i> Post A News Article</a>
        </div>
    </div>
</div>

<div id="news-slider" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->

    <ol class="carousel-indicators">
        <% loop SlideNews %>
            <li data-target="#news-slider"  <% if First %>class="active" <% end_if %> data-slide-to="$Pos(0)" ></li>
        <% end_loop %>

    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <% loop SlideNews %>
            <div class="item <% if First %>active<% end_if %>">
                <% if Image.Exists %>
                    $Image.CroppedImage(300,200)
                <% else %>
                    <img src="/themes/openstack/images/generic-profile-photo.png">
                <% end_if %>
                <div class="carousel-caption">
                    <h3 class='largeHeadline'>$Headline</h3>
                    <p class='sliderSummary'>$Summary</p>
                    <a class="more-btn" href="news/ViewArticle?articleID=$ID">Read More <i class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
        <% end_loop %>
    </div>
    <!-- Controls -->
    <a class="left carousel-control" href="#news-slider" role="button" data-slide="prev">
        <i class="fa fa-chevron-left"></i>
    </a>
    <a class="right carousel-control" href="#news-slider" role="button" data-slide="next">
        <i class="fa fa-chevron-right"></i>
    </a>
</div>


<div class="container">
    <div class="row">
        <div class="newsFeatured">
            <div class="col-lg-12">
                <h2>Featured Articles</h2>
            </div>
            <ul class="featured">
                <% loop FeaturedNews %>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <li>
                            <div class="featuredImage">
                                <a href="news/ViewArticle?articleID=$ID">
                                    <div class="featuredDate">$formatDate</div>
                                    <div class="featuredHeadline">
                                        $Headline
                                        <div class="more">Read More <i class="fa fa-chevron-circle-right"></i></div>
                                    </div>
                                    <% if Image.Exists %>
                                        $Image.CroppedImage(300,200)
                                    <% else %>
                                        <img src="/themes/openstack/images/generic-profile-photo.png">
                                    <% end_if %>
                                </a>
                            </div>
                            <div class="featuredSummary">$RAW_val(Summary)</div>
                        </li>
                    </div>
                <% end_loop %>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12">
            <h2>Recent News</h2>
            <% loop  RecentNews %>
                <div class="recentBox">
                    <div class="recentHeadline">
                        <a href="news/ViewArticle?articleID=$ID">$RAW_val(Headline)</a> <span class="itemTimeStamp">$formatDate</span>
                    </div>
                    <div class="recentSummary">$RAW_val(Summary)</div>
                </div>

            <% end_loop %>
        </div>
        <div class="news-sidebar col-lg-4 col-md-4 col-sm-12">
            <div class="upcomingEvents">
                <h3>
                    Upcoming Events
                    <div class="see-all-events">
                        <a href="/events">All Events <i class="fa fa-angle-right"></i></a>
                    </div>
                </h3>
                <div class="eventBlock upcoming">
                    <% if FutureEvents(100) %>
                        <% loop FutureEvents(100) %>

                            <div class="event <% if First %> top<% end_if %>">
                                <a rel="nofollow" href="$EventLink" target="_blank">$Title</a>
                                <div class="details">$formatDateRange - $EventLocation</div>
                                <span class="eventButton"><a rel="nofollow" href="$EventLink" target="_blank">Details</a></span>
                            </div>

                        <% end_loop %>
                    <% else %>
                        <div class="event top">
                            <h3>Sorry, there are no upcoming events listed at the moment.</h3>
                            <p class="details">Wow! It really rare that we don't have any upcoming events on display. Somewhere in the world there's sure to be an OpenStack event in the near future&mdash;We probably just need to update this list. Please check back soon for more details.</p>
                        </div>
                    <% end_if %>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- End Page Content -->