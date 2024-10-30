'use strict';

function cbxscratingreview_readonlyrating_process($) {
    //any where read only rating
    $('.cbxscratingreview_readonlyrating_score_js').each(function (index, element) {
        let $element = $(element);


        let $applied = Number($element.data('processed'));

        if ($applied === 0) {
            $(element).cbxscratingreview_raty({
                hints: cbxscratingreview_public.rating.hints,
                noRatedMsg: cbxscratingreview_public.rating.noRatedMsg,
                starType: 'img',
                starHalf: cbxscratingreview_public.rating.img_path + 'star-half.png',                                // The name of the half star image.
                starOff: cbxscratingreview_public.rating.img_path + 'star-off.png',                                 // Name of the star image off.
                starOn: cbxscratingreview_public.rating.img_path + 'star-on.png',                                  // Name of the star image on.
                halfShow: true,
                readOnly: true
            });

            $element.data('processed', 1);
        }


    });
}

let cbxscratingreview_video_filetypes = ['mp4', 'm4v', 'webm', 'ogv', 'wmv', 'flv'];
let cbxscratingreview_audio_filetypes = ['mp3', 'm4a', 'ogg', 'wav', 'wma'];


/**
 * Get file extension from a file name
 *
 * @param $file_url
 * @returns string
 */
function cbxscratingreview_getFileExtension(file_url) {
    return file_url.split('.').pop();
}

/**
 * Check if the string is valid url
 * https://stackoverflow.com/a/14582229/341955
 *
 * @param str
 * @returns {boolean}
 */
function cbxscratingreview_isURL(str) {
    let pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
        '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
    return pattern.test(str);
}

