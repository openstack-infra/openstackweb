<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
        
    <!-- Use title if it's in the page YAML frontmatter -->
    <title>All Presentations</title>
    
  </head>
  
  <body>


    <% loop FullPresentationList %>

      <a href="{$Top.Link}Presentation/{$URLSegment}">$PresentationTitle</a>

    <% end_loop %>


  </body>


</html>