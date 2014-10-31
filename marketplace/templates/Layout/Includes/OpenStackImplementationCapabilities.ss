<% if Capabilities %>
    <script  type="text/javascript">
        var coverages = [];
    </script>
    <hr>
    <h3 style="color: #{$Company.CompanyColor} !important;">OpenStack API Coverage</h3>
    <table class="api-coverage">
        <tbody>
            <% loop Capabilities %>
                <% if SupportsVersioning %>
                    <script type="text/javascript">
                        coverages.push($CoveragePercent);
                    </script>
                    <% with ReleaseSupportedApiVersion %>
                        <% if ApiVersion %>
                        <% with OpenStackComponent %>
                        <tr>
                        <td style="max-width:200px;" width="60%">
                        $Name API
                        <% if SupportsExtensions %> & Extensions<% end_if %>
                        </td>
                        <td width="30%">
                        $CodeName
                        <% end_with %>
                        <% with ApiVersion %> $Version<% end_with %>
                        </td>
                        <td width="10%" class="coverage"></td>
                        </tr>
                        <% end_if %>
                    <% end_with %>
                <% end_if %>
            <% end_loop %>
        </tbody>
    </table>
<% end_if %>