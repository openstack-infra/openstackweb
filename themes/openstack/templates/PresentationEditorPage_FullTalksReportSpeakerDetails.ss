<!doctype html>
<html>
  <head>
  </head>
  
  <body class="index">


      <h1>Full Talks Report</h1>

      <% loop AllTalks %>
         "{$PresentationTitle}",
         "{$SummitCategory.Name}",
         "{$Status}",
        <% if Speakers %>
          "
          <% loop Speakers %>
            {$FirstName} 
            {$Surname}<% if Last %><% else %>,<% end_if %>
          <% end_loop %>
          "
      <% else %>
        "No Speakers"
      <% end_if %>
      <br/>
      <% end_loop %>

  </body>
</html>