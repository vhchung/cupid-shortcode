/**
 * Created by phuongth on 2/27/15.
 */

(function($) {
    "use strict";
    $(document).ready(function(){
        $("a[rel^='prettyPhoto']").prettyPhoto(
            {
                theme: 'light_rounded',
                slideshow: 5000,
                deeplinking: false,
                social_tools: false
            });
    });
    $(window).load(function(){
        if($.isFunction($.fn.infinitescroll)){
            $('.gallery-infinite-scroll').each(function(){
                var id = $(this).attr('id');
                $('#' + id).infinitescroll({
                    navSelector  	: "#infinite_scroll_button_" + id,
                    nextSelector 	: "#infinite_scroll_button_" + id + " a",
                    itemSelector 	: ".gallery-wrapper .gallery-item",
                    loading : {
                        'selector' : '#infinite_scroll_loading_' + id,
                        'img' : cupid_gallery_ajax_url + 'assets/images/ajax-loader.gif',
                        'msgText' : 'Loading...',
                        'finishedMsg' : ''
                    }
                }, function(newElements, data, url){
                    $("a[rel^='prettyPhoto']").prettyPhoto(
                        {
                            theme: 'light_rounded',
                            slideshow: 5000,
                            deeplinking: false,
                            social_tools: false
                        });
                });
            })

        }

        $('button.gallery-load-more').click(function(event){
            event.preventDefault();
            var $this = $(this).button('loading');
            var contentWrapper = '#' + $(this).attr('data-wrapper-id');
            var link = $(this).attr('data-href');
            $.get(link,function(data) {
                var next_href = $('.gallery-load-more',data).attr('data-href');
                var element = '.gallery-item';
                var $newElems = $(element,data).css({
                    opacity: 0
                });

                $(contentWrapper).append($newElems);

                $newElems.imagesLoaded(function () {
                    $newElems.animate({
                        opacity: 1
                    });
                    $("a[rel^='prettyPhoto']").prettyPhoto(
                        {
                            theme: 'light_rounded',
                            slideshow: 5000,
                            deeplinking: false,
                            social_tools: false
                        });
                });

                if (typeof(next_href) == 'undefined') {
                    $this.hide();
                } else {
                    $this.button('reset');
                    $this.attr('data-href',next_href);
                }

            });
        });
    });

})(jQuery);
