<div class="course-box">
    <div class="course-description">
        <div class="span-16 last">
            <div class="span-12">
                <h3 style="color: #{$Top.Company.CompanyColor}" id="course_{$CourseID}">
                    $CourseName
                </h3>
            </div>
            <div class="span-4 last">
                <span class="{$LwrLevel}">$Level Level</span>
            </div>
        </div>
        <p>$Description&nbsp;</p>

        <% if Projects %>
        <ul class="projects-covered">
           <% loop Projects %>
                <li>
                    $Name
                </li>
           <% end_loop %>
        </ul>
        <% end_if %>

        <div class="span-16 last">
            <table>
                <tbody><tr style="color: #{$Top.Company.CompanyColor}">
                    <th class="location">Location</th>
                    <th class="start">Starts</th>
                    <th class="end">Ends</th>
                    <th class="duration">Duration</th>
                    <th>&nbsp;</th>
                </tr>
                <% if IsOnline %>
                    <tr>
                        <td>Online Only</td>
                        <td>Ongoing</td>
                        <td>Ongoing</td>
                        <td>&nbsp;</td>
                        <td><a style="color: #{$Top.Company.CompanyColor}" href="$Link" class="outbound-link">Register</a></td>
                    </tr>
                <% else %>
                    <% loop CurrentLocations %>
                    <tr>
                        <td>$City, $Country</td>
                        <td>$StartDateMonth $StartDateDay, $StartDateYear</td>
                        <td>$EndDateMonth $EndDateDay, $EndDateYear</td>
                        <td>$Days Days</td>
                        <td><a style="color: #{$Top.Company.CompanyColor}" href="$Link"  class="outbound-link">Register</a></td>
                    </tr>
                    <% end_loop %>
                <% end_if %>
                </tbody>
            </table>
        </div>
    </div>
</div>
