<h2>Deployments Submitted &mdash; $DeploymentsCount total (<a href="/sangria/ViewDeploymentStatistics">clear filters</a>)</h2>
$DateFilters
<div class="span-8 ">
    <h3>Is deployment Public?</h3>
        <table>
        <% loop IsPublicSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
    <h3>Deployment Types</h3>
        <table>
        <% loop DeploymentTypeSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8">
    <h3>Projects Used</h3>
        <table>
        <% loop ProjectsUsedSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8 last">
    <h3>Releases Used</h3>
        <table>
        <% loop CurrentReleasesSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>


<div class="span-8 ">
    <h3>API Format</h3>
        <table>
        <% loop APIFormatsSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
    <h3>Deployment Stage</h3>
        <table>
        <% loop DeploymentStageSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8">
    <h3>Hypervisors Used</h3>
        <table>
        <% loop HypervisorsSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8 last">
    <h3>Identity Drivers Used</h3>
        <table>
        <% loop IdentityDriversSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
    <h3>Additional Features</h3>
        <table>
        <% loop SupportedFeaturesSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>


<div class="span-8">
    <h3>Network Drivers Used</h3>
        <table>
        <% loop NetworkDriversSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
    <h3>Network IPs</h3>
        <table>
        <% loop NetworkNumIPsSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8">
    <h3>Block Drivers Used</h3>
        <table>
        <% loop BlockStorageDriversSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8 last">
    <h3>Compute Nodes</h3>
        <table>
        <% loop ComputeNodesSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
    <h3>Compute Cores</h3>
        <table>
        <% loop ComputeCoresSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
    <h3>Compute Instances</h3>
        <table>
        <% loop ComputeInstancesSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>


<div class="span-8">
    <h3>Block Storage Used</h3>
        <table>
        <% loop BlockStorageTotalSizeSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8">
    <h3>Object Storage Used</h3>
        <table>
        <% loop ObjectStorageSizeSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8 last">
    <h3>Objects</h3>
        <table>
        <% loop ObjectStorageNumObjectsSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>


<div class="span-8 ">
    <h3>Number of Users</h3>
        <table>
        <% loop NumCloudUsersSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
    <h3>Deployment Tools</h3>
        <table>
        <% loop DeploymentToolsSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
    <h3>Operating Systems</h3>
        <table>
        <% loop OperatingSystemSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8">
    <h3>Workloads</h3>
        <table>
        <% loop WorkloadsSummary %>
          <tr>
            <td>$Value</td>
            <td>$Count</td>
          </tr>
        <% end_loop %>
        </table>
</div>
<div class="span-8 last">
    <h3>Nova Network</h3>
    <div style="overflow:scroll; height:600px;">
        <% loop WhyNovaNetwork %>
          $WhyNovaNetwork<br/>
          <hr/>
        <% end_loop %>
    </div>
</div>


<div class="span-8">
    <h3>Matching Organzations</h3>
    <div style="overflow:scroll; height:600px;">
        <% loop DeploymentMatchingOrgs %>
          $OrgName<br/>
          <hr/>
        <% end_loop %>
    </div>
</div>
