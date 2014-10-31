<!doctype html>
<html>
  <head>
  </head>
  
  <body class="index">


      <h1>Full Talks Report</h1>

      <% loop AllTalks %>
        <% if Talk %>
          <% loop Talk %>
            "{$PresentationTitle}",
            "{$SummitCategory.Name}",
            "{$Status}",
          <% else %>
            "",
            "",
            "",
          <% end_loop %>
        <% end_if %>

        <% if Speaker %>
          <% loop Speaker %>
            "{$FirstName}", 
            "{$Surname}", 
            "{$Member.Email}", 
            "{$OnsiteNumber}", 
            "<% if Confirmed %> Y <% else %> N <% end_if %>", 
            "$RegistrationCode.Code", 
            "<% if Bio %> Y <% else %> N <% end_if %>", 
            "<% if Photo %> Y <% else %> N <% end_if %>"
          <% end_loop %>
        <% else %>
            "No Speakers", 
            "", 
            "", 
            "", 
            "", 
            "", 
            "", 
            ""
        <% end_if %>
        <br/>
      <% end_loop %>
  </body>
</html>