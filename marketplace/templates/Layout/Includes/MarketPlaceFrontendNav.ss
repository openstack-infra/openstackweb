<div class="container">
<div class="row marketplace-top-wrapper">
<div class="col-lg-2 col-md-12 marketplace-brand">
    <h2 class="marketplace">
        <a href="/marketplace/">OpenStack</a>
    </h2>
    <h1 class="marketplace">
        <a href="/marketplace/">Marketplace</a>
    </h1>
</div>

<% with Top %>
<div class="col-lg-9 col-lg-offset-1 col-md-12">
    <ul class="marketplace-nav">
        <% if canViewTab(1) %>
            <li id="training">
                <a href="{$getMarketPlaceTypeLink(1)}">
                    <span></span>
                    Training
                    <br>
                    &nbsp;
                </a>
            </li>
        <% end_if %>
        <% if canViewTab(2) %>
            <li id="distros">
                <a href="{$getMarketPlaceTypeLink(2)}">
                    <span></span>
                    Distros &
                    <br>
                    Appliances
                </a>
            </li>
        <% end_if %>
        <% if canViewTab(3) %>
            <li id="public-clouds">
                <a href="{$getMarketPlaceTypeLink(3)}">
                    <span></span>
                    Public <br>Clouds
                </a>
            </li>
        <% end_if %>
        <% if canViewTab(6) %>
            <li id="private-clouds">
                <a href="{$getMarketPlaceTypeLink(6)}">
                    <span></span>
                    Hosted <br />Private Clouds
                </a>
            </li>
        <% end_if %>
        <% if canViewTab(4) %>
            <li id="consulting">
                <a href="{$getMarketPlaceTypeLink(4)}">
                    <span></span>
                    Consulting &
                    <br>
                    Integrators
                </a>
            </li>
        <% end_if %>
        <% if canViewTab(5) %>
            <li id="drivers">
                <a href="{$getMarketPlaceTypeLink(5)}">
                    <span></span>
                     Drivers
                    <br>
                    &nbsp;
                </a>
            </li>
        <% end_if %>
    </ul>
</div>
<% end_with %>
    </div>
</div>
