<div class="link_button">
    <a href="/news" id="back_to_news">Back to News</a>
</div>
<% if CurrentMember.isNewsManager %>
    <div class="link_button">
        <a href="#" id="go_to_recent">Go to Recently Submitted</a>
    </div>
    <div class="newsSlider">
        <h3>Banner</h3>
        <ul id="slider_sortable" class="connected" max-items="5">
            $getSliderNews
        </ul>
    </div>
    <div class="clear"></div>
    <div class="newsFeatured">
        <h3>Featured</h3>
        <ul id="featured_sortable" class="connected" max-items="6">
            $getFeaturedNews
        </ul>
    </div>
    <div class="clear"></div>
    <div class="orderMenu">

    </div>
    <div class="newsRecent">
        <h3>Recent News</h3>
        <ul id="recent_sortable" class="connected">
            <% loop  RecentNews %>
                <li>
                    <div class="recentBox">
                        <input type="hidden" class="article_id" value="$ID" />
                        <input type="hidden" class="article_rank" value="$Rank" />
                        <input type="hidden" class="article_type" value="recent" />
                        <div class="newsImage">
                            <a href="$Link">$Image.CroppedImage(200,100)</a>
                        </div>
                        <div class="newsText">
                            <p class="headline">&ldquo;$Headline&rdquo;</p>
                            <p class="summary">&mdash; $Summary</p>
                        </div>
                        <div class="newsEdit"><a href="news-add?articleID=$ID"> Edit </a></div>
                        <div class="newsRemove">Remove</div>
                    </div>
                </li>
            <% end_loop %>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="newsStandBy">
        <h3>Recently Submitted</h3>
        <ul id="standby_sortable" class="connected">
            <% loop  StandByNews %>
                <li>
                    <div class="standbyBox">
                        <input type="hidden" class="article_id" value="$ID" />
                        <input type="hidden" class="article_rank" value="$Rank" />
                        <input type="hidden" class="article_type" value="standby" />
                        <div class="newsImage">
                            <a href="$Link">$Image.CroppedImage(200,100)</a>
                        </div>
                        <div class="newsText">
                            <p class="headline">&ldquo;$Headline&rdquo;</p>
                            <p class="summary">&mdash; $Summary</p>
                        </div>
                        <div class="newsEdit"><a href="news-add?articleID=$ID"> Edit </a></div>
                        <div class="newsDelete">Delete</div>
                    </div>
                </li>
            <% end_loop %>
        </ul>
    </div>
    <div class="clear"></div>
<% else %>
    <% if CurrentMember %>
        <p>In order to edit the news page, you need to be a news manager.</p>
    <% else %>
        <p>In order to edit your community profile, you will first need to <a href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>.
        Don't have an account? <a href="/join/">Join The Foundation</a></p>
        <p><a class="roundedButton" href="/Security/login/?BackURL=%2Fnews-manage%2F">Login</a> <a href="/join/" class="roundedButton">Join The Foundation</a></p>
    <% end_if %>
<% end_if %>