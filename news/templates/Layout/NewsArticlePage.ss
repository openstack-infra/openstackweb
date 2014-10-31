<div class="newsHome">
    <a href="/news">Back to News</a>
</div>

<div>
    <h1>$Headline</h1>
    $Image.CroppedImage(400,300)
    <p class="summary">$Summary</p>
    <p class="body">$Body</p>
    <% if Document.exists %>
        <p class="document">Document: <a href="$Document.Link">$Document.getLinkedURL</a></p>
    <% end_if %>
    <p class="link"><a href="$Link">$Link</a></p>
    <p class="date">$Date</p>
    <p></p>
</div>
