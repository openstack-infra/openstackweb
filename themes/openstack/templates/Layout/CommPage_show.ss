    <% if Menu(2) %>
    	<div id="subnav" class="span-5">
    		<% include SubMenu %>
    	</div>
    	<div class="span-19 last">
    <% else %>
    	<div class="span-24 last">
    <% end_if %>
      
         <% loop CommMember %>
          
             <h2>$Name</h2>
              
             $Photo.CroppedImage(250,250)
      
             <p>$Description</p>
          
         <% end_loop %>
   