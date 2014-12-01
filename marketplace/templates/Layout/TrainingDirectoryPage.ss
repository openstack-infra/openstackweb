<script>
    var AutoCompleteUrls = {
        TopicSearchUrl:    "training/topics",
        LocationSearchUrl: "training/locations",
        LevelSearchUrl:    "training/levels"
    };
    var Results = {
        SearchUrl: "training/search"
    };

</script>
<div class="grey-bar">
    <div class="container">
        <p class="filter-label">Filter Courses</p>
        <input type="text" id="topic-term" placeholder="ANY TOPIC" name="topic-term">
        $LocationCombo
        $LevelCombo
    </div>
</div>
<div class='container'>
    <div id="training-list" class='col-lg-8 col-md-8 col-sm-8'>
        <% if Trainings %>
            <% loop Trainings %>
                <% include TrainingDirectoryPage_CompanyTraining TrainingLink=$Top.Link%>
            <% end_loop %>
        <% end_if %>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4">
        <h3>OpenStack Online Help</h3>
        <ul class="resource-links">
            <li>
                <a href="http://docs.openstack.org/" class="outbound-link">Online Docs</a>
            </li>
            <li>
                <a href="http://docs.openstack.org/ops/" class="outbound-link">Operations Guide</a>
            </li>
            <li>
                <a href="http://www.openstack.org/blog/2013/07/openstack-security-guide-now-available/" class="outbound-link">Security Guide</a>
            </li>
            <li>
                <a href="http://www.openstack.org/software/start/" class="outbound-link">Getting Started</a>
            </li>
        </ul>
        <% if UpcomingCourses %>
        <h3>
            Upcoming Classes
        </h3>
        <ul class="training-updates">
            <% loop UpcomingCourses %>
            <li>
                <p class="date-block">
                    <span class="month">$StartDateMonth</span>
                    <span class="day">$StartDateDay</span>
                </p>
                <p>
                    <a href="$Top.Link{$BookMark}" class="outbound-link">$CourseName</a><br>
                    $City
                </p>
            </li>
            <% end_loop %>
        </ul>
        <% end_if %>
        <div class="add-your-course">
            <p>
                Does your company offer products or services that belong in the marketplace? email us for details
                <a href="mailto:ecosystem@openstack.org">Email us for details</a>
            </p>
        </div>
    </div>
</div>

