
<h1>Speaker Submissions (as of today, $Now.day)</h1>

<% loop SpeakerSubmissions %>

<hr/>
<a name="{$ID}"></a>
<div class="span-4">
	$Photo.SetWidth(100)
	<p>&nbsp;</p>
</div>


<div class="span-20 last">
<h3>$LastName, $FirstName</h3>
<div class="span-2"><strong>Email</strong></div>
<div class="span-18 last">$Email &nbsp;</div>

<div class="span-2"><strong>Edit Link</strong></div>
<div class="span-18 last"><a href="{$Top.Parent.Link}call-for-speakers/?edit=1&key={$SpeakerEditHash}">Edit This Submission</a> &nbsp; | &nbsp; <a href="{$Top.Link}remove/?id={$ID}" onclick="return confirm('Do you really want delete this entry?')">Delete</a></div>


<div class="span-2"><strong>Job Title</strong></div>
<div class="span-18 last">$JobTitle &nbsp;</div>

<div class="span-2"><strong>Company</strong></div>
<div class="span-18 last">$Company &nbsp;</div>

<div class="span-2"><strong>Bio</strong></div>
<div class="span-18 last">$Bio &nbsp;</div>

<div class="span-2"><strong>Topic(s)</strong></div>
<div class="span-18 last">$SetMainTopicLinks &nbsp;</div>

<% if MainTopic %>
<div class="span-2"><strong>Main Topic:</strong></div>
<div class="span-18 last"><strong>$MainTopic</strong></div>
<% end_if %>

<div class="span-2"><strong>Title</strong></div>
<div class="span-18 last">$PresentationTitle &nbsp;</div>

<div class="span-2"><strong>Abstract</strong></div>
<div class="span-18 last">$Abstract &nbsp;</div>
</div>


<div class="span-24 last"><p></p></div>



<% end_loop %>