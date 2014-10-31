<div id="reviews" style="min-height: 400px;">
    <h3 style="color: #{$Company.CompanyColor} !important;">Reviews</h3>
    <!--This script should be places anywhere on a page you want to see rating box-->
    <div class="review-container">
        <script type="text/javascript">
            var r_obj = {
                "Company": { "CompanyId": $Top.RatingSystemCompanyId },
                "RatingboxId": $Top.RatingSystemRatingBoxId,
                "ProductCode": "{$Company.Name}-{$Slug}",
                "SearchText": "",
                "SortExpression": "",
                "PageIndex": 1,
                "MaxpageDisplay": 10,
                "User": { "UserId": "1" }
            };
            var r_rspage = "rsratereviewbox";
        </script>
        <script type="text/javascript" src="//www.rating-system.com/widget/rsiframe.js"></script>
    </div>
    <div style='font-size:10px'>Powered by <a href="http://www.rating-system.com" target="_blank" title="Ratings and Reviews are powered by Rating-System.com">Rating-System.com</a>
    </div>
    <!-- DO NOT REMOVE THE LAST LINE, please contact us first if you need to do it -->
</div>