<p>Jobs will only be accepted if they are related to services, support, or development for OpenStack. Please be
    specific in your posting about how the job relates to OpenStack. Expect a delay of up to 72 hours before your
    job is posted as we will be moderating all posts.</p>
<% if IncludeFormTag %>
    <form $FormAttributes>
<% end_if %>
<% if Message %>
        <p id="{$FormName}_error" class="message $MessageType">$Message</p>
<% else %>
        <p id="{$FormName}_error" class="message $MessageType" style="display: none"></p>
<% end_if %>

    <fieldset>
        <% if Legend %>
            <legend>$Legend</legend>
        <% end_if %>
        <div id="point_of_contact_container" class="field text">
            <h2>Point Of Contact</h2>

            <p>Contact information will not be displayed on live site</p>

            <div class="field text " id="point_of_contact_name">
                <label class="left" for="$FormName_point_of_contact_name">Name</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(point_of_contact_name)
                </div>
            </div>
            <div class="field text " id="point_of_contact_email">
                <label class="left" for="$FormName_point_of_contact_email">Email</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(point_of_contact_email)
                </div>
            </div>
        </div>

        <div class="section_container">
            <h2>Job Information</h2>

            <div class="field text " id="title">
                <label for="$FormName_title" class="left">Title</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(title)
                </div>
            </div>
            <div class="field text " id="url">
                <label for="$FormName_url" class="left">Link to Job Posting</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(url)
                </div>
            </div>
            <div class="field text " id="description">
                <label for="$FormName_description" class="left">Description</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(description)
                </div>
            </div>
            <div class="field text " id="instructions">
                <label for="$FormName_instructions" class="left">Instructions To Apply</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(instructions)
                </div>
            </div>
            <div class="field text " id="expiration_date">
                <label for="$FormName_expiration_date" class="left">Expiration Date</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(expiration_date)
                </div>
            </div>
            <div class="field text " id="company_name">
                <label for="$FormName_company_name" class="left">Company</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(company_name)
                </div>
            </div>
        </div>

        <div class="section_container">
            <h2>Job Location</h2>

            <div class="field text " id="type">
                <label for="$FormName_type" class="left">Type</label>
                <div class="middleColumn">
                    $Fields.dataFieldByName(location_type)
                </div>
            </div>
            <table id="locations_table">
                <thead>
                    <tr>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <td> $Fields.dataFieldByName(city)</td>
                        <td> $Fields.dataFieldByName(state)</td>
                        <td> $Fields.dataFieldByName(country)</td>
                        <td><button id="add_location"  name="add_location">Add</button></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="honey">
            <label for="$FormName_honey">Don't enter anything here</label>
            $Fields.dataFieldByName(field_98438688)
        </div>
        $Fields.dataFieldByName(SecurityID)
        <div class="clear"><!-- --></div>
    </fieldset>

<% if Actions %>
        <div class="Actions">
            <% loop Actions %>
                $Field
            <% end_loop %>
        </div>
<% end_if %>
<% if IncludeFormTag %>
    </form>
<% end_if %>
<script type="text/javascript">
    tinyMCE.init({
        theme: "advanced",
        mode: "textareas",
        theme_advanced_toolbar_location: "top",
        theme_advanced_buttons1: "formatselect,|,bold,italic,underline,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,outdent,indent,separator,undo,redo",
        theme_advanced_buttons2: "",
        theme_advanced_buttons3: "",
        height: "250px",
        width: "800px"
    });
</script>
