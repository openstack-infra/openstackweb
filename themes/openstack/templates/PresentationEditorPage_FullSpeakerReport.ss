<!doctype html>
<html>
  <head>
  </head>
  <body class="index">
      <h1>Speakers With Accepted or Alternate Talks</h1>
      <% loop AllAcceptedOrALternateSpeakers %>
        "{$FirstName}", 
        "{$Surname}", 
        "{$Member.Email}", 
        "{$OnsiteNumber}", 
        "<% if Confirmed %> Y <% else %> N <% end_if %>", 
        "$RegistrationCode.Code", 
        "<% if Bio %> Y <% else %> N <% end_if %>", 
        "<% if Photo %> Y <% else %> N <% end_if %>",
        "<% if AcceptedTalks %> Accepted <% if AlternateTalks %> and <% end_if %><% end_if %>
        <% if AlternateTalks %> Alternate <% end_if %>"
        <br/>
      <% end_loop %>
      <% loop AllUnAcceptedSpeakers %>
        "{$FirstName}", 
        "{$Surname}", 
        "{$Member.Email}", 
        "{$OnsiteNumber}", 
        "<% if Confirmed %> Y <% else %> N <% end_if %>", 
        "$RegistrationCode.Code", 
        "<% if Bio %> Y <% else %> N <% end_if %>", 
        "<% if Photo %> Y <% else %> N <% end_if %>",
        "None Accepted"
        <br/>
      <% end_loop %>
  </body>
</html>