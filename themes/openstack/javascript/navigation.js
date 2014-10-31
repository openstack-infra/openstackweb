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
// Open header drop downs on hover
jQuery(document).ready(function($){

    if ($(window).width() > 767) {
        $('ul.navbar-main li ul.dropdown-menu').addClass('dropdown-hover');
        $('ul.navbar-main li').hover(function() {
          $(this).find('.dropdown-hover').stop(true, true).delay(400).fadeIn(100);
      }, function() {
          $(this).find('.dropdown-hover').stop(true, true).delay(100).fadeOut(200);
      });
    } else {
        $('ul.navbar-main li ul.dropdown-menu').removeClass('dropdown-hover');
    }

    // Close header search bar
    $(".close-search").click(function() {
        $(".navbar-main").toggleClass("show");
        $(".search-container").toggleClass("show")
        $(".search-icon").toggleClass("show");
    });

    // Open header search bar
    $(".search-icon").click(function() {
        $(".navbar-main").toggleClass("show");
        $(".search-container").toggleClass("show");
        $(".header-search").focus();
        $(".search-icon").toggleClass("show");
        $('#gsc-i-id1').focus();
        // Show placeholder text in Google Search
        $(".gsc-input").attr("placeholder", "search openstack");
    });

    $(window).resize(function () {
        if ($(window).width() > 767) {
            $('ul.navbar-main li ul.dropdown-menu').addClass('dropdown-hover');
            $('ul.navbar-main li').hover(function() {
                $(this).find('.dropdown-hover').stop(true, true).delay(400).fadeIn(100);
            }, function() {
                $(this).find('.dropdown-hover').stop(true, true).delay(100).fadeOut(200);
            });
        } else {
            $('ul.navbar-main li ul.dropdown-menu').removeClass('dropdown-hover');
        }
        if ($(window).width() < 1050) {
            $('#search-label').text('');
        } else {
            $('#search-label').text('Search');
        }
    });

    // Remove Search text in smaller browser windows
    if ($(window).width() < 1050) {
        $('#search-label').text('');
    } else {
        $('#search-label').text('Search');
    }
});