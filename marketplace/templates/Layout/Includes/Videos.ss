<% if VideoTypes %>
    <hr>
    <h2 id="">Product Videos</h2>
    <form id="videos-form" name="videos-form">
        <% loop VideoTypes %>
            <div class="field text autocompleteoff" id="video_type_$ID">
                <label class="left">$Title</label>
                <p>$Description</p>
                <div class="middleColumn">
                    <input type="text"
                           data-type-id="{$ID}"
                           data-max-length="{$MaxTotalVideoTime}"
                           name="video_type_{$ID}_youtube_id"
                           id="video_type_{$ID}_youtube_id"
                           class="youtube-video text autocompleteoff">
                </div>
            </div>
        <% end_loop %>
    </form>
<% end_if %>