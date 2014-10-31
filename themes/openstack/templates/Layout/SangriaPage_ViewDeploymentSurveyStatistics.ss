<h2>Deployment Surveys Submitted &mdash; $DeploymentSurveysCount total</h2>
$DateFilters
<div class="span-8 ">
    <h3>Industry</h3>
        <table>
        <% loop IndustrySummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8">
    <h3>Other Industry</h3>
    <div style="overflow:scroll; height:400px;">
        <% loop OtherIndustry %>
          $OtherIndustry<br/>
          <hr/>
        <% end_loop %>
    </div>
</div>
<div class="span-8 last">
    <h3>Organization Size</h3>
        <table>
        <% loop OrganizationSizeSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
    <h3>OpenStack involvement</h3>
        <table>
        <% loop InvolvementSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>


<div class="span-8">
    <h3>Information Sources</h3>
        <table>
        <% loop InformationSourcesSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8">
    <h3>Other Sources</h3>
    <div style="overflow:scroll; height:300px;">
        <% loop OtherInformationSources %>
          $OtherInformationSources<br/>
          <hr/>
        <% end_loop %>
    </div>
</div>
<div class="span-8 last">
    <h3>Further Enhancements</h3>
    <div style="overflow:scroll; height:300px;">
        <% loop FurtherEnhancement %>
          $FurtherEnhancement<br/>
          <hr/>
        <% end_loop %>
    </div>
</div>


<div class="span-8">
    <h3>Business Drivers</h3>
        <table>
        <% loop BusinessDriversSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8 last">
    <h3>Other Drivers</h3>
    <div style="overflow:scroll; height:300px;">
        <% loop OtherBusinessDrivers %>
          $OtherBusinessDrivers<br/>
          <hr/>
        <% end_loop %>
    </div>
</div>


<div class="span-8">
    <h3>What do you like most?</h3>
    <div style="overflow:scroll; height:300px;">
        <% loop WhatDoYouLikeMost %>
          $WhatDoYouLikeMost<br/>
          <hr/>
        <% end_loop %>
    </div>
</div>

<div class="span-8">
    <h3>Committee Priorities</h3>
    <div style="overflow:scroll; height:300px;">
        <% loop FoundationUserCommitteePriorities %>
          $FoundationUserCommitteePriorities<br/>
          <hr/>
        <% end_loop %>
    </div>
</div>

</div>
