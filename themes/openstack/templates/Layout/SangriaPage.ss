<h1>Dashboard</h1>
<hr />
<div class="span-24 last">
    <h2>Live Metrics</h2>
		<div class="span-8 featuredUserStory">
			<div class="wrapper">
			    <div>
			    <strong>Individual Members:</strong><br/>
			    $IndividualMemberCount
			    </div>
                <div>
                    <strong>Community Members:</strong><br/>
                    $CommunityMemberCount
                </div>
			    <div>
			    <strong>Newsletter Subscribers:</strong><br/>
			    $NewsletterMemberCount ($NewsletterPercentage%)
			    </div>
			    <div>
			    <strong>User Stories / Logos:</strong><br/>
			    $UserStoryCount / $UserLogoCount
			    </div>
			</div>
		</div>
		<div class="span-8 featuredUserStory">
			<div class="wrapper">
			    <div>
			    <strong>Platinum Members:</strong>
			    $PlatinumMemberCount
			    </div>
			    <div>
			    <strong>Gold Members:</strong>
			    $GoldMemberCount
			    </div>
			    <div>
			    <strong>Corporate Sponsors:</strong>
			    $CorporateSponsorCount
			    </div>
			    <div>
			    <strong>Startup Sponsors:</strong>
			    $StartupSponsorCount
			    </div>
			    <div>
			    <strong>Supporting Organizations:</strong>
			    $SupportingOrganizationCount
			    </div>
			    <div><br/>
			    <strong>Total Organizations:</strong>
			    $TotalOrganizationCount
			    </div>
			</div>
		</div>
		<div class="span-8 featuredUserStory last">
			<div class="wrapper">
			    <div>
			    <strong>Non-US Newsletter Subscribers:</strong><br/>
			    $NewsletterInternationalCount ($NewsletterInternationalPercentage%)
			    </div>
			    <div>
			    <strong>Individual Member Country Count:</strong><br/>
			    $IndividualMemberCountryCount
			    </div>
			    <div>
			    <strong>Non-US Organizations:</strong><br/>
			    $InternationalOrganizationCount ($OrgsInternationalPercentage%)
			    </div>
			</div>
		</div>
</div>
<hr class="space" />
<div class="span-24 last">
    <h2>Quick Actions</h2>
    <ul>
        <li><a href="/sangria/ViewSpeakingSubmissions">View Speaking Submissions</a></li>
        <li><a href="/sangria/StandardizeOrgNames">Standardize Organizations</a></li>
        <li><a href="/sangria/ViewDeploymentSurveyStatistics">View Deployment Survey Statistics</a></li>
        <li><a href="/sangria/ViewDeploymentStatistics">View Deployment Statistics</a></li>
        <li>
            <a href="#" class="cvs_download_link">Deployment Survey CSV Download</a>
            <div class="export_filters hidden">
                $DateFilters(ExportSurveyResults)
            </div>
        </li>
        <li>
            <a href="#" class="cvs_download_link" >App Dev Survey CSV Download</a>
            <div class="export_filters hidden">
                $DateFilters(ExportAppDevSurveyResults)
            </div>
        </li>
        <li><a href="$Link(ExportData)">Export Data</a></li>
    </ul>
    <h2>Manage User Stories and Deployments</h2>
    <ul>
        <li>
            <a href="/sangria/ViewCurrentStories">View Current User Stories</a></li>
        <li><a href="/sangria/ViewDeploymentDetails">View Deployment Details</a></li>
    </ul>
    $QuickActionsExtensions
</div>
