
<form $FormAttributes> 

<% if Message %> 
<p id="{$FormName}_error" class="message $MessageType">$Message</p> 
<% else %> 
<p id="{$FormName}_error" class="message $MessageType" style="display: none;"></p> 
<% end_if %>

<div id="file-well" class="no-selected-file">
	<p>No file has been selected yet.</p>


	<div id="uploadProgressBarOuterBarG">
		<div id="uploadProgressBarFrontBarG" class="uploadProgressBarAnimationG">
			<div class="uploadProgressBarBarLineG">
			</div>
			<div class="uploadProgressBarBarLineG">
			</div>
			<div class="uploadProgressBarBarLineG">
			</div>
			<div class="uploadProgressBarBarLineG">
			</div>
			<div class="uploadProgressBarBarLineG">
			</div>
			<div class="uploadProgressBarBarLineG">
			</div>
		</div>
	</div>


</div>

<fieldset>


    $Fields.dataFieldByName(UploadedMedia)
<div class="browseButton">Select a file using the button above.</div>
    $Fields.dataFieldByName(SecurityID)

</fieldset>

<% if Actions %> 
<div class="Actions"> 
<% loop Actions %>
$Field 
<% end_loop %>
</div> 
<% end_if %>

</form>

