<div class="container jobPosting"  id="{$ID}">
    <div class="row">
        <% if RecentJob %>
            <p class="type"><span class="label">Type: </span>New!</p>
        <% else %>
            <p class="type"><span class="label">Type: </span></p>
        <% end_if %>
    </div>
    <div class="row">
        <div class="col-md-8">
            <ul class="details">
                <li class="title"><span class="label">Job Title: </span><a href="#" class="jobTitle">$Title</a></li>
                <li class="employer"><span class="label">Employer: </span>at <strong>$JobCompany</strong></li>
            </ul>
        </div>
        <div class="col-md-3 postDate">
            <p><span class="label">Date Posted: </span>$JobPostedDate.format(F) $JobPostedDate.format(d)</p>
        </div>
    </div>
    <% if FormattedLocation %>
        <div class="row">
            <div class="col-md-12">
                <ul class="location">
                    <li>
                        <span class="label">Location: </span>
                        $FormattedLocation
                    </li>
                </ul>
            </div>
        </div>
    <% end_if %>

    <div class="row jobDescription">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div style="max-width:1000px">
                        $Content
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div style="max-width: 1000px">
                        $RAW_val(JobInstructions2Apply)
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="moreInfo">
                        <span class="label">More information: </span><a rel="nofollow" href="$JobMoreInfoLink">More About This Job</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>