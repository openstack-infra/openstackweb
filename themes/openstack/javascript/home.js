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
        $(".change-description").text("The world relies on Bloomberg for billions of financial data points per day.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#bestbuy-logo").hover(function() {
        $(".change-description").text("Development teams at BestBuy rely on OpenStack to continuously deploy new features.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#sony-logo").hover(function() {
        $(".change-description").text("Sony relies on Openstack to deliver connected gaming experiences to millions of gamers.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#comcast-logo").hover(function() {
        $(".change-description").text("Comcast delivers interactive entertainment to millions of living rooms.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#paypal-logo").hover(function() {
        $(".change-description").text("PayPal delivers features faster with their OpenStack private cloud.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});
$(function() {
    $("#wells-logo").hover(function() {
        $(".change-description").text("The world’s most valuable bank relies on OpenStack.");
        $(".customer-logos.logo-hover").removeClass("logo-hover");
        $(this).toggleClass("logo-hover");
    });
});