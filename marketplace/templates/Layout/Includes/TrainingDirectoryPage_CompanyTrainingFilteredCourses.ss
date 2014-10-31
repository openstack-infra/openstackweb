<% if FilteredCourses %>
<div class="training-program-box" style="border-left-color: #{$Company.CompanyColor}">
<div class="row">
    <div class="col-lg-6">
        <div class="logo-area">
            <span style="background-color: #{$Company.CompanyColor}" class="color-bar"></span>
            <a href="{$Top.TrainingURL}">
                $Company.SmallLogoPreview(150)
            </a>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="company-details-area">
            <h1>
                <a href="{$Top.TrainingURL}" style="color: #{$Company.CompanyColor}">
                    $ProgramName
                </a>
            </h1>
            <div>$Description</div>
        </div>
    </div>
</div>
    <div class="span-17 last">
        <div class="course-area">
            <table>
                <tbody><tr style="color: #{$Company.CompanyColor}">
                    <th class="course">Course</th>
                    <th class="level">Level</th>
                    <th class="location-date">Next Location / Date</th>
                </tr>
                <% loop FilteredCourses %>
                <tr>
                    <td><a href="{$Top.DetailsURL}{$BookMark}" >$CourseName</a></td>
                    <td>
                        <span class="$LwrLevel">$Level</span>
                    </td>
                    <% if IsOnline %>
                    <td class="location-date">Ongoing / Online Only</td>
                    <% else %>
                    <td class="location-date">$StartDateMonth $StartDateDay - $EndDateMonth $EndDateDay / $City</td>
                    <% end_if %>
                </tr>
                <% end_loop %>
                </tbody></table>
                <a style="background-color: #{$Company.CompanyColor}" href="{$Top.TrainingURL}" class="details-button">Details</a>
        </div>
    </div>
</div>
<% end_if %>
