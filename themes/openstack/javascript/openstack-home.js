/**
 * Copyright 2014 Openstack Foundation
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/
// Hero Credit Tooltip
$('.hero-credit').tooltip()

// Customer Stories, this should be improved
$(function() {
    $("#bloomberg-logo").hover(function() {
        $(".change-description").text("Bloomberg uses OpenStack for some really cool things.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#bestbuy-logo").hover(function() {
        $(".change-description").text("BestBuy is pretty awesome and uses OpenStack in their stores.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#sony-logo").hover(function() {
        $(".change-description").text("Sony's PS4 online network is run by OpenStack, allowing thousands to connect.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#comcast-logo").hover(function() {
        $(".change-description").text("Comcast is using OpenStack to provide real-time programming guides and fast program search.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#paypal-logo").hover(function() {
        $(".change-description").text("PayPal uses OpenStack to run thousands of racks and so many other things too.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#wells-logo").hover(function() {
        $(".change-description").text("Wells Fargo built online versions of heaven with all of the clouds they connected with OpenStack");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});