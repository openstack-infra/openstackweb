<div class="container">
    $SetCurrentTab(4)
    <% require themedCSS(profile-section) %>
    <h1>$Title</h1>
    <% if CurrentMember %>
        <% include ProfileNav %>
        <% if CurrentMember.isTrainingAdmin  %>
            <form>
                <fieldset>
                    <h2>Training</h2>
                    <hr>
                    <% loop Trainings %>
                        <h3>$Name</h3>
                        $RAW_val(Description)
                        <h4>Courses</h4>
                        <table>
                            <% loop Courses %>
                                <tr>
                                    <td>$Name</td>
                                    <td>$Level.Level</td>
    
                                    <td><a href="/profile/trainingEdit?course_id={$ID}" class="roundedButton">Edit</a></td>
                                    <td><a href="/profile/trainingDelete?course_id={$ID}" class="delete-course roundedButton">Delete</a></td>
                                </tr>
                            <% end_loop %>
                        </table>
                        <a href="/profile/TrainingAddCourse?training_id={$ID}" class="roundedButton">Add New Course</a>
                        <hr>
                    <% end_loop %>
                </fieldset>
            </form>
        <% else %>
            <p>You are not allowed to manage Training Programs.</p>
        <% end_if %>
    <% else %>
        <p>In order to edit your community profile, you will first need to
            <a href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>. Don't have an account?
            <a href="/join/">Join The Foundation</a>
        </p>
        <p>
            <a class="roundedButton" href="/Security/login/?BackURL=%2Fprofile%2F">Login</a>
            <a href="/join/" class="roundedButton">Join The Foundation</a>
        </p>
    <% end_if %>
        </div></div>