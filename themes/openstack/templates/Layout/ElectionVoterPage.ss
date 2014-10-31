<% require themedCSS(filter) %>

<% if CurrentMember %>

			$Content

			<h1>Eligible Voters</h1>


			<p class="linkLetters">

			<a href="{$Link}?letter=A">A</a><a href="{$Link}?letter=B">B</a><a href="{$Link}?letter=C">C</a><a href="{$Link}?letter=D">D</a><a href="{$Link}?letter=E">E</a><a href="{$Link}?letter=F">F</a><a href="{$Link}?letter=G">G</a><a href="{$Link}?letter=H">H</a><a href="{$Link}?letter=I">I</a><a href="{$Link}?letter=J">J</a><a href="{$Link}?letter=K">K</a><a href="{$Link}?letter=L">L</a><a href="{$Link}?letter=M">M</a><a href="{$Link}?letter=N">N</a><a href="{$Link}?letter=O">O</a><a href="{$Link}?letter=P">P</a><a href="{$Link}?letter=Q">Q</a><a href="{$Link}?letter=R">R</a><a href="{$Link}?letter=S">S</a><a href="{$Link}?letter=T">T</a><a href="{$Link}?letter=U">U</a><a href="{$Link}?letter=V">V</a><a href="{$Link}?letter=W">W</a><a href="{$Link}?letter=X">X</a><a href="{$Link}?letter=Y">Y</a><a href="{$Link}?letter=Z">Z</a><a class="intl" href="{$Link}?letter=intl">International Characters</a>
			</p>

			<% loop ElectionVoters.GroupedBy(SurnameFirstLetter) %>
				<div class="filter">
			    <h3 class="groupHeading" id="$SurnameFirstLetter">$SurnameFirstLetter</h3>
			    <ul>
			        <% loop Children %>
			            <li><strong>$FirstName $Surname</strong> ($OrgName) Member since $Created.Format(M d Y)</li>
			        <% end_loop %>
			    </ul>
				</div>
			<% end_loop %>

<% else %>
	<p>In order to view this page, you will first need to <a href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>. Don't have an account? <a href="/join/">Join The Foundation</a></p>
	<p><a class="roundedButton" href="/Security/login/?BackURL=%2Felection%2F2012-board-election%2Fvoters%2F">Login</a> <a href="/join/" class="roundedButton">Join The Foundation</a></p>
<% end_if %>
