
<% require themedCSS(filter) %>

<h1>OpenStack Foundation: Member Directory</h1>

<% if CurrentElection.NominationsAreOpen %>
<h2>Happening Now: Individual Board Member Nominations</h2>
<p>The OpenStack community is currently nominating members for the <a href="/election/">$CurrentElection.Title</a> (as Individual Board Members). To nominate someone search for them using the directory and click the Nominate button in their profile. </p>
<% end_if %>

$MemberSearchForm


<p class="linkLetters">

<a href="{$Link}?letter=A">A</a><a href="{$Link}?letter=B">B</a><a href="{$Link}?letter=C">C</a><a href="{$Link}?letter=D">D</a><a href="{$Link}?letter=E">E</a><a href="{$Link}?letter=F">F</a><a href="{$Link}?letter=G">G</a><a href="{$Link}?letter=H">H</a><a href="{$Link}?letter=I">I</a><a href="{$Link}?letter=J">J</a><a href="{$Link}?letter=K">K</a><a href="{$Link}?letter=L">L</a><a href="{$Link}?letter=M">M</a><a href="{$Link}?letter=N">N</a><a href="{$Link}?letter=O">O</a><a href="{$Link}?letter=P">P</a><a href="{$Link}?letter=Q">Q</a><a href="{$Link}?letter=R">R</a><a href="{$Link}?letter=S">S</a><a href="{$Link}?letter=T">T</a><a href="{$Link}?letter=U">U</a><a href="{$Link}?letter=V">V</a><a href="{$Link}?letter=W">W</a><a href="{$Link}?letter=X">X</a><a href="{$Link}?letter=Y">Y</a><a href="{$Link}?letter=Z">Z</a><a class="intl" href="{$Link}?letter=intl">International Characters</a>
</p>

<% loop MemberList.GroupedBy(SurnameFirstLetter) %>
	<div class="filter">
    <h3 class="groupHeading" id="$SurnameFirstLetter">$SurnameFirstLetter</h3>
    <ul>
        <% loop Children %>
            <li><strong><a href="{$Top.Link}profile/{$ID}">$FirstName $Surname</strong></a><% if CurrentOrgName %> ($CurrentOrgName)<% end_if %></li>
        <% end_loop %>
    </ul>
	</div>
<% end_loop %>
