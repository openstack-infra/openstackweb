<div class="product-box row" style="border-left-color: #{$Company.CompanyColor}">
    <div class="col-lg-3 col-md-5 col-sm-6">
        <div class="logo-area">
            <span style="background-color: #{$Company.CompanyColor}" class="color-bar"></span>
            <a href="$ConsultantLink{$Company.URLSegment}/{$Slug}">
                $Company.SmallLogoPreview(150)
            </a>
        </div>
    </div>
    <div class="col-lg-8 col-md-7 col-sm-6">
        <div class="company-details-area">
            <h4>
                <a style="color: #{$Company.CompanyColor}" href="$ConsultantLink{$Company.URLSegment}/{$Slug}">
                    $Name
                </a>
            </h4>
            <p>$Overview</p>
            <a style="background-color: #{$Company.CompanyColor}" href="$ConsultantLink{$Company.URLSegment}/{$Slug}" class="details-button">Details</a>
        </div>
    </div>
</div>