<% require themedCSS(filter) %>

<h1>Members</h1>


<p class="linkLetters">

<a href="{$Link}?letter=A">A</a><a href="{$Link}?letter=B">B</a><a href="{$Link}?letter=C">C</a><a href="{$Link}?letter=D">D</a><a href="{$Link}?letter=E">E</a><a href="{$Link}?letter=F">F</a><a href="{$Link}?letter=G">G</a><a href="{$Link}?letter=H">H</a><a href="{$Link}?letter=I">I</a><a href="{$Link}?letter=J">J</a><a href="{$Link}?letter=K">K</a><a href="{$Link}?letter=L">L</a><a href="{$Link}?letter=M">M</a><a href="{$Link}?letter=N">N</a><a href="{$Link}?letter=O">O</a><a href="{$Link}?letter=P">P</a><a href="{$Link}?letter=Q">Q</a><a href="{$Link}?letter=R">R</a><a href="{$Link}?letter=S">S</a><a href="{$Link}?letter=T">T</a><a href="{$Link}?letter=U">U</a><a href="{$Link}?letter=V">V</a><a href="{$Link}?letter=W">W</a><a href="{$Link}?letter=X">X</a><a href="{$Link}?letter=Y">Y</a><a href="{$Link}?letter=Z">Z</a><a class="intl" href="{$Link}?letter=intl">International Characters</a>
</p>

<% loop MemberList.GroupedBy(SurnameFirstLetter) %>
	<div class="filter">
    <h3 class="groupHeading" id="$SurnameFirstLetter">$SurnameFirstLetter</h3>
    <ul>
        <% loop Children %>
            <li><strong>$FirstName $Surname</strong> ($CurrentOrgName) <a href="{$Top.Link}checkNomination/{$ID}">Nominate</a></li>
        <% end_loop %>
    </ul>
	</div>
<% end_loop %>
