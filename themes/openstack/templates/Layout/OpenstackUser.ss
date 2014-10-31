
<h1>$Title Uses OpenStack</h1>
<hr />


			<div class="span-12">
			<h2 class="user-story-quote">$PullQuote</h2>
			<p><strong class="user-story-quote-author">$PullQuoteAuthor</strong></p>
			<hr />
			
			<% if Children %>
			<% loop Children %>
				<h4>Case Study:</h4>
				<h1><a href="$Link">$Title</a></h1>
				<p>$Content.LimitSentences(3)</p>
				<p><a href="$Link" class="roundedButton">Read the case study</a></p>
			<% end_loop %>
			<% end_if %>
			
			<p></p>
			<hr />
			
			
			<% if Objectives %>
			<h4>OpenStack Objectives for $Title:</h4>
			
			<div class="user-objectives">$Objectives</div>
			<% end_if %>
			
			<% if Attachments %>
			<h4>Downloads</h4>
			<ul class="user-downloads">
				<% loop Attachments %>
				<li><a href="$Link">$Name ($Size)</a></li>
				<% end_loop %>
			</ul>
			<% end_if %>
			
			<% if Links %>
			<h4>Links About $Title</h4>
			<ul class="user-links">
				<% loop Links %>
				<li><a href="$URL">$Label</a></li>
				<% if Description %>
					<p>$Description</p>
				<% end_if %>
				<% end_loop %>
			</ul>
			<% end_if %>
			
			
			</div>
			
			<div class="prepend-1 span-11 last">
			
			<div class="user-photo">
			<% loop Photos %>
				$SetWidth(410)
			<% end_loop %>
			</div>
			
			<h2 class="user-name">$Title</h2>			
			
			<div class="span-8">
			
			<ul>
				<% if URL %>
				<li><a href="$URL">$URL</a></li>
				<% end_if %>
				<% if Industry %>
					<li><strong>Industry:</strong> $Industry</li>
				<% end_if %>
				<% if Headquarters %>
				<li><strong>Headquarters:</strong> $Headquarters</li>
				<% end_if %>
				<% if Size %>
				<li><strong>Size:</strong> $Size</li>
				<% end_if %>
			</ul>
			</div>
			
			<div class="span-3 last">
				$Logo.SetWidth(90)
			</div>
			<hr />
			<h4>OpenStack technologies $Title uses: </h4>
			<ul class="user-project-list">
				<% loop Projects %>
					<li>$Name</li>
				<% end_loop %>
			</ul>
			</div>