    <div class="col-lg-3">
        $Company.SmallLogoPreview(150)
        <h2 style="color: #{$Company.CompanyColor} !important;">About $Company.Name</h2>
        <p>$Company.Overview</p>
        <hr>
        <div class="pullquote">
            <h2 style="color: #{$Company.CompanyColor} !important;">$Company.Name Commitment</h2>
            <div <% if Company.CommitmentAuthor %>class="commitment"<% end_if %>>$Company.Commitment</div>
            <% if Company.CommitmentAuthor %>
            <p class="author">&mdash;$Company.CommitmentAuthor, $Company.Name</p>
            <% end_if %>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="info-area">
            <h1 style="color: #{$Company.CompanyColor} !important;">
                $Name
                <a style="background-color: #{$Company.CompanyColor}" href="$Call2ActionUri" rel="nofollow" class="primary-action-button outbound-link">Details &amp; Signup</a>
            </h1>
            <p>$Overview</p>
        </div>