<li>
    <div class="sliderBox">
        <input type="hidden" class="article_id" value="$ID" />
        <input type="hidden" class="article_rank" value="$Rank" />
        <input type="hidden" class="article_type" value="slider" />
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