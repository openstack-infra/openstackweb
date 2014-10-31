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
jQuery(document).ready(function($){

    $('#back_to_news').click(function(event){
        window.location = $(this).attr('data-url');
    });

    $('#go_to_recent').click(function(){
        $("html, body").animate({ scrollTop: $('.newsStandBy').offset().top}, 1000);
        return false;
    });

    $( "#slider_sortable, #featured_sortable" ).sortable({
        items: "li:not(.placeholder_empty)",
        connectWith: ".connected",
        revert: false,
        placeholder: "placeholder",
        over: function(event,ui) {
            if (ui.sender) {
                $(".placeholder_empty",this).first().before(ui.placeholder);
                $(ui.placeholder).hide();
            }
        },
        remove: function(event,ui) {
            $(this).append('<li class="placeholder_empty">Drop<br> here</li>');
        },
        update: function(event,ui) {
            if (ui.sender) {
                if ($(".placeholder_empty",this).length == 0) {
                    $(ui.sender).sortable('cancel');
                    $(".placeholder_empty",ui.sender).first().remove();
                } else {
                    $(".placeholder_empty",this).first().remove();
                    saveSortArticle(ui.item,true);
                }
            } else {
                saveSortArticle(ui.item,false);
            }
        }
    }).disableSelection();

    $( "#recent_sortable, #standby_sortable" ).sortable({
        connectWith: ".connected",
        revert: false,
        placeholder: "placeholder",
        update: function(event,ui) {
            var is_new = (ui.sender);
            saveSortArticle(ui.item,is_new);
        }
    }).disableSelection();

    $('.newsDelete').click(function(){
        if (confirm("Do you wish to delete this article?")) {
            deleteArticle($(this).parents('li'));
        }
    });

    $('.newsRemove').click(function(){
        removeArticle($(this).parents('li'));
    });

});

function saveSortArticle(item,is_new) {
    var old_rank = jQuery('.article_rank',item).val();
    var new_rank = item.index() + 1;
    var article_id = jQuery('.article_id',item).val();
    var article_type = jQuery('.article_type',item).val();
    var target = jQuery(item).parents('ul').attr('id').split('_')[0];

    is_new = (is_new) ? 1 : 0;

    jQuery.ajax({
        type: "POST",
        url: 'NewsAdminPage_Controller/setArticleRank',
        data: { id : article_id, old_rank : old_rank, new_rank : new_rank, type : article_type, target: target, is_new : is_new },
        success: function(){ //update ranks
            if (article_type == target) {
                jQuery('.article_rank','#'+article_type+'_sortable').each(function(index){
                    jQuery(this).val(index+1);
                });
            } else {
                jQuery('.article_type',item).val(target);
                jQuery('.article_rank','#'+target+'_sortable').each(function(index){
                    jQuery(this).val(index+1);
                });
            }
        }
    });
}

function deleteArticle(article) {
    var article_id = jQuery('.article_id',article).val();

    jQuery.ajax({
        type: "POST",
        url: 'NewsAdminPage_Controller/deleteArticle',
        data: { id : article_id},
        success: function(){
            jQuery(article).remove();
        }
    });
}

function removeArticle(article) {
    var article_id = jQuery('.article_id',article).val();
    var article_type = jQuery('.article_type',article).val();
    var old_rank = jQuery('.article_rank',article).val();


    jQuery(article).parents('ul').append('<li class="placeholder_empty">Drop<br> here</li>');
    jQuery(article).children('div').removeClass().addClass('standbyBox');
    jQuery('.article_type',article).val('standby');
    jQuery('.newsRemove',article).html('Delete').removeClass().addClass('newsDelete');
    jQuery('#standby_sortable').prepend(article);

    jQuery.ajax({
        type: "POST",
        url: 'NewsAdminPage_Controller/removeArticle',
        data: { id : article_id, type : article_type, old_rank : old_rank},
        success: function(){

            jQuery('.article_type',article).val('standby');
            jQuery('.article_rank','#'+article_type+'_sortable').each(function(index){
                jQuery(this).val(index+1);
            });
        }
    });
}