jQuery(document).ready(function ($) {

    $('.cbxscratingreview-form-guest-note a').on('click', function(e) {
        e.preventDefault();

        let $this = $(this);
        let $this_parent = $this.closest('.cbxscratingreview-form-guest-note');

        $this_parent.next('.cbxscratingreview-form-guest-login-wrapper').toggle();
    });

    cbxscratingreview_readonlyrating_process($);

    //delete review
    $('.cbxscratingreview_review_list_items').on('click', '.cbxscratingreview-review-delete', function (e) {
        e.preventDefault();

        let $this      = $(this);
        let $review_id = Number($this.data('reviewid'));
        let $post_id   = Number($this.data('postid'));
        let $busy      = Number($this.data('busy'));

        let $list_parent = $this.closest('.cbxscratingreview_review_list_items');

        if ($busy === 0) {
            let r = confirm(cbxscratingreview_public.delete_confirm);
            if (r === true) {
                $this.data('busy', 1);
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxscratingreview_public.ajaxurl,
                    data: {
                        action: "cbxscratingreview_review_delete",
                        security: cbxscratingreview_public.nonce,
                        review_id: $review_id
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        if (Number(data.success) === 1) {

                            //console.log($this.closest('.cbxscratingreview_review_list_item'));

                            $this.closest('.cbxscratingreview_review_list_item').remove();
                            if ($list_parent.find('.cbxscratingreview_review_list_item').length === 0) {
                                $list_parent.append(cbxscratingreview_public.no_reviews_found_html);

                                $('#cbxscratingreview_search_wrapper_' + $post_id).hide();
                            }
                        } else {
                            $this.data('busy', 0);
                        }
                        alert(data.message);

                    }// end of success
                });// end of ajax
            } else {
                $this.data('busy', 0);
            }
        }
    });

    //load more reviews
    $('.cbxscratingreview_post_more_reviews_js').on('click', 'a.cbxscratingreview_loadmore', function (event) {
        event.preventDefault();

        let $this        = $(this);
        let $parent      = $this.closest('.cbxscratingreview_post_more_reviews_js');
        let $review_list = $parent.prev('.cbxscratingreview_review_list_items');
        let $busy        = Number($this.data('busy'));

        //console.log($review_list);


        let $postid  = Number($this.data('postid'));
        let $perpage = Number($this.data('perpage'));
        let $page    = Number($this.data('page'));
        let $maxpage = Number($this.data('maxpage'));
        let $orderby = $this.data('orderby');
        let $order   = $this.data('order');


        let $score = $this.data('score');

        let $triggerText = $this.text();

        //console.log($postid);


        if ($busy === 0) {
            $this.data('busy', 1);
            $this.text(cbxscratingreview_public.load_more_busy_text);
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: cbxscratingreview_public.ajaxurl,
                data: {
                    action: "cbxscratingreview_post_more_reviews",
                    security: cbxscratingreview_public.nonce,
                    post_id: $postid,
                    perpage: $perpage,
                    page: $page,
                    orderby: $orderby,
                    order: $order,
                    //status  : $status,
                    score: $score,
                    load_more: 0
                    //show_filter:0
                },
                success: function (data, textStatus, XMLHttpRequest) {
                    //console.log(data);
                    $this.data('busy', 0);
                    $this.text($triggerText);


                    //console.log(data);
                    $review_list.append(data);
                    cbxscratingreview_readonlyrating_process($);

                    if ($maxpage == $page) {
                        $parent.remove();
                    } else {
                        $this.data('page', ($page + 1));
                    }

                    //successful load of review items
                    CBXscRatingReviewEvents_do_action('cbxscratingreview_post_more_reviews_success', $this, $parent, $review_list, $postid, $perpage, $page, $maxpage, $orderby, $order);

                }// end of success
            });// end of ajax
        }


    });

    //load more all reviews
    $('.cbxscratingreview_all_more_reviews_js').on('click', 'a.cbxscratingreview_loadmore', function (event) {
        event.preventDefault();

        let $this        = $(this);
        let $parent      = $this.closest('.cbxscratingreview_all_more_reviews_js');
        let $review_list = $parent.prev('.cbxscratingreview_review_list_items');
        let $busy        = Number($this.data('busy'));


        //let $postid  = Number($this.data('postid'));
        let $perpage = Number($this.data('perpage'));
        let $page    = Number($this.data('page'));
        let $maxpage = Number($this.data('maxpage'));
        let $orderby = $this.data('orderby');
        let $order   = $this.data('order');


        let $score = $this.data('score');

        let $triggerText = $this.text();


        if ($busy === 0) {
            $this.data('busy', 1);
            $this.text(cbxscratingreview_public.load_more_busy_text);
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: cbxscratingreview_public.ajaxurl,
                data: {
                    action: "cbxscratingreview_all_more_reviews",
                    security: cbxscratingreview_public.nonce,
                    //post_id : $postid,
                    perpage: $perpage,
                    page: $page,
                    orderby: $orderby,
                    order: $order,
                    //status  : $status,
                    score: $score,
                    load_more: 0
                    //show_filter:0
                },
                success: function (data, textStatus, XMLHttpRequest) {
                    //console.log(data);
                    $this.data('busy', 0);
                    $this.text($triggerText);


                    //console.log(data);
                    $review_list.append(data);
                    cbxscratingreview_readonlyrating_process($);

                    if ($maxpage === $page) {
                        $parent.remove();
                    } else {
                        $this.data('page', ($page + 1));
                    }

                    //successful load of review items
                    CBXscRatingReviewEvents_do_action('cbxscratingreview_all_more_reviews_success', $this, $parent, $review_list, $perpage, $page, $maxpage, $orderby, $order);

                }// end of success
            });// end of ajax
        }


    });

    //search filter
    $('.cbxscratingreview_search_wrapper_js').each(function (index, element) {
        let $element          = $(element);
        let $postid           = Number($element.data('postid'));
        let $review_list      = $('#cbxscratingreview_review_list_items_' + $postid);
        let $review_list_more = $('#cbxscratingreview_post_more_reviews_' + $postid);



        //for any select field click reload reviews.
        $element.find('.cbxscratingreview_search_filter_js').on('change', function (event) {
            event.preventDefault();

            let $order   = $element.find('.cbxscratingreview_search_filter_order').val();
            let $orderby = $element.find('.cbxscratingreview_search_filter_orderby').val();
            let $score   = $element.find('.cbxscratingreview_search_filter_score').val();

            let $perpage = Number($element.data('perpage'));
            let $busy    = Number($element.data('busy'));

            if ($busy === 0) {
                $element.data('busy', 1); //disable click before the request is complete

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxscratingreview_public.ajaxurl,
                    data: {
                        action: 'cbxscratingreview_post_filter_reviews',
                        security: cbxscratingreview_public.nonce,
                        post_id: $postid,
                        perpage: $perpage,
                        page: 1,
                        orderby: $orderby,
                        order: $order,
                        //status  : 1,
                        score: $score
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        $element.data('busy', 0); //allow to click again


                        //console.log($review_list);
                        $review_list.empty();
                        $review_list.append(data.list_html);

                        if (Number(data.load_more) === 1) {
                            $review_list_more.show();
                        } else {
                            $review_list_more.hide();
                        }

                        $review_list_more.data('page', 1);
                        $review_list_more.data('orderby', data.orderby);
                        $review_list_more.data('order', data.order);
                        $review_list_more.data('score', data.score);
                        $review_list_more.data('maxpage', data.maxpage);
                    }// end of success
                });// end of ajax
            }

            //console.log($score);
        });
    });//end for each search wrapper

    //search all reviews filter
    $('.cbxscratingreview_search_wrapper_all_js').each(function (index, element) {
        let $element          = $(element);
        let $postid           = Number($element.data('postid'));
        let $review_list      = $('#cbxscratingreview_review_list_items_' + $postid);
        let $review_list_more = $('#cbxscratingreview_post_more_reviews_' + $postid);
        //console.log($review_list_more);


        //for any select field click reload reviews.
        $element.find('.cbxscratingreview_search_wrapper_all_js').on('change', function (event) {
            event.preventDefault();

            let $order   = $element.find('.cbxscratingreview_search_filter_order').val();
            let $orderby = $element.find('.cbxscratingreview_search_filter_orderby').val();
            let $score   = $element.find('.cbxscratingreview_search_filter_score').val();

            let $perpage = Number($element.data('perpage'));
            let $busy    = Number($element.data('busy'));

            if ($busy === 0) {
                $element.data('busy', 1); //disable click before the request is complete

                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbxscratingreview_public.ajaxurl,
                    data: {
                        action: 'cbxscratingreview_post_filter_reviews',
                        security: cbxscratingreview_public.nonce,
                        post_id: $postid,
                        perpage: $perpage,
                        page: 1,
                        orderby: $orderby,
                        order: $order,
                        //status  : 1,
                        score: $score
                    },
                    success: function (data, textStatus, XMLHttpRequest) {

                        $element.data('busy', 0); //allow to click again


                        $review_list.empty();
                        $review_list.append(data.list_html);

                        if (Number(data.load_more) === 1) {
                            $review_list_more.show();
                        } else {
                            $review_list_more.hide();
                        }

                        $review_list_more.data('page', 1);
                        $review_list_more.data('orderby', data.orderby);
                        $review_list_more.data('order', data.order);
                        $review_list_more.data('score', data.score);
                        $review_list_more.data('maxpage', data.maxpage);
                    }// end of success
                });// end of ajax
            }

            //console.log($score);
        });
    });//end for each search wrapper
});

//for elementor widget render
jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/cbxscratingreviewpostavgrating.default', function ($scope, $) {
        cbxscratingreview_readonlyrating_process(jQuery);
    });
});//end for elementor widget render


/*
jQuery('.stars-cca img').on('click', function(e) {
	e.preventDefault();
	jQuery([document.documentElement, document.body]).animate({
		scrollTop: $("#stars").offset().top
	}, 2000);
});*/
