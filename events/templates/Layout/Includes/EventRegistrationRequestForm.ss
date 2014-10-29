<% if IncludeFormTag %>
    <form $FormAttributes role="form">
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
            <h2>Event Information</h2>

            <div class="field text " id="title">
                <label for="$FormName_title" class="left">Title</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(title)
                </div>
            </div>
            <div class="field text " id="url">
                <label for="$FormName_url" class="left">url</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(url)
                </div>
            </div>
        </div>

        <div class="form-group">
            <h2>Event Location</h2>

            <div class="field text " id="city">
                <label for="$FormName_city" class="left">City</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(city)
                </div>
            </div>
            <div class="field text " id="state">
                <label for="$FormName_state" class="left">State</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(state)
                </div>
            </div>

            <div class="field text " id="country">
                <label for="$FormName_country" class="left">Country</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(country)
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h2>Event Duration</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2" id="start_date">
                <label for="$FormName_start_date" class="left">Start Date</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(start_date)
                </div>
            </div>
            <div class="col-md-2" id="end_date">
                <label for="$FormName_end_date" class="left">End Date</label>

                <div class="middleColumn">
                    $Fields.dataFieldByName(end_date)
                </div>
            </div>
        </div>

        <div style="position: absolute; left: -9999px;">
            <label for="$FormName_username">Don't enter anything here</label>
            $Fields.dataFieldByName(user_name)
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