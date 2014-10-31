<div class="container">
    $SetCurrentTab(5)
    <% require themedCSS(profile-section) %>
    <h1>$Title</h1>
    <% if CurrentMember %>
        <% include ProfileNav %>
        <% if CurrentMember.isMarketPlaceAdmin %>
            <fieldset>
                <h2>MarketPlace</h2>
                <hr>
                <p><strong>OpenStack Marketplace Content Guidelines</strong></p>
                <p>Thank you for participating in the OpenStack Marketplace!  Before submitting any content to the Marketplace, please make sure that all submissions conform to the following guidelines:</p>
                <ul>
                    <li>Our visitors are looking for OpenStack specific facts about your products and services, so please include this type of data to help visitors make informed decisions.</li>
                    <li>Never disparage another company’s products, services, or brands, whether they are also involved in the marketplace or not.</li>
                    <li>Be responsible about any content you link to from the “Additional Resources” section to make sure that it’s relevant, does not pose a security threat to users, and fits within the general <a href="https://www.openstack.org/legal/community-code-of-conduct" target="_top">code of conduct</a> of our community.  </li>
                    <li>The content you submit to the Marketplace must be true and correct and must not be misleading, harmful, threatening, abusive, harassing, defamatory, offensive, violent, obscene, pornographic, vulgar, libelous, racially, ethnically, religiously or otherwise objectionable.</li>
                    <li>By submitting content to the Marketplace, you represent and warrant that you own or otherwise have permission to submit any such content and that the submission does not infringe any patent, trademark, trade secret, publicity right, privacy right, copyright or other intellectual property or any rights of any party.</li>
                </ul>
                <a  class="roundedButton" href="$MarketPlaceManagerLink">Marketplace Admin</a>
            </fieldset>
        <% else %>
            <p>You are not allowed to administer MarketPlace.</p>
        <% end_if %>
    <% else %>
        <p>In order to edit your community profile, you will first need to
            <a href="/Security/login/?BackURL=%2Fprofile%2F">login as a member</a>. Don't have an account?
            <a href="/join/">Join The Foundation</a>
        </p>
        <p>
            <a class="roundedButton" href="/Security/login/?BackURL=%2Fprofile%2F">Login</a>
            <a href="/join/" class="roundedButton">Join The Foundation</a>
        </p>
    <% end_if %></div></div>